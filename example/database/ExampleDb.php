<?php

namespace Example\Database;

use Example\Database\Dao\TestDao;
use Example\Database\Dao\TestDao2;
use Corm\Base\CormDatabase;

/**
 * @database(entities={
 *      TestModel,
 *      TestModel2,
 * }, namespace = Example\Database\Entities)
 */
abstract class ExampleDb extends CormDatabase
{

    /**
     * @return  TestDao
     */
    public abstract function testDao(): TestDao;

  
}
