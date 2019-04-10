<?php

class FTPAdapter extends AdapterBase{
  private $data;
  // public function __construct(string $path){
  //   $this->data = AdapterBase::getConfig($path);
  // }
  public function __construct(array $data){
    $this->data = $data;
  }

  public function getData(){
    foreach ($this->data as $value) {
      print_r($value);
      echo "<br>". "<br>";
    }
  }

  public function setLoader(){
    return new FTPLoader($this->data);
  }
}

?>