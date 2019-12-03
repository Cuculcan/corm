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

    private function generateMethodsImpl(DaoClassModel $daoModel, ClassType $classImpl, array $entities)
    {
        foreach ($daoModel->methods as $methodMeta) {
            print_r($methodMeta);

            $methodImpl = $classImpl->addMethod($methodMeta->name)
                ->setVisibility('public');

            if ($methodMeta->query == null || $methodMeta->query == "") {
                $methodImpl->setBody('return null;');
                continue;
            }

            $returnType = trim($methodMeta->returnType, '\\');
            if (array_key_exists($returnType, $entities)) {
                $returnType = $entities[$returnType];
            }


            $body = ' $query = \'' . trim($methodMeta->query) . '\' ;
            $command = $this->_db->createCommand($query, ' . $this->printArray($methodMeta->parameters) . ');

            $result = $command->queryAll();
            if (!$result || count($result) == 0) {
                return [];
            }
            $data = [];
            foreach ($result as $row) {            ';

            if ($returnType instanceof EntityModel) {
                $body .= $this->generateQueryResultArray($returnType);
            }
            $body .= '
            }
            return null;
            ';

            $methodImpl->setBody($body);
        }
    }
    private function printArray($parameters)
    {
        $txt = '[';
        foreach ($parameters as $key => $value) {
            $txt .= "\n'$key'=>'$value'\n";
        }
        $txt .= ']';

        return $txt;
    }

    private function generateQueryResultArray(EntityModel $returnType)
    {

        $body = '$item = new ' . $returnType->getFullClassName() . '(';
        foreach ($returnType->fields as $field) {
            $body .= ' $row[' . $field->columnName . '],';
        }
        $body .= ');
        $data[] = $item;
        ';
        return $body;
    }
}
