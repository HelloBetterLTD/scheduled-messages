<?php

namespace SilverStripers\ScheduledMessages\Extension;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataExtension;
use SilverStripers\ScheduledMessages\Model\EmailMessageTemplate;
use SilverStripers\ScheduledMessages\Model\MessageTemplate;
use SilverStripers\ScheduledMessages\Model\TextMessageTemplate;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;

class SiteConfigExtension extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.ScheduledMessages',
            GridField::create('MessageTemplates', 'Message Templates')
                ->setList(MessageTemplate::get())
                ->setConfig(
                    GridFieldConfig_RecordEditor::create()
                        ->addComponent($multiClass = GridFieldAddNewMultiClass::create())
                )
        );
        $multiClass->setClasses([
            EmailMessageTemplate::class => 'Email Message',
            TextMessageTemplate::class => 'Text Message'
        ]);
    }

}
