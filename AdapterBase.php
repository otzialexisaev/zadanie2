<?php
/**
 * Абстрактный класс для адаптеров, содержащий функцию установки заугрузчика setLoader.
 * Реализует интерфейс адаптеров.
 */
abstract class AdapterBase implements AdapterInterface
{
    const CONFIG_PATH = "config";
    
    /**
     * Возвращает общий файл-конфиг как массив, содержащий массивы с отдельными конфигами.
     */
    public static function getConfig(string $path): array
    {
        $pricelistConfig = include self::CONFIG_PATH . "/" . $path;
        return $pricelistConfig;
    }

    abstract public function setLoader(): LoaderInterface;
}
