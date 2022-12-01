<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

class GreaterThanCondition extends Condition
{

    private static $table_name = 'GreaterThanCondition';

    public function getType()
    {
        return 'is greater than';
    }
    
    protected function getSQLOperator()
    {
        return '>';
    }

}
