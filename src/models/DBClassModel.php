<?php

namespace Corm\Models;

use Corm\Models\DaoGetter;
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

    public $entities;

    public $entities_namespace;

    /**
     * @var DaoGetter[]
     */
    public $daoInterfaces;
}
