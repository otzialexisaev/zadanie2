<?php 
declare(strict_types=1);
/**
 * Класс представляющий один конфиг.
 */
class Configuration extends ConfigurationTemplate{
  
  public $data;

  /**
   * Конструктор, который принимает конфиг в виде массива и присваивает его полю $data.
   *
   * @param  array $config
   */
  public function __construct(array $config){
    $this->data = $config;
  }
}