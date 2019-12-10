<?php

namespace Corm;

use Corm\Exceptions\ClassNotFoundException;
use Corm\Exceptions\BadParametersException;
use Corm\Models\DaoClassMethodModel;
use Corm\Models\DaoClassModel;
use Corm\Models\DBClassModel;
use phpDocumentor\Reflection\DocBlockFactory;
use Corm\Models\DaoGetter;
use Corm\Models\EntityModel;
use Corm\Utils\DocCommentUtils;
use phpDocumentor\Reflection\Types\Array_;
use Corm\EntitiesParser;
use Corm\Models\MethodParameter;
use phpDocumentor\Reflection\DocBlock;

use ReflectionParameter;

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

        $entitiesNames = $this->parseEntitiesBlock($databaseTag[0]);
        $entitiesNnamespace =  DocCommentUtils::getBlocValue($databaseTag[0], 'namespace');

        $model->entities = $this->parseEntities($entitiesNames, $entitiesNnamespace);

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

        $classRef = new \ReflectionClass($daoClassName);
        //$comment = $dbClassRef->getDocComment();

        $methods = $classRef->getMethods();
        //print_r($methods);

        $factory  = DocBlockFactory::createInstance();

        foreach ($methods as $method) {


            $daoClass->methods[] = $this->parseMethod($method, $factory);
        }


        return $daoClass;
    }

    public function parseEntities(array $entitiesNames, string $entitiesNnamespace)
    {

        $entityParser = new EntitiesParser();
        $entities = [];
        foreach ($entitiesNames as $entityName) {
            $entities[] =  $entityParser->parseEntity($entityName, $entitiesNnamespace);
        }

        return $entities;
    }

    /**
     * @var \ReflectionMethod $method
     * @var DocBlockFactory $factory
     * 
     * @return DaoClassMethodModel
     */
    public function parseMethod(\ReflectionMethod $method, DocBlockFactory $factory)
    {
        $docComment =  $method->getDocComment();
        if ($docComment == null) {
            throw new BadParametersException("Missing docComents");
        }

        $methodModel = new DaoClassMethodModel();
        $methodModel->name =  $method->getName();

        $docblock = $factory->create($docComment);

        $returnInfo = $this->getMethodReturnInfo($docblock);
        $methodModel->isReturnArray = $returnInfo[0];
        $methodModel->returnType = $returnInfo[1];

        $methodModel->query = $this->getMethodQueryAnnotation($docblock);

        $methodModel->parameters = $this->getMethodParameters($method->getParameters(), $docblock);
        
        $methodModel->special = $this->checkIsSpecialMethod($docblock);

        return $methodModel;
    }

    private function getMethodReturnInfo(DocBlock $docBlock)
    {
        $returnTag = $docBlock->getTagsByName('return');
        if ($returnTag == null) {
            return [false, null];
        }

        if ($returnTag[0]->gettype() instanceof Array_) {
            return [true, $returnTag[0]->gettype()->getValueType()->__toString()];
        }

        return [false,  $returnTag[0]->gettype()->__toString()];
    }

    private function getMethodQueryAnnotation(DocBlock $docBlock)
    {
        $query = $docBlock->getTagsByName('query');
        if ($query == null) {
            return null;
        }

        $re = '/\((\s*.*\s*)?\)/m';

        preg_match_all($re, $query[0], $matches, PREG_SET_ORDER, 0);

        // Print the entire match result
        if (count($matches) == 0) {
            throw new BadParametersException("missing query code");
        }

        if (count($matches[0]) < 2) {
            throw new BadParametersException("missing query code ");
        }

        return  $matches[0][1];
    }

    private function checkIsSpecialMethod(DocBlock $docBlock)
    {
        $insert = $docBlock->getTagsByName('insert');
        if ($insert != null) {
            return "insert";
        }

        $update = $docBlock->getTagsByName('update');
        if ($insert != null) {
            return "update";
        }

        $delete = $docBlock->getTagsByName('delete');
        if ($insert != null) {
            return "delete";
        }

        return null;

    }

    private function getMethodParameters($parametersRefl, DocBlock $docBlock)
    {

        $extraInfo = $this->getMethodParamAnnotation($docBlock);
        
        $parameters = [];
        foreach ($parametersRefl as $param) {

            $parameter = new MethodParameter();
            $parameter->name =  $param->name;

            if ($param->isDefaultValueAvailable()) {
                $parameter->defaultValue = $param->getDefaultValue();
            } else {
                $parameter->defaultValue = null;
            }
            $parameter->type = "plain";
            $parameter->isArray = false;
           
            if(array_key_exists($parameter->name, $extraInfo)){
                $extra = $extraInfo[$parameter->name];
                $parameter->type = $extra->type;
                $parameter->isArray =  $extra->isArray;
            }
           
            $parameters[] = $parameter;
        }



        return $parameters;
    }
    
    private function getMethodParamAnnotation(DocBlock $docBlock)
    {
        $parametersDoc = $docBlock->getTagsByName('param');
        if ($parametersDoc == null) {
            return [];
        }
        
        $parameters = [];
        foreach ($parametersDoc as $param) {

            $parameter = new MethodParameter();
            
            $parameter->name = $param->getVariableName();
            if ($param->getType() instanceof \phpDocumentor\Reflection\Types\Array_) {
                $parameter->isArray = true;
                $parameter->type = $param->getType()->getValueType()->__toString();
            } else {
                $parameter->isArray = false;
                $parameter->type = $param->getType()->__toString();
            }

            $parameters[$parameter->name] = $parameter;
        }
        return $parameters;
    }
}
