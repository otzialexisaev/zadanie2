<?php
namespace DayThreeApp\Loaders;

use DayThreeApp\Interfaces\LoaderInterface as LoaderInterface;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Main\ConfigurationHistory as ConfigurationHistory;
/**
 * Загрузчик HTTP.
 */
class HttpLoader implements LoaderInterface
{
    /**
     * Переменная хранения конфига.
     */
    private $data;

    /**
     * Конструктор класса HttpLoader.
     *
     * Принимает объект класса Configuration, значение поля $data которого переносит в свое поле $data.
     *
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf)
    {
        $this->data = $conf;
    }

    /**
     * @return Configuration
     */
    public function getData():Configuration
    {
        return $this->data;
    }

    /**
     * @param Configuration $data
     */
    public function setData(Configuration $data)
    {
        $this->data = $data;
    }

    /**
     * "Переписывает конфиг" и возвращает объект ConfigurationHistory
     * с данными по ошибкам и количеству строк.
     *
     * @return ConfigurationHistory
     */
    public function rewriteConfig(): ConfigurationHistory
    {
        echo "HttpLoader \"переписывает\" конфиг";
        $confHistory = new ConfigurationHistory($this->data);
        if (rand(0, 1) == 1) {
            $confHistory->setErrors(404);
        } else {
            $confHistory->setErrors(0);
        }
        $confHistory->setChangedLines(rand(0, 1000));
        return $confHistory;
    }
}
