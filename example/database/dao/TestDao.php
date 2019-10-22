<?php

namespace Example\Database\Dao;

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
     * 
     * @return Example\Database\Entities\Model1
     */
    public function getById();



}