<?php
declare(strict_types=1);
/**
 * Загрузчик FTP.
 */
class FTPLoader implements LoaderInterface{
  /**
   * Переменная хранения конфига.
   */
  private $data;

  public function __construct(Configuration $conf){
    $this->data = $conf;
  }

  public function doSomething(){
    print_r($this->data);
    echo "FTPLoader getValues";
  }
}