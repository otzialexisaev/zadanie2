<?php

namespace DayThreeApp\Main;

use DayThreeApp\Interfaces\DBInterface as DBInterface;

class DbInteract
{
    private $conn;
    /**
     * Имя таблицы-очереди.
     */
    const QUERY_TABLE_NAME = "query";
    /**
     * Имя таблицы-истории.
     */
    const HISTORY_TABLE_NAME = "history";

    public function __construct(DbInterface $conn)
    {
        $this->conn = $conn;
        $this->conn->connect();
    }

    /**
     * Записывает объект ConfigurationHistory в таблицу-историю.
     */
    public function addConfHistory(ConfigurationHistory $config)
    {
        try {
            $config = $this->configHistoryToMysqlArray($config);
            $this->conn->insert($this::HISTORY_TABLE_NAME, $config);
            echo "<br>Конфигурация добавлена в историю";
        } catch (\Exception $e) {
            echo "<br>Ошибка добавления конфигурации в историю: " . $e->getMessage();
        }
    }

    /**
     * Преобразует объект ConfigurationHistory в массив, с ключом, который будет считан базовым
     * классом БД как столбец для занесения данных. В данном случае configuration.
     */
    private function configHistoryToMysqlArray(ConfigurationHistory $config): array
    {
        $arr = [
            "configuration" => serialize($config->getData()),
            "changed_lines" => $config->getChangedLines(),
            "errors" => $config->getErrors()
        ];
        return $arr;
    }

    /**
     * Преобразует объект Configuration в массив, с ключом, который будет считан базовым
     * классом БД как столбец для занесения данных. В данном случае configuration.
     */
    private function configToMysqlArray(Configuration $config): array
    {
        $arr = [
            "configuration" => serialize($config),
        ];
        return $arr;
    }

    /**
     * Возвращает количество записей в таблицу-очередь.
     */
    public function getQueryCount()
    {
        return $this->conn->getTableCount($this::QUERY_TABLE_NAME);
    }

    /**
     * Записывает конфигурации из массива по одной в таблицу-очередь.
     */
    public function setConfigsQuery(array $configs)
    {
        foreach ($configs as $config) {
            $this->saveConfigToQuery($config);
        }
    }
    
    /**
     * Сохраняет объект Configuration в таблицу-очередь.
     */
    public function deleteFirstQueryRecord()
    {
        //print_r($config);
        $this->conn->deleteFirstRecord($this::QUERY_TABLE_NAME);
        echo "Конфигураця была удалена из очереди<br>";
    }

    /**
     * Сохраняет объект Configuration в таблицу-очередь.
     */
    public function saveConfigToQuery($config)
    {
        $config = $this->configToMysqlArray($config);
        //print_r($config);
        $this->conn->insert($this::QUERY_TABLE_NAME, $config);
        echo "Конфигураця была добавлена в очередь<br>";
    }

    /**
     * Получает первую запись из таблицы query и возвращает ее как объект Configuration.
     */
    public function getFirstQueryRecordAsConfiguration()
    {
        $record = $this->conn->getFirstTableRecordAsArray($this::QUERY_TABLE_NAME);
        echo "<hr>";
        print_r($record['configuration']);
        $config = unserialize($record['configuration']);
        return $config;
    }

    
    // /**
    //  * Закрывает подключение к БД.
    //  */
    public function closeConnection()
    {
        $this->conn->close();
    }

    // public function getTableContentAsArrayAll(string $table): ?array
    // {
    //     $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . ";";
    //     try {
    //         $output = $this->conn->query($query);
    //         $returnArr = [];
    //         while ($row = $output->fetch_array()) {
    //             array_push($returnArr, $row);
    //         }
    //         return $returnArr;
    //     } catch (\Exception $e) {
    //         echo '<br>Ошибка получения записей из таблицы ' . $table . ": " . $e->getMessage();
    //         return null;
    //     }
    // }
}
