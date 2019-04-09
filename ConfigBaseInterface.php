<?php
/**
 * Interface ConfigBaseInterface
 * 
 * Содержит базовую функцию для класса работы с конфиг-файлами.
 */
interface ConfigBaseInterface{
    /**
     * Функция для получения значений конфиг файлов из переданного пути
     * и возвращения их слияния.
     * 
     * @param string $path
     * @return mixed
     */
    public static function getConfig(string $path);
}