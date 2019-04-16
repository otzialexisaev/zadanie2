<?php
namespace DayThreeApp\DbConnect;

use DayThreeApp\BaseClasses\DBBase as DBBase;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Main\ConfigurationHistory as ConfigurationHistory;

class MysQLDB extends DBBase
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
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $dbname
     * @return \mysqli
     */
    public function connect(string $host, string $user, string $password, string $dbname)
    {
        $conn = mysqli_connect($host, $user, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $this->conn = $conn;
        return $this->conn;
    }

    /**
     * Функция получения всех записей таблицы как массива.
     *
     * Функция получает все записи из таблицы, переданной в функцию,
     * каждую полученную строку поместив в массив, который возвращается из функции.
     *
     * @param string $table
     * @return array
     */
    public function getTableContentAsArrayAll(string $table): ?array
    {
        $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . ";";

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
    }

    /**
     * Сохранение объекта Configuration в таблицу очереди с помощью serialize.
     *
     * @param Configuration $conf
     */
    public function saveConfigToQuery(Configuration $conf)
    {
        $query = "INSERT INTO " . mysqli_real_escape_string($this->conn, $this::QUERY_TABLE_NAME) . " (configuration) VALUES ('" . mysqli_real_escape_string($this->conn, serialize($conf)) . "');";

        try {
            if ($this->conn->query($query)) {
                echo "Конфигурация добавлена в очередь" . "<br>";
            } else {
                throw new \Exception("Ошибка добавления конфигурации в очередь");
            }
        } catch (\Exception $e) {
            $e->getMessage();
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
                $getFetched = $get->fetch_array();
                //print_r($getFetched['configuration']);
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
        $query = "INSERT INTO " . mysqli_real_escape_string($this->conn, $this::HISTORY_TABLE_NAME)
        . " (configuration, changed_lines, errors) VALUES ('" .
        mysqli_real_escape_string($this->conn, serialize($confHistory->getData())) . "','" .
        mysqli_real_escape_string($this->conn, $confHistory->getChangedLines()) . "','" .
        mysqli_real_escape_string($this->conn, $confHistory->getErrors()) . "');";
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
                $count = $count->fetch_array();
                return $count[0];
            } else {
                throw new \Exception('Ошибка получения количества записей в очереди.');
            }

        } catch (\Exception $e) {
            echo "<br>" . $e->getMessage() . "<br>";
        }
    }

    /**
     * Функция получения одни записи таблицы как массива по id.
     *
     * Функция получает запись из таблицы по столбцу id, переданным в функцию,
     * и помещает ее во вложенный массив и возвращает.
     *
     * @param string $table
     * @param int $id
     * @return array
     */
    public function getTableContentAsArrayOne(string $table, int $id): array
    {
        try {
            $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . " WHERE id=" . mysqli_real_escape_string($this->conn, $id) . ";";
            if ($output = $this->conn->query($query)) {
                $returnArr = $output->fetch_array();
                return $returnArr;
            } else {
                throw new \Exception('Ошибка получения записи.');
            }
        } catch (\Exception $e) {
            echo "<br>" . $e->getMessage() . "<br>";
        }
    }
    
    /**
     * Закрывает подключение к БД.
     */
    public function close()
    {
        $this->conn->close();
    }
}
