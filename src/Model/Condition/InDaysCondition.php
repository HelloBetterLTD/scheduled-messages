<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

use SilverStripe\Core\Convert;
use SilverStripe\ORM\FieldType\DBDatetime;

class InDaysCondition extends Condition
{

    private static $table_name = 'InDaysCondition';

    public function getType()
    {
        return 'in days';
    }


    public function getSQL()
    {
        return sprintf(
            'DATEDIFF(DATE("%s"), DATE(\'%s\')) = %s',
            $this->DataField,
            DBDatetime::now()->getValue(),
            Convert::raw2sql($this->Value1)
        );
    }

    protected function getSQLOperator()
    {
        return '';
    }

}
