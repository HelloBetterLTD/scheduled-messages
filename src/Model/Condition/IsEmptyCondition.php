<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

use SilverStripe\Forms\FieldList;

class IsEmptyCondition extends Condition
{
    
    private static $table_name = 'IsEmptyCondition';

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName('Value1');
        });
        return parent::getCMSFields();
    }

    public function getType()
    {
        return 'is empty';
    }

    public function getTitle()
    {
        return sprintf('"%s" is empty', $this->getDataFieldName());
    }

    public function getSQL()
    {
        return sprintf(
            '"%s" IS NULL OR "%s" = \'\'',
            $this->DataField,
            $this->DataField
        );
    }
}
