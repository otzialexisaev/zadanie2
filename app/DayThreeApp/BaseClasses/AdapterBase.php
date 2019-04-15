<?php
namespace DayThreeApp\BaseClasses;

use DayThreeApp\Interfaces\AdapterInterface as AdapterInterface;
use DayThreeApp\Interfaces\LoaderInterface as LoaderInterface;
use DayThreeApp\Main\Configuration as Configuration;

/**
 * Абстрактный класс для адаптеров, содержащий функцию установки заугрузчика setLoader.
 * Реализует интерфейс адаптеров.
 */
abstract class AdapterBase implements AdapterInterface
{
    /**
     * Хранит имя корневого каталога с конфиг-файлами.
     */
    const CONFIG_PATH = "config";

    /**
     * Находит конфиг по переданному пути и возвращает его содержимое как массив объектов Configuration.
     *
     * @param string $path
     * @return array
     */
    public static function getConfig(string $path): array
    {
        $pricelistConfig = include self::CONFIG_PATH . "/" . $path;
        $configurations = [];
        foreach ($pricelistConfig as $config) {
            $config = new Configuration($config);
            \array_push($configurations, $config);
        }
        return $configurations;
    }

    /**
     * @return LoaderInterface
     */
    abstract public function setLoader(): LoaderInterface;
}
