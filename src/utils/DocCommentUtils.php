<?php

namespace Corm\Utils;

use phpDocumentor\Reflection\DocBlock;
use Corm\Exceptions\BadParametersException;

class DocCommentUtils
{

    public static function getType(DocBlock $docblock)
    {
        $varTag = $docblock->getTagsByName('var');
        if ($varTag == null) {
            throw new BadParametersException("Missing Tag @var ");
        }

        $re = '/\s*.*?\s/m';
        preg_match_all($re, $varTag[0]->__toString(), $matches, PREG_SET_ORDER, 0);

        if (count($matches) == 0) {
            throw new BadParametersException("missing type name from @var tag");
        }

        if (count($matches[0]) < 1) {
            throw new BadParametersException("missing type name from @var tag");
        }

        return  trim(trim($matches[0][0]), "\\");
    }
    public static function checkTagExist(DocBlock $docblock, $tagName)
    {
        $entityTag = $docblock->getTagsByName($tagName);
        if ($entityTag == null) {
            return false;
        }

        return true;
    }

    public static function getTagValueWithkey(DocBlock $docblock, $tagName, $keyName)
    {
        $entityTag = $docblock->getTagsByName($tagName);
        if ($entityTag == null) {
            throw new BadParametersException("Missing Tag @" . $tagName);
        }

        return self::getBlocValue($entityTag[0]->__toString(), $keyName);
    }

    public static function getBlocValue($entityDescr, $key)
    {

        $re = '/' . $key . '\s*=\s*(.*)?[\)|\s]/m';

        preg_match_all($re, $entityDescr, $matches, PREG_SET_ORDER, 0);

        if (count($matches) == 0) {
            throw new BadParametersException("missing block \"" . $key . "\"");
        }

        if (count($matches[0]) < 2) {
            throw new BadParametersException("missing block \"" . $key . "\" ");
        }

        return  trim($matches[0][1]);
    }
}
