<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

use SilverStripe\Forms\FieldList;

class IsNotEmptyCondition extends Condition
{
    private static $table_name = 'IsNotEmptyCondition';
    
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName('Value1');
        });
        return parent::getCMSFields();
    }

    public function getType()
    {
        return 'is not empty';
    }

    public function getTitle()
    {
        return sprintf('"%s" not is empty', $this->getDataFieldName());
    }

    public function getSQL()
    {
        return sprintf(
            '"%s" IS NOT NULL AND "%s" != \'\'',
            $this->DataField,
            $this->DataField
        );
    }

}
