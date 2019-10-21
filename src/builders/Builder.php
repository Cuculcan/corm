<?php

namespace Corm\Builders;

use Corm\Exceptions\BadParametersException;
use Corm\Exceptions\ClassNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;
use Example\Database\ExampleDB;
use phpDocumentor\Reflection\DocBlockFactory;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\PhpFile;
use Corm\Parser;
use Corm\Models\DBClassModel;
use Corm\Models\DaoGetter;
use Corm\Builders\DBClassImplBuilder;

class Builder
{

    private $parser;

    public function __construct()
    {
        $this->parser =  new Parser();
    }

    public function build($codeDir, $dbClass)
    {
        $dBclassBuilder  = new DBClassImplBuilder($codeDir, $dbClass);
        $dBclassBuilder->build();

        // $classInfo = $this->parser->parseClassname($dbClass);


        // $implDir =  "$codeDir/impl";
        // if (!file_exists($implDir)) {
        //     mkdir($implDir);
        // }

        // $file = new PhpFile;
        // $file->addComment('This file is auto-generated.');
        // $namespace = $file->addNamespace($classInfo['namespace'] . "\\Impl");
        // $class = $namespace->addClass($classInfo['class_name'] . "_impl")
        //     ->setExtends($dbClass);


        // $method = $class->addMethod('__construct')
        //     ->setVisibility('public')
        //     ->setBody('parent::__construct($connection_params);');

        // $method->addParameter('connection_params');


        // $dbClassInfo = $this->parser->parseDatabaseClass($dbClass);
        // $this->generateDaoAccessMetods($dbClassInfo, $class, $file);

        // $myfile = fopen($implDir . "/" . $classInfo['class_name'] . "_impl.php", "w");
        // $printer = new PsrPrinter;
        // fwrite($myfile, $printer->printFile($file));
        // fclose($myfile);
    }

   
}
