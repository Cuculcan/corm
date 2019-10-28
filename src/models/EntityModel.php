<?php

namespace Corm\Models;

use Corm\Models\FieldModel;

class EntityModel {

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


}