<?php
/**
 * Абстрактный класс для адаптеров, содержащий функцию установки заугрузчика setLoader.
 * Реализует интерфейс адаптеров.
 */
abstract class AdapterBase implements AdapterInterface
{
    /**
     * Хранит корневой каталог с конфиг-файлами.
     */
    const CONFIG_PATH = "config";
    
    /**
     * {@inheritdoc}
     *
     * @param string $path
     * @return array
     */
        public static function getConfig(string $path): array
    {
        $pricelistConfig = include self::CONFIG_PATH . "/" . $path;
        return $pricelistConfig;
    }

    /**
     *
     *
     * @return LoaderInterface
     */
    abstract public function setLoader(): LoaderInterface;
}
