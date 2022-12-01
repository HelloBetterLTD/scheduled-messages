<?php

namespace SilverStripers\ScheduledMessages\Cron;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\CronTask\Interfaces\CronTask;
use SilverStripers\ScheduledMessages\Dev\ProcessScheduledMessages;

class ScheduledMessagesCron implements CronTask
{

    public function getSchedule()
    {
        return '* * * * *';
    }

    public function process()
    {
        /* @var $task ProcessScheduledMessages */
        $task = Injector::inst()->get(ProcessScheduledMessages::class);
        $task->run(null);
    }

}
