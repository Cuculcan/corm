<?php

namespace Corm;

use Corm\Exceptions\ClassNotFoundException;
use Corm\Exceptions\BadParametersException;
use Corm\Models\DaoClassModel;
use Corm\Models\DBClassModel;
use phpDocumentor\Reflection\DocBlockFactory;
use Corm\Models\DaoGetter;


class Parser
{
    public function parseDatabaseClass($databaseClass): DBClassModel
    {
        $model =  new DBClassModel();
        $model->fullName = $databaseClass;

        $parsedName = $this->parseClassname($databaseClass);
        $model->namespace = $parsedName['namespace'];
        $model->className = $parsedName['class_name'];


        $dbClassRef = new \ReflectionClass($databaseClass);
        $comment = $dbClassRef->getDocComment();
        // echo $comment . "\n";

        $factory  = DocBlockFactory::createInstance();

        $docblock = $factory->create($comment);

        $databaseTag = $docblock->getTagsByName('database');
        if ($databaseTag == null) {
            throw new BadParametersException("Tag @database not found");
        }

        $model->entities = $this->parseEntitiesBlock($databaseTag[0]);
        $model->entities_namespace =  $this->parseEntitiesNamespaceBlock($databaseTag[0]);

        $model->daoInterfaces = $this->parseDaoInterfaces($databaseClass);

        return $model;
    }


    public function parseClassname($dbClass)
    {

        $classNamespaceParts = explode("\\", $dbClass);

        if (count($classNamespaceParts) == 1 && $classNamespaceParts[0] == null) {
            throw new BadParametersException("Class Name not set");
        }

        if ($classNamespaceParts[0] == null || $classNamespaceParts[0] == "") {
            array_shift($classNamespaceParts);
        }
        $className = array_pop($classNamespaceParts);

        return [
            "namespace" => implode("\\", $classNamespaceParts),
            "class_name" => $className,
        ];
    }

    public function parseDaoClassName($daoClass)
    {
        $classInfo = $this->parseClassname($daoClass);
        $ns = $classInfo['namespace'];
        $namespacePars = explode("\\", $ns);
        $daoName = array_pop($namespacePars);

        return [
            "namespace" => implode("\\", $namespacePars),
            "class_name" => $classInfo['class_name'],
            "dao_name" => $daoName
        ];
    }

    private function parseEntitiesBlock($bloc)
    {
        $re = '/entities\s*=\s*{((\s*(.*)\s*)*?)}/m';
        preg_match_all($re, $bloc, $matches, PREG_SET_ORDER, 0);
        if (count($matches) == 0) {
            throw new BadParametersException("missing block \"entities\"");
        }

        if (count($matches[0]) < 2) {
            throw new BadParametersException("missing  entities class list");
        }

        $entitiesStr = trim($matches[0][1]);
        $entities = explode(',', $entitiesStr);

        $filteredEntities = [];
        foreach ($entities as $entity) {
            $entity = trim($entity);
            if ($entity != "") {
                $filteredEntities[] = $entity;
            }
        }

        return $filteredEntities;
    }

    private function parseEntitiesNamespaceBlock($bloc)
    {
        $re = '/namespace\s*=\s*(.*)?[\)|\s]/m';

        preg_match_all($re, $bloc, $matches, PREG_SET_ORDER, 0);

        if (count($matches) == 0) {
            throw new BadParametersException("missing block \"namespace\"");
        }

        if (count($matches[0]) < 2) {
            throw new BadParametersException("missing block \"namespace\" ");
        }

        return trim($matches[0][1]);
    }

    private function parseDaoInterfaces($databaseClass)
    {
        $dbClassRef = new \ReflectionClass($databaseClass);
        $methods = $dbClassRef->getMethods();


        $methods = array_filter($methods, function ($method) use (&$databaseClass) {
            return trim($method->class) . "" == trim($databaseClass) && $method->isAbstract();
        });

        $getDaoMethods = [];
        foreach ($methods as $method) {
            $methodRef = new \ReflectionMethod($databaseClass, $method->name);
            //print_r($methodRef->getReturnType()->getName());

            $methodInfo = new DaoGetter();
            $methodInfo->name = $methodRef->name;

            $returnType = $methodRef->getReturnType();
            if ($returnType == null) {
                throw new BadParametersException($databaseClass . "::" . $methodInfo->name . "() : --  Return Type Missing ");
            }
            $methodInfo->returnType = $returnType->getName();
            $methodInfo->returnTypeInfo = $this->parseDaoClassName($returnType->getName());
            $getDaoMethods[] = $methodInfo;
        }


        return $getDaoMethods;
    }


    public function parseDaoClass($daoClassName)
    {
        $daoClass = new DaoClassModel();

        $classNameInfo = $this->parseDaoClassName($daoClassName);
        $daoClass->fullName = $daoClassName;
        $daoClass->classNameInfo = $classNameInfo;

        return $daoClass;

    }
}
