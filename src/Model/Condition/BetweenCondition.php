<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

use SilverStripe\Core\Convert;

class BetweenCondition extends Condition
{

    private static $db = [
        'Value2' => 'Varchar'
    ];

    private static $table_name = 'BetweenCondition';

    public function getType()
    {
        return 'is between';
    }

    public function getTitle()
    {
        return sprintf('"%s" %s "%s" and "%s"', $this->getDataFieldName(), $this->getType(), $this->Value1);
    }

    public function getSQL()
    {
        return sprintf(
            '"%s" IS BETWEEN \'%s\' AND \'%s\'',
            $this->DataField,
            Convert::raw2sql($this->Value1),
            Convert::raw2sql($this->Value2)
        );
    }

    protected function getSQLOperator()
    {
        return 'BETWEEN';
    }

}
