<?php
namespace DayThreeApp\Adapters;

use DayThreeApp\BaseClasses\AdapterBase as AdapterBase;
use DayThreeApp\Interfaces\LoaderInterface as LoaderInterface;
use DayThreeApp\Main\Configuration as Configuration;
use DayThreeApp\Loaders\FtpLoader as FtpLoader;
/**
 * Класс-адаптер для FTP.
 */
class FtpAdapter extends AdapterBase
{
    /**
     * Переменная хранения конфигурации.
     * 
     * Присваивается в конструкторе и имеет тип Configuration.
     */
    private $data;

    /**
     * Конструктор класса FtpAdapter.
     *
     * Конструктор, который принимает конфиг-массив и присваивает его в поле $data.
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
     * В конструктор загрузчика передается конфиг-массив.
     *
     * @return LoaderInterface
     */
    public function setLoader(): LoaderInterface
    {
        return new FtpLoader($this->data);
    }
}
