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

   

    public function testShouldBuldDatabaseFiles()
    {

        $expectedDBDir = __DIR__ . '/../../example/database/impl';
        $expectedDBFile = __DIR__ . '/../../example/database/impl/ExampleDb_impl.php';
        if (file_exists($expectedDBDir)) {
            $this->delTree($expectedDBDir);
        }
        Corm::databaseBuilder( __DIR__ . '/../../example/database', "Example\Database\ExampleDb");

        $this->assertTrue(file_exists($expectedDBFile));
    }

    public function testShouldReturnDatabaseImpl()
    {
        $db = Corm::getDatabase("Example\Database\ExampleDb", \Example\Config::db);
        $this->assertNotNull($db);
    }

    public function testShouldTrowExceptionOnEmptyClassName()
    {
        $this->expectException(BadParametersException::class);
        $db = Corm::getDatabase("");
    }

    private  function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
