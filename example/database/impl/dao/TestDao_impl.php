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
                    $command = $this->_db->createCommand($query, []);

                    $result = $command->queryAll();
                    if (!$result || count($result) == 0) {
                        return [];
                    }
                    $data = [];
                    foreach ($result as $row) {            $item = new Example\Database\Entities\Model1( $row[id], $row[name], $row[value], $row[],);
                $data[] = $item;

                    }
                    return null;
    }

    public function getById()
    {
        $query = 'SELECT * FROM model_1 where id = :id' ;
                    $command = $this->_db->createCommand($query, []);

                    $result = $command->queryAll();
                    if (!$result || count($result) == 0) {
                        return [];
                    }
                    $data = [];
                    foreach ($result as $row) {            $item = new Example\Database\Entities\Model1( $row[id], $row[name], $row[value], $row[],);
                $data[] = $item;

                    }
                    return null;
    }
}
