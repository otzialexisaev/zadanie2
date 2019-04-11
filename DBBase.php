<?php 
  abstract class DBBase {
    abstract public function connect($host, $user, $password, $dbname);
    abstract public function getTableContentAsArrayAll($table);
    abstract public function getTableContentAsArrayOne($table, $id);

  }
