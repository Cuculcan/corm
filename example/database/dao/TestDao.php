<?php

namespace Example\Database\Dao;
use Example\Database\Entities\Model1;

interface TestDao {


    /**
     * @query(
     *   SELECT * FROM model_1
     * )
     * 
     * @return Example\Database\Entities\Model1[]
     */
    public function getAll();


    /**
     * @query(
     *   SELECT * FROM model_1 where id = :id
     * )
     * @param int $id
     * @param string $ololo
     * 
     * @return Example\Database\Entities\Model1
     */
    public function getById($id, $ololo);


    /**
     * @insert
     * 
     * @param Example\Database\Entities\Model1[] $models
     */
    public function insert(array $models);

}