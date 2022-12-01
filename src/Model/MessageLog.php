<?php

namespace SilverStripers\ScheduledMessages\Model;

use SilverStripe\ORM\DataObject;

class MessageLog extends DataObject
{

    private static $has_one = [
        'Message' => MessageTemplate::class,
        'Object' => DataObject::class
    ];

    private static $table_name = 'MessageLog';

}
