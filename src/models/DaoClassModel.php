<?php

namespace Corm\Models;

use Corm\Models\DaoClassMethodModel;

class DaoClassModel 
{
    public $fullName;

    public $classNameInfo;

    /**
     * @var DaoClassMethodModel[]
     */
    public $methods = [];
}