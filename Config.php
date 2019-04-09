<?php

declare(strict_types=1);

/**
 * Class Config
 * Класс-оболочка для класса ConfigTemp, который позволяет изменять полученный слитый массив.
 * Реализует интерфейс ConfigInterface.
 */
class Config extends ConfigTemp implements ConfigInterface{
    /**
     * @var array - переменная для хранения слитого массива, полученный во время работы конструктора.
     */
    private $data;

    /**
     * Config constructor.
     * 
     * Конструктор принимает строку как параметр, запускает метод getConfig,
     * наследуемый из ConfigTemp, передаваяя полученную строку в него как параметр
     * и присваивая полученный массив полю $data.
     * 
     * @param string $path - путь до файла из каталога с конфигами в работе 
     * и пользовательскими конфигами.
     */
    public function __construct(string $path){
        $this->data = $this::getConfig($path);
    }

    /**
     * 
     * Функция отдает массив из переменной $data.
     * 
     * @return array
     */
    public function getData():array{
        return $this->data;
    }

    /**
     * Функция проверки на существование ключа. 
     * 
     * Проверяется существование переданного ключа в хранящемся
     * в $data массиве.
     * 
     * @param $key - ключ
     * @return bool
     */
    public function isValidKey($key):bool{
        return array_key_exists($key, $this->data);
    }

    /**
     * Функция присваивания значения по ключу в $data.
     * 
     * Функция принимает ключ и значение. Сначала идет проверка на существование ключа.
     * Если проверка прошла, то массиву в $data по ключу $key присваивается значение $value.
     * При успешном присвоении возвращает true.
     * 
     * @param $key - ключ
     * @param $value - значение
     * @return bool
     */
    public function set($key, $value){
        if($this->isValidKey($key)){
            $this->data[$key] = $value;
            return true;
        } 
        return false;
    }

    /**
     * Функция получения значения из массива по ключу.
     * 
     * По переданному параметру с помощью isValidKey проверяется существование 
     * ключа. При успешной проверке возвращает значение из массива.
     * 
     * @param $key - ключ для получения значения
     * @return bool|mixed
     */
    public function get($key){
        return $this->isValidKey($key) ? $this->data[$key] : false;
    }
}