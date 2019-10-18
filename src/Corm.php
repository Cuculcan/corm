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

        $output->write(['start generator', "\n"]);

        $dbClass = "\\" . $this->namespace . "\\" . $dbName;
        $output->write([$dbClass, "\n"]);


        $dbClassRef = new \ReflectionClass($dbClass);
        $comment = $dbClassRef->getDocComment();
        $output->write([$comment]);

        $factory  = DocBlockFactory::createInstance();

        $docblock = $factory->create($comment);

        $databaseTag = $docblock->getTagsByName('database');
        if ($databaseTag == null) {
            $output->write(["\n", "database TAG not found\n"]);
        } else {
            $output->write(["\n", $databaseTag[0]]);
        }

        return true;
    }

    public static function databaseBuilder($codeDir, $dbClass)
    {

        $parser = new Parser();
        $classInfo = $parser->parseClassname($dbClass);


        $implDir =  "$codeDir/impl";
        if (!file_exists($implDir)) {
            mkdir($implDir);
        }

        $file = new PhpFile;
        $file->addComment('This file is auto-generated.');
        $namespace = $file->addNamespace($classInfo['namespace'] . "\\Impl");
        $class = $namespace->addClass($classInfo['class_name'] . "_impl")
            ->setExtends('Corm\\Base\\Database');


        $method = $class->addMethod('__construct')
            ->setVisibility('public')
            ->setBody('parent::__construct($connection_params);');

        $method->addParameter('connection_params');
        

       // $parser = new Parser();
        $parser->parseDatabaseClass($dbClass);


        $myfile = fopen($implDir . "/".$classInfo['class_name'] . "_impl.php", "w");
        $printer = new PsrPrinter;
        fwrite($myfile, $printer->printFile($file));
        fclose($myfile);
       



     
    }

    public static function getDatabase($dbClass, $connection_params = null)
    {

        $parser = new Parser();
        $info = $parser->parseClassname($dbClass);
        //$info   = self::parseClassname($dbClass);
        $implClass = $info['namespace'] . "\\Impl\\" . $info['class_name'] . "_impl";

        if (!class_exists($implClass)) {
            throw new ClassNotFoundException("не найден класс реализующий " . $info['class_name']);
        }

        return new $implClass($connection_params);
    }
}
