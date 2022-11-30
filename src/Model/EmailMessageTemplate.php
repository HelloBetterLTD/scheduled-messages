<?php

namespace SilverStripers\ScheduledMessages\Model;

class EmailMessageTemplate extends MessageTemplate
{

    private static $db = [
        'Body' => 'HTMLText'
    ];

    public function getType()
    {
        return 'Email';
    }

}
