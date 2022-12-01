<?php

namespace SilverStripers\ScheduledMessages\Model\Condition;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormField;
use SilverStripe\ORM\DataObject;
use SilverStripers\ScheduledMessages\Model\MessageTemplate;

class Condition extends DataObject
{

    private static $db = [
        'DataField' => 'Varchar',
        'Value1' => 'Varchar',
    ];

    private static $has_one = [
        'Message' => MessageTemplate::class
    ];

    private static $summary_fields = [
        'Title'
    ];

    private static $table_name = 'Condition';



    public function getType()
    {
        return 'equals to';
    }

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function(FieldList $fields) {
            /* @var $template MessageTemplate */
            $template = $this->Message();
            $fields->removeByName('MessageID');
            $fields->replaceField('DataField',
                DropdownField::create('DataField', 'Field')
                    ->setSource($this->getComparisonFields()));
        });
        return parent::getCMSFields();
    }

    public function getComparisonFields()
    {
        $ret = [];
        /* @var $template MessageTemplate */
        $template = $this->Message();
        foreach ($template->getComparisonFields() as $field) {
            $ret[$field] = FormField::name_to_label($field);
        }
        return $ret;
    }

    public function getTitle()
    {
        return sprintf('"%s" %s "%s"', $this->getDataFieldName(), $this->getType(), $this->Value1);
    }

    public function getSQL()
    {
        return sprintf(
            '"%s" %s \'%s\'',
            $this->DataField,
            $this->getSQLOperator(),
            Convert::raw2sql($this->Value1)
        );
    }

    protected function getSQLOperator()
    {
        return '=';
    }

    public function getDataFieldName()
    {
        return FormField::name_to_label($this->DataField);
    }

}
