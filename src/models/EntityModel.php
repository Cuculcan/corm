<?php

namespace Corm\Models;

use Corm\Models\FieldModel;

class EntityModel
{

    /**
     * @var string
     */
    public $className;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $tableName;

    /**
     * @var FieldModel[]
     */
    public $fields = [];

    /**
     * @var string[]
     */
    public $constuctorParams;


    public function getFullClassName()
    {
        return  $this->namespace . '\\' . $this->className;
    }

    public function getFieldByName($name){
        foreach($this->fields as $field){
            if($field->name == $name){
                return $field;
            }
        }

        return null;
    }
}
