<?php

use Corm\Corm;
use Corm\Exceptions\BadParametersException;
use Example\Database\Test;
use Symfony\Component\Console\Output\ConsoleOutput;

use Corm\Parser;
use Corm\Models\DBClassModel;
use Corm\Models\DaoGetter;
use Corm\Models\DaoClassModel;
use Corm\EntitiesParser;
use Corm\Models\EntityModel;
use Corm\Models\FieldModel;

class EntitiesParserTest extends \Codeception\Test\Unit
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



   

    public function testShouldParseEntity()
    {
        
        $entityParser = new EntitiesParser();
        $entity = $entityParser->parseEntity("Model1", "Example\Database\Entities");

        $this->assertTrue($entity instanceof EntityModel);
        $this->assertTrue($entity->tableName == 'model_1');

        $this->assertTrue(is_array($entity->fields));
        $this->assertTrue($entity->fields[0] instanceof FieldModel);
        
    }

    public function testShouldTrowExceptionOnNonExistClass(){
        $this->expectException(BadParametersException::class);

        $entityParser = new EntitiesParser();
        $entity = $entityParser->parseEntity("Model1OLOLO", "Example\Database\Entities");
      
    }
}
