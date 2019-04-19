<?php

namespace DayThreeApp\DbConnect;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
// use DayThreeApp\BaseClasses\DBBase as DBBase;
use DayThreeApp\Interfaces\DBInterface as DBInterface;

class MySqlDb implements DbInterface
{
    private $config;
    private $conn;

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

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        
        $conn = \mysqli_connect($this->config['host'],
            $this->config['user'],
            $this->config['pass'],
            $this->config['db']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $this->conn = $conn;
        return $this->conn;
    }

    public function getFirstTableRecordAsArray(string $table):array
    {
        $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table)
            . " order by id asc limit 1;";
        echo $query;
        try {
            $get = $this->conn->query($query);
            //print_r($get);
            //echo "<hr>";
            $getFetched = $get->fetch_array();
            //print_r($getFetched);
            return $getFetched;
        } catch (\Exception $e) {
            echo '<br>Не удалось получить запись из очереди: ' . $e->getMessage();
            return null;
        }
    }

    public function deleteFirstRecord(string $table) //Проверить какую именно строку удаляет
    {
        $query = "DELETE from " . $table . " order by id asc limit 1;";
        try {
            $this->conn->query($query);
            echo "<br>Конфигурация удалена из очереди<br>";
        } catch (\Exception $e) {
            echo "<br>Ошибка удаления конфигурации из очереди: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Возвращает количество записей в таблице.
     */
    public function getTableCount(string $table): ?int
    {
        try {
            $count = $this->conn->query("SELECT COUNT(*) FROM " . $table);
            $count = $count->fetch_array();
            return $count[0];
        } catch (\Exception $e) {
            echo "<br>Ошибка получения количества записей в очереди: " . $e->getMessage() . "<br>";
            return null;
        }
    }

    /**
     * Записывает данные из переданного массива в указанную таблицу.
     */
    public function insert(string $table, array $array)
    {
        $keys = $this->arrayKeysToString($array);
        $values = $this->arrayValsToString($array);
        // echo "<br>Ключи<br>";
        // print_r($keys);
        // echo "<br>Pyfxtybz<br>";
        // print_r($values);
        //echo "<br>".$values;
        // $query = "INSERT INTO " . \mysqli_real_escape_string($this->conn, $table)
        //         . "(" . \mysqli_real_escape_string($this->conn, $keys) . ") VALUES ('"
        //         . \mysqli_real_escape_string($this->conn, $values) . "');";

        $query = "INSERT INTO " . \mysqli_real_escape_string($this->conn, $table)
            . "(";
        // foreach ($keys as $key) {
        //     $query .= \mysqli_real_escape_string($this->conn, $key);
        // }
        for ($i = 0; $i < sizeof($keys) - 1; $i++) {
            $query .= \mysqli_real_escape_string($this->conn, $keys[$i]) . ",";
        }
        $query .= \mysqli_real_escape_string($this->conn, $keys[sizeof($keys) - 1]);

        $query .= ") VALUES (";
        // foreach ($values as $value) {
        //     $query .= "'".\mysqli_real_escape_string($this->conn, $value)."',";
        // }
        for ($i = 0; $i < sizeof($values) - 1; $i++) {
            $query .= "'" . \mysqli_real_escape_string($this->conn, $values[$i]) . "',";
        }
        $query .= "'" . \mysqli_real_escape_string($this->conn, $values[sizeof($values) - 1]) . "'";

        $query .= ");";
        // echo $query;
        try {
            $this->conn->query($query);
        } catch (\Exeption $e) {
            echo "asd";
            echo $e->getMessage();
        }
    }

    /**
     * Возвращает значения массива одной строкой через запятую.
     */
    private function arrayValsToString($array)
    {
        $string = "";
        $arrayVals = array_values($array);
        //print_r(array_keys($array));
        for ($index = 0; $index < sizeof($arrayVals) - 1; $index++) {
            $string .= \mysqli_real_escape_string($this->conn, $arrayVals[$index]) . ", ";
        }
        $string .= $arrayVals[sizeof($arrayVals) - 1];
        //echo $string;
        return $arrayVals;
    }

    /**
     * Возвращает ключи массива одной строкой через запятую.
     */
    private function arrayKeysToString($array)
    {
        $string = "";
        $arrayKeys = array_keys($array);
        //print_r(array_keys($array));
        for ($index = 0; $index < sizeof($arrayKeys) - 1; $index++) {
            $string .= $arrayKeys[$index] . ", ";
        }
        $string .= $arrayKeys[sizeof($arrayKeys) - 1];
        //echo $string;
        return $arrayKeys;
    }

    // /**
    //  * Закрывает подключение к БД.
    //  */
    public function close()
    {
        $this->conn->close();
    }
}
    // public function connect(string $host, string $user, string $password, string $dbname)
    // {
    //     $conn = mysqli_connect($host, $user, $password, $dbname);
    //     if ($conn->connect_error) {
    //         die("Connection failed: " . $conn->connect_error);
    //     }
    //     $this->conn = $conn;
    //     return $this->conn;
    // }

/**
 * новый(старый) getFirstRecordAsArray
 */
    // public function getFirstRecordAsArray(string $table):?array{
    //     $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . " order by configuration asc limit 1;";
    //     try {
    //         $get = $this->conn->query($query);
    //         //print_r($get);
    //         //echo "<hr>";
    //         $getFetched = $get->fetch_array();
    //         return $getFetched;
    //     } catch (\Exception $e) {
    //         echo '<br>Не удалось получить запись из очереди: ' . $e->getMessage();
    //         return null;
    //     }
    // }

    // /**
    //  * Функция получения всех записей таблицы как массива.
    //  *
    //  * Функция получает все записи из таблицы, переданной в функцию,
    //  * каждую полученную строку поместив в массив, который возвращается из функции.
    //  *
    //  * @param string $table
    //  * @return array
    //  */
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

    // /**
    //  * Сохранение объекта Configuration в таблицу очереди с помощью serialize.
    //  *
    //  * @param Configuration $conf
    //  */
    // public function saveConfigToQuery(Configuration $conf)
    // {
    //     $query = "INSERT INTO " . mysqli_real_escape_string($this->conn, $this::QUERY_TABLE_NAME) . " (configuration) VALUES ('" . mysqli_real_escape_string($this->conn, serialize($conf)) . "');";

    //     try {
    //         $this->conn->query($query);
    //         echo "<br>Конфигурация добавлена в очередь";
    //     } catch (\Exception $e) {
    //         echo "<br>Ошибка добавления конфигурации в очередь" . $e->getMessage();
    //     }
    // }

    // /**
    //  * Получение объекта Configuration из первой записи таблицы очереди с помощью unserialize.
    //  *
    //  * @return Configuration|null
    //  */
    // public function getFirstQueryRecordAsConfiguration(): ?Configuration
    // {
    //     $query = "SELECT configuration from " . $this::QUERY_TABLE_NAME . " order by configuration asc limit 1;";
    //     try {
    //         $get = $this->conn->query($query);
    //         //print_r($get);
    //         //echo "<hr>";
    //         $getFetched = $get->fetch_array();
    //         //print_r($getFetched);
    //         //echo "<hr>";
    //         $config = unserialize($getFetched['configuration']);
    //         return $config;
    //     } catch (\Exception $e) {
    //         echo '<br>Не удалось получить запись из очереди: ' . $e->getMessage();
    //         return null;
    //     }
    // }

    // /**
    //  * Удаляет первую запись из таблицы query_table
    //  */
    // public function deleteFirstQueryRecord()
    // {
    //     $query = "DELETE from " . $this::QUERY_TABLE_NAME . " LIMIT 1;";
    //     try {
    //         $this->conn->query($query);
    //         echo "<br>Конфигурация удалена из очереди<br>";
    //     } catch (\Exception $e) {
    //         echo "<br>Ошибка удаления конфигурации из очереди: " . $e->getMessage() . "<br>";
    //     }
    // }

    // /**
    //  * Добавляет объект ConfigurationHistory в таблицу истории.
    //  *
    //  * @param ConfigurationHistory $confHistory
    //  */
    // public function addConfHistory(ConfigurationHistory $confHistory)
    // {
    //     $query = "INSERT INTO " . mysqli_real_escape_string($this->conn, $this::HISTORY_TABLE_NAME)
    //     . " (configuration, changed_lines, errors) VALUES ('" .
    //     mysqli_real_escape_string($this->conn, serialize($confHistory->getData())) . "','" .
    //     mysqli_real_escape_string($this->conn, $confHistory->getChangedLines()) . "','" .
    //     mysqli_real_escape_string($this->conn, $confHistory->getErrors()) . "');";
    //     try {
    //         $this->conn->query($query);
    //         echo "<br>Конфигурация добавлена в историю";
    //     } catch (\Exception $e) {
    //         echo "<br>Ошибка добавления конфигурации в историю: " . $e->getMessage();
    //     }
    // }

    // /**
    //  * Возвращает количество записей из очереди.
    //  *
    //  * @return int Количество записей
    //  */
    // public function getQueryCount(): ?int
    // {
    //     try {
    //         $count = $this->conn->query("SELECT COUNT(*) FROM query;");
    //         $count = $count->fetch_array();
    //         return $count[0];
    //     } catch (\Exception $e) {
    //         echo "<br>Ошибка получения количества записей в очереди: " . $e->getMessage() . "<br>";
    //         return null;
    //     }
    // }

    // /**
    //  * Функция получения одни записи таблицы как массива по id.
    //  *
    //  * Функция получает запись из таблицы по столбцу id, переданным в функцию,
    //  * и помещает ее во вложенный массив и возвращает.
    //  *
    //  * @param string $table
    //  * @param int $id
    //  * @return array
    //  */
    // public function getTableContentAsArrayOne(string $table, int $id): array
    // {
    //     try {
    //         $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . " WHERE id=" . mysqli_real_escape_string($this->conn, $id) . ";";
    //         if ($output = $this->conn->query($query)) {
    //             $returnArr = $output->fetch_array();
    //             return $returnArr;
    //         } else {
    //             throw new \Exception('Ошибка получения записи.');
    //         }
    //     } catch (\Exception $e) {
    //         echo "<br>" . $e->getMessage() . "<br>";
    //     }
    // }

