<?php

/**
 * This file is auto-generated.
 */

namespace Example\Database\Impl\Dao;

use Corm\Base\CormDatabase;

class TestDao_impl implements \Example\Database\Dao\TestDao
{
    /** @var \PDO */
    private $_db;

    public function __construct(CormDatabase $db)
    {
        $this->_db = $db;
    }

    public function getAll()
    {
        $query = 'SELECT * FROM model_1' ;
        $stm = $this->_db->prepare($query);
        $stm->execute( );$result = $stm->fetch(\PDO::FETCH_ASSOC);
        if (!$result || count($result) == 0) {
        	return [];
        }
        $data = [];
        foreach ($result as $row) {
        	$item = new \Example\Database\Entities\Model1(
        		$row['id'],
        		$row['name'],
        		$row['value'] );
            $data[] = $item;

        }
        return $data;
    }

    public function getById()
    {
        $query = 'SELECT * FROM model_1 where id = :id' ;
        $stm = $this->_db->prepare($query);
        $stm->execute( );$result = $stm->fetch(\PDO::FETCH_ASSOC);
        if (!$result || count($result) == 0) {
        	return null;
        }
        $data = [];
        foreach ($result as $row) {
        	$item = new \Example\Database\Entities\Model1(
        		$row['id'],
        		$row['name'],
        		$row['value'] );
            $data[] = $item;

        }
        return $data;
    }
}
