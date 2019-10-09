<?php 

use Corm\Corm;
use Symfony\Component\Console\Output\ConsoleOutput;

class CormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        $corm = new Corm("Example");
        $consoleOut = new ConsoleOutput();

        $this->assertTrue($corm->generate("ExampleDB", $consoleOut));

    }
}