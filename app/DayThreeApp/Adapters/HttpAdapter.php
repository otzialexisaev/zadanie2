<?php
namespace DayThreeApp\Adapters;

use DayThreeApp\BaseClasses\AdapterBase as AdapterBase;
use DayThreeApp\Interfaces\LoaderInterface as LoaderInterface;
use DayThreeApp\Loaders\HttpLoader as HttpLoader;
use DayThreeApp\Main\Configuration as Configuration;

/**
 * Класс-адаптер для HTTP.
 */
class HttpAdapter extends AdapterBase
{
    /**
     * @var Configuration Переменная хранения конфига.
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
     * Конструктор класса HttpAdapter.
     *
     * Конструктор, который принимает объект Configuration и присваивает его в поле $data.
     *
     * HttpAdapter constructor.
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf)
    {
        $this->data = $conf;
    }

    /**
     * Возвращает новый загрузчик HttpLoader.
     *
     * В загрузчик передается объект Configuration.
     *
     * @return LoaderInterface
     */
    public function setLoader(): LoaderInterface
    {
        return new HttpLoader($this->data);
    }
}
