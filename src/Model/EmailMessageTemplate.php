<?php

namespace SilverStripers\ScheduledMessages\Model;

use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripers\ScheduledMessages\Interface\ScheduledMessager;

class EmailMessageTemplate extends MessageTemplate
{

    private static $db = [
        'TestEmailAddress' => 'Varchar',
        'BodyHTML' => 'HTMLText'
    ];

    private static $table_name = 'EmailMessageTemplate';

    public function getCMSFields() : FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName([
                'BodyPlain',
                'TestEmailAddress'
            ]);
            $fields->addFieldToTab(
                'Root.Settings',
                TextField::create('TestEmailAddress', 'Test email address')
            );
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
            $email->setBody($htmlBody);
            $email->send();
        }
    }

    public function isTest()
    {
        return (bool)$this->TestEmailAddress;
    }
}
