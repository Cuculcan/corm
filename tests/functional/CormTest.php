<?php

use Corm\Corm;
use Corm\Exceptions\BadParametersException;
use Example\Database\Entities\Model1;

class CormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    { }

    protected function _after()
    {
        ob_flush();
    }

   
    // public function testShouldReturnDatabaseImpl()
    // {
    //     $db = Corm::getDatabase("Example\Database\ExampleDb", \Example\Config::db);
    //     $this->assertNotNull($db);
    // }

    // public function testShouldTrowExceptionOnEmptyClassName()
    // {
    //     $this->expectException(BadParametersException::class);
    //     $db = Corm::getDatabase("");
    // }

    // public function testShouldReturnDatabaseImplAndTestDaoImp()
    // {
    //     $db = Corm::getDatabase("Example\Database\ExampleDb", \Example\Config::db);
    //     $this->assertNotNull($db);

    //     $testDao = $db->testDao();
    //     $this->assertNotNull($testDao);
    // }

    // public function testShouldGetAllData()
    // {
    //     $db = Corm::getDatabase("Example\Database\ExampleDb", \Example\Config::db);
    //     $this->assertNotNull($db);

    //     $testDao = $db->testDao();
    //     $this->assertNotNull($testDao);

    //     $res = $testDao->getAll();
    //     $this->assertNotNull($res);

    //     $this->assertTrue(is_array($res));
    //     $this->assertTrue($res[0] instanceof Model1);

    // }

    // public function testShouldGetById()
    // {
    //     $db = Corm::getDatabase("Example\Database\ExampleDb", \Example\Config::db);
    //     $this->assertNotNull($db);

    //     $testDao = $db->testDao();
    //     $this->assertNotNull($testDao);

    //     $res = $testDao->getById(1);
    //     $this->assertNotNull($res);

    //     $this->assertTrue($res instanceof Model1);
    //     $this->assertTrue($res->getId() == 1);
       
    // }

}
