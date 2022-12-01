<?php

namespace SilverStripers\ScheduledMessages\Model;

use SilverStripe\Control\Email\Email;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripers\ScheduledMessages\Interface\ScheduledMessager;
use SilverStripers\ScheduledMessages\Interface\TextMessageTransporter;

class TextMessageTemplate extends MessageTemplate
{

    private static $db = [
        'TestPhoneNumber' => 'Varchar'
    ];

    private static $table_name = 'TextMessageTemplate';

    public function getType()
    {
        return 'Text';
    }

    public function getCMSFields() : FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->addFieldToTab('Root.Settings', TextField::create('TestPhoneNumber', 'Test phone number'));
            $fields->removeByName('Subject');
        });
        return parent::getCMSFields();
    }

    /**
     * @param $object ScheduledMessager
     * @return void
     */
    protected function processMessage($object)
    {
        /* @var $transporter TextMessageTransporter */
        if (($transporter = $this->getTransporter()) && ($phoneNumber = $object->getMessagePhoneNumber())) {
            if ($this->TestPhoneNumber) {
                $phoneNumber = $this->TestPhoneNumber;
            }
            $body = $this->mergeWithData($object, $this->BodyPlain);
            $transporter->sendText($body, $phoneNumber);
        }
    }

    protected function getTransporter()
    {
        $transporters = ClassInfo::implementorsOf(TextMessageTransporter::class);
        if (!empty($transporters)) {
            $transporterClass = reset($transporters);
            return Injector::inst()->get($transporterClass);
        }
        return null;
    }

    public function isTest()
    {
        return (bool)$this->TestPhoneNumber;
    }



}
