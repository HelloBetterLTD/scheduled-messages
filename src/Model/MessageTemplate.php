<?php

namespace SilverStripers\ScheduledMessages\Model;

use SilverStripe\ORM\DataObject;

class MessageTemplate extends DataObject
{

    private static $db = [
        'Label' => 'Varchar',
        'Subject' => 'Varchar',
        'BodyPlain' => 'Text',
        'MessageOnType' => 'Varchar'
    ];

    private static $summary_fields = [
        'Type',
        'Label'
    ];

    private static $table_name = 'MessageTemplate';

    public function getTitle()
    {
        return $this->Label ?? parent::getTitle();
    }

    public function getType() {
        return 'Message';
    }

}
