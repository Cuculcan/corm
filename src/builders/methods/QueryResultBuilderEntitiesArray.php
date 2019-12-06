<?php

namespace Corm\Builders\Methods;

use Corm\Builders\Methods\IQueryResultBuilder;

use Corm\Models\EntityModel;
use Corm\Exceptions\BadParametersException;

class QueryResultBuilderEntitiesArray implements IQueryResultBuilder
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

        $body = "\n\n\$data = [];\n";
        $body .="while (\$row = \$stm->fetch(\PDO::FETCH_ASSOC)) {";
        $body .= "\n\t\$item = new \\" . $this->entity->getFullClassName() . "(\n";

        $paramArray = [];
        foreach ($this->entity->constuctorParams as $parameterName) {


            $entityField = $this->entity->getFieldByName($parameterName);
            if ($entityField == null) {
                throw new BadParametersException("Constructor param '" . $parameterName."' not found");
            }
            //print_r($entityField);
            if ($entityField->columnName == null || $entityField->columnName == "") {
                throw new BadParametersException("[$this->entity->className] Constructor param '" . $parameterName . "' is not database column");
            }

            $paramArray[] = '$row[\'' . $entityField->columnName . '\']';
        }
        $body .= "\t\t" . implode(",\n\t\t", $paramArray);
        $body .= " );
    \$data[] = \$item;
        ";

        $body .="\n}\n";
        $body .="return \$data;";
        return $body;
    }
}
