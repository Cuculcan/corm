<?php 

namespace Corm;

use Corm\Exceptions\ClassNotFoundException;
use Corm\Exceptions\BadParametersException;
use Corm\Models\DBClassModel;

class Parser
{
    public function parseDatabaseClass($databaseClass){
        $model =  new DBClassModel();
        $parsedName = $this->parseClassname($databaseClass);
        $model->namespace = $parsedName['namespace'];
        $model->className = $parsedName['class_name'];
        return $model;
    }


    public function parseClassname($dbClass)
    {

        $classNamespaceParts = explode("\\", $dbClass);

        if (count($classNamespaceParts) == 1 && $classNamespaceParts[0] == null) {
            throw new BadParametersException("Не задано имя класса");
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
}