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
    public function getTableContentAsArrayAll(string $table): array
    {
        $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . ";";
        $output = $this->conn->query($query);
        $returnArr = [];
        while ($row = $output->fetch_array()) {
            //echo "getTableContentAsArrayAll";
            array_push($returnArr, $row);
            // while($row = $output->fetch_array()){
            //   echo $row['config']."<br>";
            // }
        }
        return $returnArr;
    }

    /**
     * Сохранение объекта Configuration в таблицу очереди с помощью serialize.
     * 
     * @param Configuration $conf
     */
    public function saveConfigToQuery(Configuration $conf)
    {
        $query = "INSERT INTO " . mysqli_real_escape_string($this->conn, $this::QUERY_TABLE_NAME) . " (configuration) VALUES (\"" . mysqli_real_escape_string($this->conn, serialize($conf)) . "\");";
        if ($this->conn->query($query)) {
            echo "Конфиг добавлен в очередь" . "<br>";
        } else {
            echo $this->conn->error;
            echo "Конфиг НЕ добавлен в очередь" . "<br>";
        }
    }

    /**
     * Получение объекта Configuration из первой записи таблицы очереди с помощью unserialize.
     * 
     * @return Configuration
     */
    public function getFirstQueryRecordAsConfiguration(): Configuration
    {
        $query = "SELECT configuration from " . $this::QUERY_TABLE_NAME . " order by configuration asc limit 1;";
        if ($this->conn->query($query)) {
            $get = $this->conn->query($query);
            $getFetched = $get->fetch_array();
            $config = unserialize($getFetched['configuration']);
        }
        return $config;
    }

    /**
     * Удаляет первую запись из таблицы query_table
     */
    public function deleteFirstQueryRecord()
    {
        $query = "DELETE from " . $this::QUERY_TABLE_NAME . " LIMIT 1;";
        if ($this->conn->query($query)) {
            echo "<br>Конфиг удален из очереди<br>";
        } else {
            echo $this->conn->error;
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
        . " (configuration, changed_lines, errors) VALUES (\"" .
        mysqli_real_escape_string($this->conn, serialize($confHistory->getData())) . "\",\"" . 
        mysqli_real_escape_string($this->conn, $confHistory->getChangedLines()) . "\",\"" . 
        mysqli_real_escape_string($this->conn, $confHistory->getErrors()) . "\");";
        if ($this->conn->query($query)) {
            echo "Конфиг добавлен в историю" . "<br>";
        } else {
            echo $this->conn->error . "<br>";
            echo "Конфиг НЕ добавлен в историю" . "<br>";
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
        $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . " WHERE id=" . mysqli_real_escape_string($this->conn, $id) . ";";
        $output = $this->conn->query($query);
        $returnArr = $output->fetch_array();
        return $returnArr;
    }
}
