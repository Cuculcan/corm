<?php

namespace Example\Database;
use Example\Database\Dao\TestDao;

/**
 * @database(entities={
 *      TestModel
 * })
 * @ololo ololo
 */
abstract class ExampleDB{

    /**
     * @return  TestDao
     */
    public abstract function testDao(): TestDao;
    
}
