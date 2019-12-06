<?php

namespace Corm\Builders\Methods;

use Corm\Builders\Methods\IQueryResultBuilder;

use Corm\Models\EntityModel;
use Corm\Exceptions\BadParametersException;


class QueryResultBuilderEntity implements IQueryResultBuilder
{

    /**
     * $var EntityModel
     */
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
    function buildQueryResult()
    {

        $body = "\n\n";
        $body .= '$row = $stm->fetch(\PDO::FETCH_ASSOC);
if (!$row || count($row) == 0) {';
        $body .= "\n\treturn null;\n";
        $body .= '}';

        //$body .= 'print_r($row);';
        
        $body .= "\n\n\$item = new \\" . $this->entity->getFullClassName() . "(\n";

        $paramArray = [];
        foreach ($this->entity->constuctorParams as $parameterName) {


            $entityField = $this->entity->getFieldByName($parameterName);
            if ($entityField == null) {
                throw new BadParametersException("Constructor param '" . $parameterName."' not found");
            }

            if ($entityField->columnName == null || $entityField->columnName == "") {
                throw new BadParametersException("[$this->entity->className] Constructor param '" . $parameterName . "' is not database column");
            }

            $paramArray[] = '$row[\'' . $entityField->columnName . '\']';
        }
        $body .= "\t" . implode(",\n\t", $paramArray);
        $body .= " );";

        
        $body .= "\nreturn \$item;";
        return $body;
    }
}
