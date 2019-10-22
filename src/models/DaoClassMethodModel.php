<?php

namespace Corm\Models;

class DaoClassMethodModel
{
    public $name;

    public $returnType;

    public $query = null;

    /**
     * @var bool
     */
    public $isReturnArray = false;


    public $parameters = [];
}