<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

class GreaterThanOrEqual extends Condition
{

    private static $table_name = 'GreaterThanOrEqual';

    public function getType()
    {
        return 'is greater than or equal';
    }

    protected function getSQLOperator()
    {
        return '>=';
    }

}
