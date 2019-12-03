<?php

use Corm\Corm;
use Corm\Exceptions\BadParametersException;
use Example\Database\Test;
use Symfony\Component\Console\Output\ConsoleOutput;
use Corm\Builders\Builder;
use Corm\Builders\DaoClassImplBuilder;
use Corm\Models\DaoClassModel;


class DaoClassImlpBuilderTest extends \Codeception\Test\Unit
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



    // public function testShouldBuldDaoImplementationClass()
    // {
    //     $codeDir = __DIR__ . '/../../example/database';

    //     $expectedDBFile = $codeDir.'/impl/dao/TestDao_impl.php';
    //     if (file_exists($expectedDBFile)) {
    //         unlink($expectedDBFile);
    //     }

    //     $builder = new DaoClassImplBuilder($codeDir);

    //     $daoModel = new  DaoClassModel();
    //     $daoModel->fullName = 'Example\\Database\\Dao\\TestDao';
    //     $daoModel->classNameInfo = [
    //         'class_name'=>'TestDao',
    //         'namespace' =>'Example\\Database',
    //         'dao_name'  => 'Dao',
    //     ];

    //     $builder->build($daoModel);

    //     $this->assertTrue(file_exists($expectedDBFile));
    // }



    private  function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
