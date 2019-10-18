<?php

use Corm\Corm;
use Corm\Exceptions\BadParametersException;
use Example\Database\Test;
use Symfony\Component\Console\Output\ConsoleOutput;

use Corm\Parser;
use Corm\Models\DBClassModel;

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
        $this->assertTrue($result->namespace == "Example\Database");
        $this->assertTrue($result->className == "ExampleDb");
    }

   
}
