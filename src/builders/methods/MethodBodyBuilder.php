<?php

namespace Corm\Builders\Methods;

use Corm\Builders\Methods\IQueryResultBuilder;
use Corm\Models\DaoClassMethodModel;


class MethodBodyBuilder {
   
   /**
    * @var IQueryResultBuilder
    */
   private $queryResultBuilder = null;

   /**
    * @var DaoClassMethodModel
    */
   private $methodMeta;

   public function __construct($queryResultBuilder, DaoClassMethodModel $methodMeta )
   {
      $this->queryResultBuilder = $queryResultBuilder;
      $this->methodMeta = $methodMeta;
   }

   public function build() : string {

      $body = '$query = \'' . trim($this->methodMeta->query) . '\' ;
$stm = $this->_db->getConnection()->prepare($query);
$stm->execute( ' . (empty($this->methodMeta->parameters) ? '' : $this->printArray($this->methodMeta->parameters)) . ');';

      $body.=  $this->queryResultBuilder->buildQueryResult();
      return $body;

   }

   private function printArray($parameters)
   {

       $values = [];
      
       foreach ($parameters as $_ => $value) {
         $values[] = "\n\t'$value' => \$$value";
       }

       $txt = '[';
       $txt.= implode(",", $values);
       $txt .= "\n]";

       return $txt;
   }
   
 

}