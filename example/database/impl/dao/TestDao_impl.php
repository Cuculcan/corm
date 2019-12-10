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
}
