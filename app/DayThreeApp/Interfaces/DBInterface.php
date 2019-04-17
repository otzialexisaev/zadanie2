<?php

namespace DayThreeApp\Interfaces;

use DayThreeApp\Interfaces\DBInterface as DBInterface;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Main\ConfigurationHistory as ConfigurationHistory;

interface DBInterface
{
    public function connect(string $host, string $user, string $password, string $dbname);
    public function saveConfigToQuery(Configuration $conf);
    public function getFirstQueryRecordAsConfiguration(): ?Configuration;
    public function deleteFirstQueryRecord();
    public function getQueryCount(): ?int;
    public function addConfHistory(ConfigurationHistory $confHistory);
}
