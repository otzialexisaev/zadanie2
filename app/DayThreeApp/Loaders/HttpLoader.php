<?php
namespace DayThreeApp\Loaders;

use DayThreeApp\Interfaces\LoaderInterface as LoaderInterface;
use DayThreeApp\Main\Configuration as Configuration;
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
    public function __construct(Configuration $arr)
    {
        $this->data = $arr;
    }


    /**
     * @return mixed|void
     */
    public function doSomething()
    {
        print_r($this->data);
        echo "HttpLoader getValues";
    }
}
