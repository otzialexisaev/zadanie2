<?php

class HTTPLoader implements LoaderInterface{

  private $data;

  public function __construct(array $arr){
    $this->data = $arr;
  }

  public function doSomething(){
    print_r($this->data);
    echo "HTTPLoader getValues";
  }
}