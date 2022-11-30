<?php

namespace SilverStripers\ScheduledMessages\Model;

class TextMessageTemplate extends MessageTemplate
{

    public function getType()
    {
        return 'Text';
    }

}
