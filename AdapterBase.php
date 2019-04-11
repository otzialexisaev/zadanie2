<?php
declare(strict_types=1);
/**
 * Абстрактный класс для адаптеров, содержащий функцию установки заугрузчика setLoader.
 * Реализует интерфейс адаптеров.
 */
abstract class AdapterBase implements AdapterInterface{

  const configPath = "config";
	/**
   * Возвращает общий файл-конфиг как массив, содержащий массивы с отдельными конфигами.
   */
  public static function getConfig(string $path):array{
    $pricelistConfig = include self::configPath."/".$path;
    return $pricelistConfig;
	}
	
	abstract function setLoader():LoaderInterface;
}