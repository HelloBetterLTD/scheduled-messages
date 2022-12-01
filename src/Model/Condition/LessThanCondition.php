<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

class LessThanCondition extends Condition
{

    private static $table_name = 'LessThanCondition';

    public function getType()
    {
        return 'is less than';
    }

    protected function getSQLOperator()
    {
        return '<';
    }

}
