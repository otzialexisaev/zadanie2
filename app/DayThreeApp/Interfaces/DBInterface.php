<?php

namespace DayThreeApp\Interfaces;

use DayThreeApp\Interfaces\DBInterface as DBInterface;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Main\ConfigurationHistory as ConfigurationHistory;

interface DbInterface
{
    // public function connect(string $host, string $user, string $password, string $dbname);
    
    public function insert(string $table, array $array);    
    public function connect();
    public function fetchAll(string $table): ?array;
    public function fetchFirst(string $table): array;
    
    
    
    
    
    
    // public function connect();
    // public function arrayToTable(array $arr, string $table);
    // public function getFirstRecordAsArray(string $table):?array;






    // public function saveConfigToQuery(Configuration $conf);
    // public function getFirstQueryRecordAsConfiguration(): ?Configuration;
    // public function deleteFirstQueryRecord();
    // public function getQueryCount(): ?int;
    // public function addConfHistory(ConfigurationHistory $confHistory);
}
