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
     * 
     * @return Example\Database\Entities\Model1
     */
    public function getById($id);


    //  /**
    //  * @query(
    //  *   SELECT * FROM model_1 where id IN (:ids)
    //  * )
    //  * @param int[] $ids
    //  * 
    //  * @return Example\Database\Entities\Model1
    //  */
    // public function getByIds($ids);


    /**
     * @insert
     * 
     * @param Example\Database\Entities\Model1 $model
     */
    public function insert($model);


    // /**
    //  * @insert
    //  * 
    //  * @param Example\Database\Entities\Model1[] $models
    //  */
    // public function insertBatch($models);

}