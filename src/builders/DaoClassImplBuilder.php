<?php

namespace Corm\Builders;

use Corm\Exceptions\BadParametersException;
use Corm\Models\DaoClassModel;
use Corm\Models\EntityModel;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Corm\Builders\Methods\MethodBuilderFactory;

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
    public function build(DaoClassModel $daoModel, array $entities)
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

        $this->generateMethodsImpl($daoModel, $class, $entities);
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

    /*
    TODO: generate
    - INSERT (MODEL) && INSERT ([models]) 
    - UPDATE (MODEL) && UPDATE (MODEL, [fields])
    - DELETE (MODEL)
    - methods like count(*)
    - RAW QUERY
    */
    private function generateMethodsImpl(DaoClassModel $daoModel, ClassType $classImpl, array $entities)
    {
        
        foreach ($daoModel->methods as $methodMeta) {

            $methodImpl = $classImpl->addMethod($methodMeta->name)
                ->setVisibility('public');

            foreach ($methodMeta->parameters as $param) {
                $methodImpl->addParameter($param->name);
            }

            $methodBodyBuilder = MethodBuilderFactory::getMethodBodyBuilder($methodMeta, $entities);
            $body = $methodBodyBuilder->build();

            $methodImpl->setBody($body);
        }
    }
}
