<?php
/**
 * Интерфейс адаптеров.
 */
interface AdapterInterface
{
    /**
     * Находит конфиг по переданному пути и возвращает его содержимое как массив.
     *
     * @param string $path
     * @return array
     */
    public static function getConfig(string $path):array;


    /**
     * @return LoaderInterface
     */
    public function setLoader():LoaderInterface;
}
