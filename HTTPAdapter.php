<?php

class HTTPAdapter extends AdapterBase{
  private $data;
  // public function __construct(string $path){
  //   $this->data = AdapterBase::getConfig($path);
  // }
  public function __construct(array $data){
    $this->data = $data;
  }

  public function setLoader(){
    return new HTTPLoader($this->data);
  }
}

?>