<?php
namespace DayThreeApp\BaseClasses;

use DayThreeApp\Interfaces\DBInterface as DBInterface;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Main\ConfigurationHistory as ConfigurationHistory;

abstract class DBBase implements DBInterface
{
    abstract public function connect(string $host, string $user, string $password, string $dbname);
    //abstract public function getTableContentAsArrayAll(string $table): ?array;
    //abstract public function getTableContentAsArrayOne(string $table, int $id): array;
    abstract public function saveConfigToQuery(Configuration $conf);
    abstract public function getFirstQueryRecordAsConfiguration(): ?Configuration;
    abstract public function deleteFirstQueryRecord();
    abstract public function getQueryCount(): int;
    abstract public function addConfHistory(ConfigurationHistory $confHistory);
}
