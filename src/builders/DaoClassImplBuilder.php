<?php

namespace Corm\Builders;

use Corm\Exceptions\BadParametersException;
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
            ->addComment('@var \PDO');

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
            // print_r($methodMeta);

            $methodImpl = $classImpl->addMethod($methodMeta->name)
                ->setVisibility('public');

            if ($methodMeta->query == null || $methodMeta->query == "") {
                $methodImpl->setBody('return null;');
                continue;
            }

            $body = '$query = \'' . trim($methodMeta->query) . '\' ;
$stm = $this->_db->prepare($query);
$stm->execute( ' . (empty($methodMeta->parameters) ? '' : $this->printArray($methodMeta->parameters)) . ');';

            $resultBuilder  = null;

            $returnType = trim($methodMeta->returnType, "\\");
            if (array_key_exists($returnType, $entities)) {

                $returnType = $entities[$returnType];

                if ($methodMeta->isReturnArray) {
                    $resultBuilder = new QueryResultBuilderEntitiesArray();
                } else {
                    $resultBuilder = new QueryResultBuilderEntity();
                }
            };




            //             $body .= '$result = $stm->fetch(\PDO::FETCH_ASSOC);
            // if (!$result || count($result) == 0) {';

            //             if ($methodMeta->isReturnArray) {
            //                 $body .= "\n\treturn [];\n";
            //             } else {
            //                 $body .= "\n\treturn null;\n";
            //             }

            //             $body .= '}
            // $data = [];
            // foreach ($result as $row) {            ';



            //             //var_dump($returnType);
            //             if ($returnType instanceof EntityModel) {
            //                 $body .= $this->generateQueryResultArray($returnType);
            //                 $body .= '
            // }
            // return $data;
            // ';
            //             }



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

        $body = "\n\t\$item = new \\" . $returnType->getFullClassName() . "(\n";

        $paramArray = [];
        foreach ($returnType->constuctorParams as $parameterName) {


            $entityField = $returnType->getFieldByName($parameterName);
            if ($entityField == null) {
                throw new BadParametersException("Не найден параметр конструктора " . $parameterName);
            }
            //print_r($entityField);
            if ($entityField->columnName == null || $entityField->columnName == "") {
                throw new BadParametersException("[$returnType->className] Constructor param '" . $parameterName . "' is not database column");
            }

            $paramArray[] = '$row[\'' . $entityField->columnName . '\']';
        }
        $body .= "\t\t" . implode(",\n\t\t", $paramArray);
        $body .= " );
    \$data[] = \$item;
        ";
        return $body;
    }
}
