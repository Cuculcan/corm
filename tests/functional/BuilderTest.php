<?php

use Corm\Corm;
use Corm\Exceptions\BadParametersException;
use Example\Database\Test;
use Symfony\Component\Console\Output\ConsoleOutput;
use Corm\Builders\Builder;

class BuilderrTest extends \Codeception\Test\Unit
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

   

    public function testShouldBuldDatabaseFiles()
    {

        $expectedDBDir = __DIR__ . '/../../example/database/impl';
        $expectedDBFile = __DIR__ . '/../../example/database/impl/ExampleDb_impl.php';
        if (file_exists($expectedDBDir)) {
            $this->delTree($expectedDBDir);
        }
        
        $builder = new Builder();
        $builder->build( __DIR__ . '/../../example/database', "Example\Database\ExampleDb");

        $this->assertTrue(file_exists($expectedDBFile));
    }

    

    private  function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
