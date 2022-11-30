<?php

namespace SilverStripers\ScheduledMessages\Model;

class MessageDateCondition extends MessageCondition
{

    const DATE = 'DATE';
    const AGO = 'AGO';
    const IN = 'IN';

    private static $db = [
        'DateProcessFunction' => 'Varchar'
    ];

}
