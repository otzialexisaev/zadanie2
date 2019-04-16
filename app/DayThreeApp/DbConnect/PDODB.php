<?php
namespace DayThreeApp\DbConnect;

use DayThreeApp\BaseClasses\DBBase as DBBase;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Main\ConfigurationHistory as ConfigurationHistory;

class PDODB extends DBBase
{
    const QUERY_TABLE_NAME = "query";
    const HISTORY_TABLE_NAME = "history";
    private $conn;
    private $output;
    /**
     * Функция соединения с базой данных.
     *
     * Эта функция подключается к базе данных по переданным
     * параметрам, проверяет подключение, присваивает подключение переменной
     * экземпляра $conn.
     */
    public function connect($host, $user, $password, $dbname)
    {
        try {
            $conn = new \PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        $this->conn = $conn;
        return $this->conn;
    }

    /**
     * Сохранение объекта Configuration в таблицу очереди с помощью serialize.
     *
     * @param Configuration $conf
     */
    public function saveConfigToQuery(Configuration $conf)
    {
        $query = "INSERT INTO " . $this::QUERY_TABLE_NAME . " (configuration) VALUES (" . $this->conn->quote(serialize($conf)) . ");";
        try {
            if ($this->conn->query($query)) {
                echo "Конфигурация добавлена в очередь" . "<br>";
            } else {
                throw new \Exception("Ошибка добавления конфигурации в очередь");
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Получение объекта Configuration из первой записи таблицы очереди с помощью unserialize.
     *
     * @return Configuration|null
     */
    public function getFirstQueryRecordAsConfiguration(): ?Configuration
    {
        $query = "SELECT configuration from " . $this::QUERY_TABLE_NAME . " order by configuration asc limit 1;";
        try {
            if ($get = $this->conn->query($query)) {
                $getFetched = $get->fetch();
                $config = unserialize($getFetched['configuration']);
                return $config;
            } else {
                throw new \Exception('Не удалось получить запись из очереди.');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Удаляет первую запись из таблицы query_table
     */
    public function deleteFirstQueryRecord()
    {
        $query = "DELETE from " . $this::QUERY_TABLE_NAME . " LIMIT 1;";

        try {
            if ($this->conn->query($query)) {
                echo "<br>Конфигурация удалена из очереди<br>";
            } else {
                throw new \Exception('Ошибка удаления конфигурации из очереди.');
            }
        } catch (\Exception $e) {
            echo "<br>" . $e->getMessage() . "<br>";
        }
    }

    /**
     * Добавляет объект ConfigurationHistory в таблицу истории.
     *
     * @param ConfigurationHistory $confHistory
     */
    public function addConfHistory(ConfigurationHistory $confHistory)
    {
        $query = "INSERT INTO " . $this::HISTORY_TABLE_NAME
        . " (configuration, changed_lines, errors) VALUES (" .
        $this->conn->quote(serialize($confHistory->getData())) . "," .
        $this->conn->quote($confHistory->getChangedLines()) . "," .
        $this->conn->quote($confHistory->getErrors()) . ");";
        try {
            if ($this->conn->query($query)) {
                echo "<br>" . "Конфигурация добавлена в историю" . "<br>";
            } else {
                throw new \Exception('Ошибка добавления конфигурации в историю.');
            }

        } catch (\Exception $e) {
            echo "<br>" . $e->getMessage() . "<br>";
        }
    }

    /**
     * Возвращает количество записей из очереди.
     *
     * @return int Количество записей
     */
    public function getQueryCount(): int
    {
        try {
            if ($count = $this->conn->query("SELECT COUNT(*) FROM query;")) {
                $count = $count->fetch();
                return $count[0];
            } else {
                throw new \Exception('Ошибка получения количества записей в очереди.');
            }
        } catch (\Exception $e) {
            echo "<br>" . $e->getMessage() . "<br>";
        }
    }

    /**
     * Функция получения всех записей таблицы как массива.
     *
     * Функция получает все записи из таблицы, переданной в функцию,
     * каждую полученную строку поместив в массив, который возвращается из функции.
     */
    public function getTableContentAsArrayAll($table): ?array
    {
        $query = "SELECT * from " . $this->conn->quote($table) . ";";
        try {
            if ($output = $this->conn->query($query)) {
                $returnArr = [];
                while ($row = $output->fetch_array()) {
                    array_push($returnArr, $row);
                }
                return $returnArr;
            } else {
                throw new \Exception("Ошибка получения записей из табоицы " . $table);
            }
        } catch (\Exception $e) {
            $e->getMessage();
            return null;
        }
        // $returnArr = [];
        // while ($row = $query->fetch()) {
        //     array_push($returnArr, $row);
        // }
        // return $returnArr;
    }

    /**
     * Функция получения одни записи таблицы как массива по id.
     *
     * Функция получает запись из таблицы по столбцу id, переданным в функцию,
     * и помещает ее во вложенный массив и возвращает.
     */
    public function getTableContentAsArrayOne($table, $id): array
    {
        echo "SELECT * from " . $this->conn->quote($table) . " WHERE id=" . $id . ";";
        $query = $this->conn->query("SELECT * from " . $this->conn->quote($table) . " WHERE id=" . $id . ";");
        $returnArr = $query->fetch();
        return $returnArr;
    }
}
