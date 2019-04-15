<?php
namespace DayThreeApp\Adapters;

use DayThreeApp\BaseClasses\AdapterBase as AdapterBase;
use DayThreeApp\Interfaces\LoaderInterface as LoaderInterface;
use DayThreeApp\Loaders\FtpLoader as FtpLoader;
use DayThreeApp\Main\Configuration as Configuration;

/**
 * Класс-адаптер для FTP.
 */
class FtpAdapter extends AdapterBase
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
     * Конструктор класса FtpAdapter.
     *
     * Конструктор, который принимает объект Configuration и присваивает его в поле $data.
     *
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf)
    {
        $this->data = $conf;
    }

    /**
     * Возвращает новый загрузчик FtpLoader.
     *
     * В конструктор загрузчика передается объект Configuration.
     *
     * @return LoaderInterface
     */
    public function setLoader(): LoaderInterface
    {
        return new FtpLoader($this->data);
    }
}
