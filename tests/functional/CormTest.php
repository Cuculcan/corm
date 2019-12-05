<?php

use Corm\Corm;
use Corm\Exceptions\BadParametersException;
use Example\Database\Test;
use Symfony\Component\Console\Output\ConsoleOutput;

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


}
