<?php

/**
 * This file is auto-generated.
 */

namespace Example\Database\Impl\Dao;

use Corm\Base\CormDatabase;

class TestDao_impl implements \Example\Database\Dao\TestDao
{
    /** @var CormDatabase */
    private $_db;

    public function __construct(CormDatabase $db)
    {
        $this->_db = $db;
    }

    public function getAll()
    {
        $query = 'SELECT * FROM model_1' ;
        $stm = $this->_db->getConnection()->prepare($query);
        $stm->execute( );

        $data = [];
        while ($row = $stm->fetch(\PDO::FETCH_ASSOC)) {
        	$item = new \Example\Database\Entities\Model1(
        		$row['id'],
        		$row['name'],
        		$row['value'] );
            $data[] = $item;

        }
        return $data;
    }

    /**
     * @param int $id
     */
    public function getById($id)
    {
        $query = 'SELECT * FROM model_1 where id = :id' ;
        $stm = $this->_db->getConnection()->prepare($query);
        $stm->execute( [
        	'id' => $id
        ]);

        $row = $stm->fetch(\PDO::FETCH_ASSOC);
        if (!$row || count($row) == 0) {
        	return null;
        }

        $item = new \Example\Database\Entities\Model1(
        	$row['id'],
        	$row['name'],
        	$row['value'] );
        return $item;
    }

    /**
     * @param int[] $ids
     */
    public function getByIds($ids)
    {
        $query = 'SELECT * FROM model_1 where id IN (:ids)' ;
        $stm = $this->_db->getConnection()->prepare($query);
        $stm->execute( [
        	'ids' => $ids
        ]);

        $row = $stm->fetch(\PDO::FETCH_ASSOC);
        if (!$row || count($row) == 0) {
        	return null;
        }

        $item = new \Example\Database\Entities\Model1(
        	$row['id'],
        	$row['name'],
        	$row['value'] );
        return $item;
    }

    /**
     * @param \Example\Database\Entities\Model1 $model
     */
    public function insert($model)
    {
        $query = 'INSERT INTO model_1 (`name`,`value`) VALUES (:name,:value)';

        $stm = $this->_db->getConnection()->prepare($query);
        $stm->execute([
        	'name' => $model->name,
        	'value' => $model->value
        ]);
        return $this->_db->getConnection()->lastInsertId();
    }

    /**
     * @param \Example\Database\Entities\Model1[] $models
     */
    public function insertBatch($models)
    {
        $dataToInsert = [];
        $valuesPlaceholder = [];
        foreach( $models as $entity) {
        	$values = [];
        	$dataToInsert[] = $entity->name;
        	$values[] = '?';
        	$dataToInsert[] = $entity->value;
        	$values[] = '?';
        	$valuesPlaceholder[] = '(' . implode(',',$values) . ')';
         }

        $query = 'INSERT INTO model_1 (`name`,`value`) VALUES ';
        $query .=  implode(',', $valuesPlaceholder);

        echo $query;

        $stm = $this->_db->getConnection()->prepare($query);
        $stm->execute($dataToInsert);

        return $this->_db->getConnection()->lastInsertId();
    }
}
