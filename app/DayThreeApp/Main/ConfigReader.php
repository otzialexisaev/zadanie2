<?php
namespace DayThreeApp\Main;

use DayThreeApp\Adapters\FtpAdapter as FtpAdapter;
use DayThreeApp\Adapters\HttpAdapter as HttpAdapter;
use DayThreeApp\BaseClasses\AdapterBase as AdapterBase;

/**
 * Класс-обертка для работы с конфигами.
 */
class ConfigReader
{
    /**
     * Возвращает определенный адаптер.
     *
     * Возвращает нужный класс-адаптер исходя из значения массива по ключу 'source' из полученного объекта класса Configuration.
     *
     * @param Configuration $configuration
     * @return AdapterBase
     */
    private static function initAdapter(Configuration $configuration): AdapterBase
    {
        if ($configuration->getData()['source'] == "ftp") {
            return new FtpAdapter($configuration);
        } elseif ($configuration->getData()['source'] == "http") {
            return new HttpAdapter($configuration);
        }
    }

    public function setConfigsQuery()
    {
        
    }

    /**
     * Функция обновления конфигов.
     *
     * AdapterBase::getConfig возвращает общий файл-конфиг как массив, содержащий массивы с отдельными конфигами.
     * Полученный общий массив перебирается, таким образом каждая итерация опрериует отдельным конфигом.
     * Создается новый адаптер с помощью initAdapter.
     * Адаптер создает новый загрузчик.
     * Закгрузчик вызывает функцию doSomething.
     *
     * @param string $path Путь до нужного конфиг-файла, начиная с директории /config.
     */
    public function updateConfigs(string $path)
    {
        $configurations = AdapterBase::getConfig($path);
        foreach ($configurations as $configuration) {
            $configuration = new Configuration($configuration);
            $adapter = $this::initAdapter($configuration);
            $loader = $adapter->setLoader();
            $loader->doSomething();
            echo "<hr>";
        }
    }
}
