<?php
declare(strict_types=1);

/**
 * Класс-адаптер для HTTP.
 */
class HTTPAdapter extends AdapterBase{
  /**
   * Переменная хранения конфига.
   */
  private $data;
  // public function __construct(string $path){
  //   $this->data = AdapterBase::getConfig($path);
  // }

  /**
   * Конструктор, оторый принимает конфиг-массив и присваивает его в поле $data.
   */
  public function __construct(Configuration $data){
    $this->data = $data;
  }

  /**
   * Возвращает новый загрузчик HTTPLoader.
   * 
   * В загрузчик передается конфиг-массив.
   */
  public function setLoader():LoaderInterface{
    return new HTTPLoader($this->data);
  }
}

?>