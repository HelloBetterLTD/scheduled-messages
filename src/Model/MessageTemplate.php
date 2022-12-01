<?php

namespace SilverStripers\ScheduledMessages\Model;

use SilverStripe\Control\HTTP;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\TimeField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;
use SilverStripers\ScheduledMessages\Interface\ScheduledMessager;
use SilverStripers\ScheduledMessages\Model\Condition\Condition;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;

class MessageTemplate extends DataObject
{

    private static $db = [
        'Label' => 'Varchar',
        'Enabled' => 'Boolean',
        'MessageOnType' => 'Varchar',
        'Subject' => 'Varchar',
        'BodyPlain' => 'Text',
        'MessageWindowStart' => 'Time',
        'MessageWindowEnd' => 'Time',
    ];

    private static $has_many = [
        'Conditions' => Condition::class
    ];

    private static $summary_fields = [
        'Type',
        'Label'
    ];

    private static $table_name = 'MessageTemplate';

    public function getTitle()
    {
        return sprintf('%s (%s)', $this->Label ?? parent::getTitle(), $this->getType());
    }

    public function getType() {
        return 'Message';
    }

    public function canProcess()
    {
        $can = $this->Enabled;
        $time = DBDatetime::now()->Format('HH:mm:ss');
        if ($this->MessageWindowStart && $this->MessageWindowStart > $time) {
            $can = false;
        }
        if ($this->MessageWindowEnd && $this->MessageWindowEnd < $time) {
            $can = false;
        }
        return $can;
    }

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {

            $fields->removeByName([
                'Enabled',
                'Conditions',
                'MessageWindowStart',
                'MessageWindowEnd'
            ]);

            $fields->replaceField(
                'MessageOnType',
                DropdownField::create('MessageOnType', 'Message on data type')
                    ->setSource(self::get_scheduled_classes())
            );

            $fields->dataFieldByName('Subject')->setDescription($this->getMergeFieldsList());
            $fields->dataFieldByName('BodyPlain')->setDescription($this->getMergeFieldsList());

            if (empty($this->MessageOnType)) {
                $fields->removeByName([
                    'Subject',
                    'BodyPlain',
                ]);
            }

            if ($this->exists()) {
                $fields->addFieldsToTab(
                    'Root.Conditions',
                    GridField::create('Conditions')
                        ->setList($this->Conditions())
                        ->setConfig($conditionsConfig = GridFieldConfig_RecordEditor::create())
                );

                $conditionsConfig->addComponent($multiClass = GridFieldAddNewMultiClass::create());
                $conditionsConfig->removeComponentsByType(GridFieldAddNewButton::class);

                $classes = [];
                foreach (ClassInfo::subclassesFor(Condition::class) as $class) {
                    $classes[$class] = Injector::inst()->get($class)->getType();
                }
                $multiClass->setClasses($classes);
            }

            $fields->addFieldsToTab('Root.Settings', [
                CheckboxField::create('Enabled'),
                TimeField::create('MessageWindowStart', 'Window start'),
                TimeField::create('MessageWindowEnd', 'Window end'),
            ]);

        });
        return parent::getCMSFields();
    }

    public static function get_scheduled_classes()
    {
        $classes = ClassInfo::implementorsOf(ScheduledMessager::class);
        $ret = [];
        foreach ($classes as $class) {
            $ret[$class] = Injector::inst()->get($class)->i18n_singular_name();
        }
        return $ret;
    }

    protected function getMergeFieldsList()
    {
        $fields = $this->getMergeFields();
        return sprintf('Merge fields {$%s}', implode('}, {$', $fields));
    }

    public function getMergeFields()
    {
        if ($this->MessageOnType) {
            $object = Injector::inst()->get($this->MessageOnType);
            return $object->getMergeFields();
        }
        return [];
    }

    public function getComparisonFields()
    {
        if ($this->MessageOnType) {
            $object = Injector::inst()->get($this->MessageOnType);
            return $object->getComparisonFields();
        }
        return [];
    }

    public function getSenders()
    {
        /* @var $object DataObject */
        $object = Injector::inst()->get($this->MessageOnType);
        $table = $object->baseTable();

        $list = DataList::create($this->MessageOnType)
            ->where(sprintf(
                'NOT EXISTS (
                    SELECT
                        1
                    FROM
                        "MessageLog"
                    WHERE
                        "MessageLog"."MessageID" = %s
                        AND "MessageLog"."ObjectID" = "%s"."ID"
                        AND "MessageLog"."ObjectClass" = \'%s\'
                    LIMIT 1
                )',
                $this->ID,
                $table,
                addslashes($this->MessageOnType)
            ));


        $sqls = [];
        foreach ($this->Conditions() as $condition) {
            $sqls[] = $condition->getSQL();
        }

        if (!empty($sqls)) {
            $list = $list->where(implode(' AND ', $sqls));
        }
        return $list;
    }

    public function process()
    {
        $objects = $this->getSenders()->limit(5); // we dont want to bulk send a lot of emails
        foreach ($objects as $object) {
            $this->processMessage($object);
            if (!$this->isTest()) {
                $this->markAsSent($object);
            }
        }
    }

    protected function mergeWithData(DataObject $object, $template)
    {
        $fields = $this->getMergeFields();
        $data = [];
        foreach ($fields as $field) {
            if (method_exists($object, $field)) {
                $data[$field] = $object->$field();
            } else if (method_exists($object, 'get' . $field)) {
                $method = 'get' . $field;
                $data[$field] = $object->$method();
            } else {
                $data[$field] = $object->getField($field);
            }
        }

        $template = SSViewer::fromString($template);
        return HTTP::absoluteURLs(ArrayData::create($data)->renderWith($template));
    }

    protected function processMessage($object)
    {
    }

    protected function markAsSent($object)
    {
        $log = MessageLog::create([
            'Message' => $this->ID,
            'Object' => $object
        ]);
        $log->write();
    }

    public function isTest()
    {
        return false;
    }

}
