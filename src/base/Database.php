<?php 

namespace Corm\Base;

abstract class Database {

    protected $db;

    public function __construct($connection_params)
    {
        $host = $connection_params['host'];
        $dbname = $connection_params['dbname'];
        $user = $connection_params['user'];
        $pass = $connection_params['password'];

        $this->db = new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }

    public function getConnection()
    {
        return $this->db;
    }
}