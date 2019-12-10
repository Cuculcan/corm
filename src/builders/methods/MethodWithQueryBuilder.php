<?php

namespace Corm\Builders\Methods;

use Corm\Builders\Methods\IQueryResultBuilder;
use Corm\Models\DaoClassMethodModel;

use Corm\Models\MethodParameter;

class MethodWithQueryBuilder implements IMethodBuilder
{

   /**
    * @var IQueryResultBuilder
    */
   private $queryResultBuilder = null;

   /**
    * @var DaoClassMethodModel
    */
   private $methodMeta;

   public function __construct($queryResultBuilder, DaoClassMethodModel $methodMeta)
   {
      $this->queryResultBuilder = $queryResultBuilder;
      $this->methodMeta = $methodMeta;
   }

   public function build(): string
   {

      $body = '$query = \'' . trim($this->methodMeta->query) . '\' ;
$stm = $this->_db->getConnection()->prepare($query);
$stm->execute( ' . (empty($this->methodMeta->parameters) ? '' : $this->printArray($this->methodMeta->parameters)) . ');';

      $body .=  $this->queryResultBuilder->buildQueryResult();
      return $body;
   }

   /**
    * @param MethodParameter[] $parameters
    */
   private function printArray($parameters)
   {

      $values = [];

      foreach ($parameters as $parameter) {


         $values[] = "\n\t'$parameter->name' => \$$parameter->name";
      }

      $txt = '[';
      $txt .= implode(",", $values);
      $txt .= "\n]";

      return $txt;
   }
}
