<?php

namespace SilverStripers\ScheduledMessages\Model;

use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripers\ScheduledMessages\Interface\ScheduledMessager;

class EmailMessageTemplate extends MessageTemplate
{

    private static $db = [
        'TemplateFile' => 'Varchar(500)',
        'TestEmailAddress' => 'Varchar',
        'BodyHTML' => 'HTMLText'
    ];

    private static $table_name = 'EmailMessageTemplate';

    private static $template_register = [];

    public function getCMSFields() : FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName([
                'BodyPlain',
                'TestEmailAddress',
                'TemplateFile'
            ]);
            $fields->addFieldToTab(
                'Root.Settings',
                TextField::create('TestEmailAddress', 'Test email address')
            );

            $templates = self::config()->get('template_register');
            if ($templates && !empty($templates)) {
                $fields->addFieldsToTab(
                    'Root.Settings',
                    [
                        HeaderField::create('Templates', 'Select a template for the email')
                            ->setHeadingLevel(3),
                        DropdownField::create('TemplateFile', 'Template')
                            ->setEmptyString('Select a template')
                            ->setDescription('Select a template or leave blank to plain text')
                            ->setSource($templates)
                    ]
                );
            }

            $fields->dataFieldByName('BodyHTML')->setDescription($this->getMergeFieldsList());
            if (empty($this->MessageOnType)) {
                $fields->removeByName([
                    'BodyHTML',
                ]);
            }
        });
        return parent::getCMSFields();
    }

    public function getType() : string
    {
        return 'Email';
    }

    protected function processMessage($object)
    {
        $subject = $this->mergeWithData($object, $this->Subject);
        $htmlBody = $this->mergeWithData($object, $this->BodyHTML);
        $emailAddress = $this->TestEmailAddress ?? $object->getMessageEmail();
        if ($emailAddress) {
            $email = Email::create();
            $email->setTo($emailAddress);
            $email->setSubject($subject);

            if ($this->TemplateFile) {
                $data = [
                    'Body' => $htmlBody,
                    'Subject' => $subject
                ];
                $this->invokeWithExtensions('updateEmailData', $data, $object);
                $email->setData($data);
                $email->setHTMLTemplate($this->TemplateFile);
            } else {
                $email->setBody($htmlBody);
            }
            $email->send();
        }
    }

    public function isTest()
    {
        return (bool)$this->TestEmailAddress;
    }
}
