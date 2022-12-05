<?php

namespace SilverStripers\ScheduledMessages\Dev;

use SilverStripe\Dev\BuildTask;
use SilverStripers\ScheduledMessages\Model\MessageTemplate;

class ProcessScheduledMessages extends BuildTask
{

    private static $segment = 'scheduled-messages';

    protected $title = 'Process scheduled messages';

    public function run($request)
    {
        /* @var $message MessageTemplate */
        foreach (MessageTemplate::get() as $message) {
            if ($message->canProcess()) { 
                $message->process();
            }
        }
    }
}
