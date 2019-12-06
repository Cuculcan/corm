<?php

namespace Corm\Models;

class MethodParameter
{
    public $name;

    public $type;

    public $defaultValue;

    /**
     * @var bool
     */
    public $isArray = false;

}