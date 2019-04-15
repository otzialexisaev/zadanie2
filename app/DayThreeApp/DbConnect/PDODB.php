<?php
namespace DayThreeApp\DbConnect;

class PDODB extends DBBase
{
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
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        $this->conn = $conn;
        return $this->conn;
    }

    /**
     * Функция получения всех записей таблицы как массива.
     *
     * Функция получает все записи из таблицы, переданной в функцию,
     * каждую полученную строку поместив в массив, который возвращается из функции.
     */
    public function getTableContentAsArrayAll($table)
    {
        $query = $this->conn->query("SELECT * from " . $table . ";");
        $returnArr = [];
        while ($row = $query->fetch()) {
            array_push($returnArr, $row);
        }
        return $returnArr;
    }

    /**
     * Функция получения одни записи таблицы как массива по id.
     *
     * Функция получает запись из таблицы по столбцу id, переданным в функцию,
     * и помещает ее во вложенный массив и возвращает.
     */
    public function getTableContentAsArrayOne($table, $id)
    {
        echo "SELECT * from " . $this->conn->quote($table) . " WHERE id=" . $id . ";";
        $query = $this->conn->query("SELECT * from " . $this->conn->quote($table) . " WHERE id=" . $id . ";");
        $returnArr = $query->fetch();
        return $returnArr;
    }
}
