<?php

namespace DayThreeApp\Adapters;

use DayThreeApp\Interfaces\DbAdapterInterface as DbAdapterInterface;
use DayThreeApp\DbConnect\MysqlDb as MysqlDb;

class MysqlDbAdapter implements DbAdapterInterface{

    private $config;

    public function __construct(array $config){
        $this->config = $config;
    }

    public function setConnection(){
        $conn = new MysqlDb($this->config);
        // $conn->connect(
        //     $this->config['host'],
        //     $this->config['user'],
        //     $this->config['pass'],
        //     $this->config['db']
        // );
        return $conn;
    }
}