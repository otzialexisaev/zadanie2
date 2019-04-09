<?php

/**
 * Interface ConfigInterface. Интерфейс для оболочки класса ConfigTemp.
 * 
 * Интерфейс содержит функции для проверки существования ключей в массиве,
 * получения массива, смены значения в массиве по ключу, получение значения из массива по ключу.
 */
interface ConfigInterface{
    /**
     * Функция для проверки существования ключей в массиве.
     * 
     * @param $key - ключ для проверки
     * @return bool
     */
    public function isValidKey($key):bool;

    /**
     * Функция получения массива.
     * 
     * @return array
     */
    public function getData():array;

    /**
     * Функция смены значения в массиве по ключу.
     * 
     * @param $key - ключ для смены значения.
     * @param $value - значение.
     * @return mixed
     */
    public function set($key, $value);

    /**
     * Функция получение значения из массива по ключу.
     * 
     * @param $key - ключ.
     * @return mixed
     */
    public function get($key);
}