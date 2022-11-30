<?php

namespace SilverStripers\ScheduledMessages\Model;

use SilverStripe\ORM\DataObject;

class MessageCondition extends DataObject
{

    const LESS_THAN = 'LESS_THAN';
    const GREATER_THAN = 'GREATER_THAN';
    const LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL';
    const GREATER_THAN_OR_EQUAL = 'GREATER_THAN_OR_EQUAL';
    const BETWEEN = 'BETWEEN';

    private static $db = [
        'Field' => 'Varchar',
        'ComparisonType' => 'Varchar',
        'Value1' => 'Varchar',
        'Value2' => 'Varchar'
    ];



}
