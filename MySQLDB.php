<?php 
  class MysQLDB extends DBBase{
    private $conn;
    private $output;
    /**
     * Функция соединения с базой данных.
     * 
     * Эта функция подключается к базе данных mysql по переданным
     * параметрам, проверяет подключение, присваивает подключение переменной
     * экземпляра $conn. 
     */
    public function connect($db, $user, $password, $dbname){
      $conn = mysqli_connect($db, $user, $password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      } 
      $this->conn = $conn;
      return $conn;
    }

    /**
     * Функция получения всех записей таблицы как массива.
     * 
     * Функция получает все записи из таблицы, переданной в функцию,
     * каждую полученную строку поместив в массив, который возвращается из функции. 
     */
    public function getTableContentAsArrayAll($table){
      $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . ";";
      $output = $this->conn->query($query);
      $returnArr = [];
      while($row = $output->fetch_array()){
        //echo "getTableContentAsArrayAll";
        array_push($returnArr, $row);
        // while($row = $output->fetch_array()){
        //   echo $row['config']."<br>";
        // }
      }
      return $returnArr;
    }

    /**
     * Функция получения одни записи таблицы как массива по id.
     * 
     * Функция получает запись из таблицы по столбцу id, переданным в функцию, 
     * и помещает ее во вложенный массив и возвращает.
     */
    public function getTableContentAsArrayOne($table, $id){
      $query = "SELECT * from " . mysqli_real_escape_string($this->conn, $table) . " WHERE id=". mysqli_real_escape_string($this->conn, $id). ";";
      $output = $this->conn->query($query);
      $returnArr = $output->fetch_array();
      return $returnArr;
    }
  }