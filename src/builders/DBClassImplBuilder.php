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

class DBClassImplBuilder
{

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $codeDir;


   


    private $_phpFile;

    private $_namespace;

    

    public function __construct($codeDir)
    {
        $this->parser =  new Parser();
        $this->codeDir = $codeDir;
      
      
    }

    public function build($dbClassInfo)
    {

        $this->_phpFile = new PhpFile;
        $this->_phpFile->addComment('This file is auto-generated.');
        $this->_namespace =  $this->_phpFile->addNamespace($dbClassInfo->namespace . "\\Impl");
        $class = $this->_namespace->addClass( $dbClassInfo->className . "_impl")
            ->setExtends($dbClassInfo->fullName);


        $method = $class->addMethod('__construct')
            ->setVisibility('public')
            ->setBody('parent::__construct($connection_params);');

        $method->addParameter('connection_params');


        $this->generateDaoAccessMetods($dbClassInfo, $class);
        $this->save($dbClassInfo->className . "_impl.php");
    }

    private function save(string $file_name)
    {
        $implDir =  "$this->codeDir/impl";
        if (!file_exists($implDir)) {
            mkdir($implDir);
        }

        $codeFile = fopen($implDir . "/" . $file_name, "w");
        $printer = new PsrPrinter;
        fwrite($codeFile, $printer->printFile($this->_phpFile));
        fclose($codeFile);
    }

    private function generateDaoAccessMetods(DBClassModel $dbClassInfo, ClassType $class)
    {
        foreach ($dbClassInfo->daoInterfaces as $daoInterface) {

            $this->generateDaoGetterMethod($daoInterface, $class);
        }
    }

    private function generateDaoGetterMethod(DaoGetter $daoGetterMethod, ClassType $classImpl)
    {
        $returnTypeInfo  = $daoGetterMethod->returnTypeInfo;

        $implClassName = $returnTypeInfo['class_name'] . "_impl";
        $implClassNameFull = $returnTypeInfo['namespace'] . "\\Impl\\" . $returnTypeInfo['dao_name'] . "\\" . $implClassName;

      //  echo $implClassNameFull . "\n";

        $this->_namespace->addUse($implClassNameFull);

        $daoVarName = '_' . $implClassName;
        $classImpl->addProperty($daoVarName, null)
            ->setVisibility('private')
            ->addComment('@var ' . $implClassName);

        $method = $classImpl->addMethod($daoGetterMethod->name)
            ->setVisibility('public')
            ->setReturnType($daoGetterMethod->returnType);

        $body = '
if($this->' . $daoVarName . ' != null){
    return $this->' . $daoVarName . ';
}
$this->' . $daoVarName . ' = new ' . $implClassName . '( $this );

return  $this->' . $daoVarName . ';
';

        $method->setBody($body);
    }
}
