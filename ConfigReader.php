<?php
declare(strict_types=1);
/**
 * Класс-обертка для работы с конфигами. 
 */
class ConfigReader
{
  /**
   * Возвращает определенный адаптер.
   * 
   * Возвращает нужный класс-адаптер исходя из значения 'source' переданного массива-конфига.
   *
   * @param  array $pricelistConfig
   */
  private static function initAdapter(Configuration $configuration):AdapterBase
  {
    if ($configuration->data['source'] == "ftp") {
      return new FTPAdapter($configuration);
    } else if ($configuration->data['source'] == "http") {
      return new HTTPAdapter($configuration);
    }
  }
  
  /**
   * Функция обновления конфигов.
   * 
   * getConfig возвращает общий файл-конфиг как массив, содержащий массивы с отдельными конфигами.
   * Полученный общий массив перебирается, таким образом каждая итерация опрериует отдельным конфигом.
   * Создается новый адаптер с помощью initAdapter.
   * Адаптер создает новый загрузчик.
   * Закгрузчик вызывает функцию doSomething.
   *
   * @param string $path
   */
  public function updateConfigs(string $path)
  {
    $configurations = AdapterBase::getConfig($path);
    foreach ($configurations as $configuration) {
      $configuration = new Configuration($configuration);
      $adapter = ConfigReader::initAdapter($configuration);
      $loader = $adapter->setLoader();
      $loader->doSomething();
      echo "<hr>";
    }
  }
}
