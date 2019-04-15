<?php
namespace DayThreeApp\Loaders;

use DayThreeApp\Interfaces\LoaderInterface as LoaderInterface;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Main\ConfigurationHistory as ConfigurationHistory;

/**
 * Загрузчик FTP.
 */
class FtpLoader implements LoaderInterface
{
    /**
     * Переменная хранения конфигурации.
     *
     * Присваивается в конструкторе и имеет тип Configuration.
     *
     * @var Configuration
     */
    private $data;

    /**
     * Конструктор класса FtpLoader.
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
    public function getData(): Configuration
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
        echo "FtpLoader \"переписывает\" конфиг";
        $confHistory = new ConfigurationHistory($this->data);
        $confHistory->setErrors(rand(0, 5));
        $confHistory->setChangedLines(rand(0, 1000));
        return $confHistory;
    }
}
