<?php

namespace Corm\Models;

use Corm\Models\MethodParameter;

class DaoClassMethodModel
{
    public $name;

    public $returnType;

    public $query = null;

    /**
     * @var bool
     */
    public $isReturnArray = false;

    /**
     * @var MethodParameter[]
     */
    public $parameters = [];

    
    public $special = null;
}
