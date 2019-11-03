<?php

namespace Corm\Builders;

use Corm\Models\DaoClassModel;
use Corm\Models\EntityModel;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;


class DaoClassImplBuilder
{
    /**
     * @var string
     */
    private $codeDir;

    /**
     * @var PhpFile
     */
    private $_phpFile;

    /**
     * @var PhpNamespace
     */
    private $_namespace;

    /**
     * @var EntityModel[]
     */
    private $entitiesMap;


    public function __construct($codeDir, $entitiesMap)
    {
        $this->codeDir = $codeDir;
        $this->entitiesMap = $entitiesMap;
    }

    /**
     * @param DaoClassModel daoModel
     */
    public function build(DaoClassModel $daoModel)
    {
        $this->_phpFile = new PhpFile;
        $this->_phpFile->addComment('This file is auto-generated.');
        $this->_namespace =  $this->_phpFile->addNamespace($daoModel->classNameInfo['namespace'] . "\\Impl\\Dao");

        $class = $this->_namespace->addClass($daoModel->classNameInfo['class_name'] . "_impl")
            ->setImplements([$daoModel->fullName]);

        $this->_namespace->addUse("Corm\\Base\\CormDatabase");

        $class->addProperty('_db')
            ->setVisibility('private')
            ->addComment('@var CormDatabase');

        $method = $class->addMethod('__construct')
            ->setVisibility('public')
            ->setBody('$this->_db = $db;');

        $method->addParameter('db')
            ->setTypeHint('Corm\\Base\\CormDatabase');


        $this->generateMethodsImpl($daoModel, $class);
        // $this->generateDaoAccessMetods($dbClassInfo, $class);
        $this->save($daoModel->classNameInfo['class_name'] . "_impl.php");
    }

    private function save(string $file_name)
    {
        $implDir =  "$this->codeDir/impl/dao";
        if (!file_exists($implDir)) {
            mkdir($implDir);
        }

        $codeFile = fopen($implDir . "/" . $file_name, "w");
        $printer = new PsrPrinter;
        fwrite($codeFile, $printer->printFile($this->_phpFile));
        fclose($codeFile);
    }

    private function generateMethodsImpl(DaoClassModel $daoModel, ClassType $classImpl)
    {
        foreach ($daoModel->methods as $method) {
            print_r($method);    

            $method = $classImpl->addMethod($method->name)
                ->setVisibility('public')
                ->setBody('return null;');
        }
    }
}
