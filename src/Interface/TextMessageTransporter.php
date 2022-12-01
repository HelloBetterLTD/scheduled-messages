<?php

namespace SilverStripers\ScheduledMessages\Interface;

interface TextMessageTransporter
{

    public function sendText($message, $phone);

}
