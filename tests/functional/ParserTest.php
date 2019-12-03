<?php

use Corm\Corm;
use Corm\Exceptions\BadParametersException;
use Example\Database\Test;
use Symfony\Component\Console\Output\ConsoleOutput;

use Corm\Parser;
use Corm\Models\DBClassModel;
use Corm\Models\DaoGetter;
use Corm\Models\DaoClassModel;
use Corm\Models\EntityModel;

class ParserTest extends \Codeception\Test\Unit
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



    public function testShouldParseDatabaseCalass()
    {

        $parser = new Parser();
        $result =  $parser->parseDatabaseClass("Example\Database\ExampleDb");

        $this->assertTrue($result instanceof  DBClassModel);

        $this->assertTrue($result->fullName == "Example\Database\ExampleDb");
        $this->assertTrue($result->namespace == "Example\Database");
        $this->assertTrue($result->className == "ExampleDb");
        $this->assertTrue($result instanceof  DBClassModel);
        $this->assertTrue(is_array($result->entities));
        $this->assertTrue(count($result->entities) == 1);
        $this->assertTrue($result->entities[0] instanceof EntityModel);

        $this->assertTrue(is_array($result->daoInterfaces));
        $this->assertTrue(count($result->daoInterfaces) == 1);
        $this->assertTrue($result->daoInterfaces[0] instanceof DaoGetter);
    }

    public function testShouldParseDaoClassName()
    {
        $parser = new Parser();

        $daoClassInfo = $parser->parseDaoClassName('Example\Database\Dao\TestDao');
        $this->assertTrue(is_array($daoClassInfo));
        $this->assertTrue(count($daoClassInfo) == 3);
        $this->assertTrue(array_key_exists('dao_name', $daoClassInfo));
    }

    public function testShouldParseDaoClass()
    {
        $parser = new Parser();
        $daoInfo = $parser->parseDaoClass('Example\Database\Dao\TestDao');
        $this->assertTrue($daoInfo instanceof  DaoClassModel);

    }
}
