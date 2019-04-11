<?php
declare(strict_types=1);

/**
 * Загрузчик HTTP.
 */
class HTTPLoader implements LoaderInterface{

  /**
   * Переменная хранения конфига.
   */
  private $data;

  public function __construct(Configuration $arr){
    $this->data = $arr;
  }

  public function doSomething(){
    print_r($this->data);
    echo "HTTPLoader getValues";
  }
}