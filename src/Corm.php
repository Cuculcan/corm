<?php

namespace Corm;

use Corm\Exceptions\BadParametersException;
use Corm\Exceptions\ClassNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;
use Example\Database\ExampleDB;
use phpDocumentor\Reflection\DocBlockFactory;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\PhpFile;
use Corm\Parser;

class Corm
{

    /**
     * @var string 
     */
    private $namespace;

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    public function generate(string $dbName, OutputInterface $output)
    {

       
    }

    public static function getDatabase($dbClass, $connection_params = null)
    {

        $parser = new Parser();
        $info = $parser->parseClassname($dbClass);

        $implClass = $info['namespace'] . "\\Impl\\" . $info['class_name'] . "_impl";

        if (!class_exists($implClass)) {
            throw new ClassNotFoundException("не найден класс реализующий " . $info['class_name']);
        }

        return new $implClass($connection_params);
    }
}
