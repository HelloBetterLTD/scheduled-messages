<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

class LessThanOrEqual extends Condition
{

    private static $table_name = 'LessThanOrEqual';

    public function getType()
    {
        return 'is less than or equal';
    }

    protected function getSQLOperator()
    {
        return '<=';
    }

}
