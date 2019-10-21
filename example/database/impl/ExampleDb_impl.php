<?php

/**
 * This file is auto-generated.
 */

namespace Example\Database\Impl;

use Example\Database\Impl\Dao\TestDao_impl;

class ExampleDb_impl extends \Example\Database\ExampleDb
{
    /** @var TestDao_impl */
    private $_TestDao_impl;

    public function __construct($connection_params)
    {
        parent::__construct($connection_params);
    }

    public function testDao(): \Example\Database\Dao\TestDao
    {
        if($this->_TestDao_impl != null){
            return $this->_TestDao_impl;
        }
        $this->_TestDao_impl = new TestDao_impl();

        return  $this->_TestDao_impl;
    }
}
