<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

use SilverStripe\Core\Convert;
use SilverStripe\ORM\FieldType\DBDatetime;

class DaysAgoCondition extends Condition
{

    private static $table_name = 'DaysAgoCondition';

    public function getType()
    {
        return 'is days ago';
    }

    public function getSQL()
    {
        return sprintf(
            'DATEDIFF(DATE(\'%s\'), DATE("%s")) >= ',
            DBDatetime::now()->getValue(),
            $this->DataField,
            Convert::raw2sql($this->Value1)
        );
    }

    protected function getSQLOperator()
    {
        return 'BETWEEN';
    }

}
