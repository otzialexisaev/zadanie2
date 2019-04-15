<?php
namespace DayThreeApp\Main;

use DayThreeApp\BaseClasses\ConfigurationTemplate as ConfigurationTemplate;
use DayThreeApp\Main\Configuration as Configuration;
/**
 * Класс представляющий один конфиг.
 */
class ConfigurationHistory
{
    /**
     * @var array Переменная для хранения массива, полученного из конфиг-файла.
     */
    private $data;
    private $changed_lines;
    private $errors;

    /**
     * Возвращает значение поля $data.
     * 
     * @return array
     */
    public function getData():Configuration
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getChangedLines():int
    {
        return $this->changed_lines;
    }

    /**
     * @param mixed $changed_lines
     */
    public function setChangedLines(int $changed_lines)
    {
        $this->changed_lines = $changed_lines;
    }

    /**
     * @return mixed
     */
    public function getErrors():int
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors(int $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Конструктор, который принимает конфиг в виде массива и присваивает его полю $data.
     *
     * @param \DayThreeApp\Main\Configuration $config
     */
        public function __construct(Configuration $config)
    {
        $this->data = $config;
    }
}
