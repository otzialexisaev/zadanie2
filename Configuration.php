<?php
/**
 * Класс представляющий один конфиг.
 */
class Configuration extends ConfigurationTemplate
{
    /**
     * @var array Переменная для хранения массива, полученного из конфиг-файла.
     */
    private $data;

    /**
     * Возвращает значение поля $data.
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Конструктор, который принимает конфиг в виде массива и присваивает его полю $data.
     *
     * @param  array $config
     */
    public function __construct(array $config)
    {
        $this->data = $config;
    }
}
