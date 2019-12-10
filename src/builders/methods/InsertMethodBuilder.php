<?php

namespace Corm\Builders\Methods;

use Corm\Builders\Methods\IQueryResultBuilder;
use Corm\Exceptions\BadParametersException;
use Corm\Models\DaoClassMethodModel;
use Corm\Models\EntityModel;
use Corm\Models\MethodParameter;

class InsertMethodBuilder implements IMethodBuilder
{

   /**
    * @var DaoClassMethodModel
    */
   private $methodMeta;


   private $entities;


   public function __construct(DaoClassMethodModel $methodMeta,  array $entities)
   {
      $this->methodMeta = $methodMeta;
      $this->entities = $entities;
   }

   public function build(): string
   {
      if ($this->methodMeta->parameters == null || count($this->methodMeta->parameters) == 0 || count($this->methodMeta->parameters) > 1) {
         throw new BadParametersException("Insert method must have one parameter");
      }
      $parameter = $this->methodMeta->parameters[0];

      $type = trim($parameter->type, "\\");
      if (!array_key_exists($type, $this->entities)) {
         throw new BadParametersException("$type is not entity");
      }

      $entity = $this->entities[$type];

      if ($parameter->isArray) {
         return $this->buildBatchInsert($entity);
      }
      return $this->buildInsert($parameter, $entity);
   }

   private function buildInsert(MethodParameter $parameter, EntityModel  $entity)
   {

      $fieldNames = [];
      $fieldValues = [];
      $insertData = [];

      $body = "";
      foreach ($entity->fields as $field) {
         if ($field->columnName == 'id') {
            continue;
         }
         if ($field->columnName == null) {
            continue;
         }
         $fieldNames[] = "`" . $field->columnName . "`";
         $fieldValues[] = ":" . $field->columnName;
         
         $insertData[] = "\t'$field->columnName' => $". $parameter->name."->".$field->name;
       
      }
     
      $insertIgnore = false;
      $sql = 'INSERT' . ($insertIgnore ? ' IGNORE ' : ' ') . 'INTO ' . $entity->tableName . ' (' . implode(',', $fieldNames) . ') VALUES (' . implode(',', $fieldValues) . ')';

      $body = "\$query = '$sql';\n\n";

      $body .= "\$stm = \$this->_db->getConnection()->prepare(\$query);\n";
      $body .= "\$stm->execute([\n".implode(",\n", $insertData)."\n]);\n";

      $body .= "return \$this->_db->getConnection()->lastInsertId();";

      
      return $body;
   }

   private function buildBatchInsert(EntityModel  $entity)
   {
      return "";
   }
}
