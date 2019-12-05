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
use Corm\Models\FieldModel;
use Corm\Utils\DocCommentUtils;
use phpDocumentor\Reflection\Types\Array_;

class EntitiesParser
{

    public function parseEntity($className, $namespace)
    {
        $entity =  new EntityModel();
        $entity->className = $className;
        $entity->namespace = $namespace;

        $fullName = $namespace . "\\" . $className;

        if (!class_exists($fullName)) {
            throw new BadParametersException("class " . $fullName . " not found");
        }

        $classRef = new \ReflectionClass($fullName);
        $entity->tableName = $this->getTableName($classRef);
        $entity->fields = $this->getFields($classRef);
        $entity->constuctorParams = $this->getConstructorParams($classRef);
        return $entity;
    }

    private function getTableName(\ReflectionClass $classRef)
    {
        $comment = $classRef->getDocComment();

        $factory  = DocBlockFactory::createInstance();
        $docblock = $factory->create($comment);

        $tableName = DocCommentUtils::getTagValueWithkey($docblock, 'entity', 'table_name');
        return $tableName;
    }

    private function getFields(\ReflectionClass $classRef)
    {
        $properties = $classRef->getProperties();
        $fields = [];
        foreach ($properties as $prop) {
            $fields[] = $this->getFieldInfo($prop);
        }
        return $fields;
    }



    private function getFieldInfo(\ReflectionProperty $refl): FieldModel
    {

        $model =  new FieldModel();
        $model->name = $refl->getName();

        $comment = $refl->getDocComment();
        $factory  = DocBlockFactory::createInstance();
        if ($comment == null) {
            return $model;
        }
        $docblock = $factory->create($comment);

        $columnName = null;
        try {
            $columnName = DocCommentUtils::getTagValueWithkey($docblock, 'column_info', 'name');
        } catch (BadParametersException $ex) { }
        $model->columnName = $columnName;

        $type = null;
        try {
            $type =  DocCommentUtils::getType($docblock);
        } catch (BadParametersException $ex) { }
        $model->type = $type;

        return $model;
    }


    private function getConstructorParams(\ReflectionClass $classRef)
    {
        $constructor = $classRef->getConstructor();
        if ($constructor == null) {
            throw new BadParametersException("Не задан конструктор у объекта $classRef->name");
        }

        $parameters = [];
        $constructorParameters = $constructor->getParameters();
        foreach ($constructorParameters as $param) {
            $parameters[] = $param->name;
        }
        
        return $parameters;
    }
}
