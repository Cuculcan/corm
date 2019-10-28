<?php

namespace Corm\Models;

use Corm\Models\DaoGetter;

use Corm\Models\EntityModel;

class DBClassModel
{

    /**
     * @var string
     */

    public $fullName;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $className;

    /**
     * @var EntityModel;
     */
    public $entities;

    /**
     * @var DaoGetter[]
     */
    public $daoInterfaces;
}
