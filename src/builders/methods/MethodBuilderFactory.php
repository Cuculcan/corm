<?php

namespace Corm\Builders\Methods;

use Corm\Builders\Methods\IQueryResultBuilder;
use Corm\Builders\Methods\MethodBodyBuilder;
use Corm\Builders\Methods\QueryResultBuilderEntitiesArray;
use Corm\Builders\Methods\QueryResultBuilderEntity;
use Corm\Exceptions\BadParametersException;
use Corm\Models\DaoClassMethodModel;

class MethodBuilderFactory
{

    const UNDEFINED = -1;
    const VOID = 0;
    const ENTYTY = 1;
    const ENTYTY_ARRAY = 2;
    const PLAIN = 3;
    const PLAIN_ARRAY = 4;

    public static function getMethodBodyBuilder(DaoClassMethodModel $methodMeta,  array $entities)
    {

        $resultType = self::UNDEFINED;
        $returnType = trim($methodMeta->returnType, "\\");
        $returnEntity = null;

        if ($returnType == null || $returnType == "") {
            $resultType = self::VOID;
        } else if (array_key_exists($returnType, $entities)) {
            $returnEntity = $entities[$returnType];
            $resultType = ($methodMeta->isReturnArray) ? self::ENTYTY_ARRAY : self::ENTYTY;
        } else {
            $resultType = ($methodMeta->isReturnArray) ? self::PLAIN : self::PLAIN_ARRAY;
        }

        $queryResultBuilder = null;
        switch ($resultType) {
            case self::VOID:
                break;
            case self::ENTYTY:
                $queryResultBuilder = new QueryResultBuilderEntity($returnEntity);
                break;
            case self::ENTYTY_ARRAY:
                $queryResultBuilder = new QueryResultBuilderEntitiesArray($returnEntity);
                break;
            case self::PLAIN:
                break;
            case self::PLAIN_ARRAY:
                break;
            default:
                throw new BadParametersException("$methodMeta->name undefined return type");
        }

        return new MethodBodyBuilder($queryResultBuilder, $methodMeta);
    }
}
