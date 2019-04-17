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
            $this->conn->query($query);
            echo "<br>Конфигурация добавлена в очередь";
        } catch (\Exception $e) {
            echo "<br>Ошибка добавления конфигурации в очередь" . $e->getMessage();
        }
    }

    /**
     * Получение объекта Configuration из первой записи таблицы очереди с помощью unserialize.
     *
     * @return Configuration|null Null возвращается при неудачном выполнении операции.
     */
    public function getFirstQueryRecordAsConfiguration(): ?Configuration
    {
        $query = "SELECT configuration from " . $this::QUERY_TABLE_NAME . " order by configuration asc limit 1;";
        try {
            $get = $this->conn->query($query);
            $getFetched = $get->fetch();
            $config = unserialize($getFetched['configuration']);
            echo '<br>Запись получена из очереди. ';
            return $config;
        } catch (\Exception $e) {
            echo '<br>Не удалось получить запись из очереди: ' . $e->getMessage();
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
            $this->conn->query($query);
            echo "<br>Конфигурация удалена из очереди<br>";
        } catch (\Exception $e) {
            echo "<br>Ошибка удаления конфигурации из очереди: " . $e->getMessage() . "<br>";
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
            $this->conn->query($query);
            echo "<br>" . "Конфигурация добавлена в историю";
        } catch (\Exception $e) {
            echo "<br>Ошибка добавления конфигурации в историю: " . $e->getMessage();
        }
    }

    /**
     * Возвращает количество записей из очереди.
     *
     * @return int Количество записей
     */
    public function getQueryCount(): ?int
    {
        try {
            $count = $this->conn->query("SELECT COUNT(*) FROM query;");
            $count = $count->fetch();
            return $count[0];
        } catch (\Exception $e) {
            echo "<br>Ошибка получения количества записей в очереди: " . $e->getMessage() . "<br>";
            return null;
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
        $query = "SELECT * from " . $table . ";";

        try {
            $output = $this->conn->query($query);
            $returnArr = [];
            while ($row = $output->fetch()) {
                array_push($returnArr, $row);
            }
            //print_r($returnArr);
            return $returnArr;
        } catch (\PDOException $e) {
            echo '<br>Ошибка получения записей из таблицы ' . $table . ": " . $e->getMessage();
            return null;
        } catch (\Exception $e) {
            echo '<br>Ошибка получения записей из таблицы ' . $table . ": " . $e->getMessage();
            return null;
        }
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

    /**
     * Закрывает подключение к БД.
     */
    public function close()
    {
        $this->conn = null;
    }
}
