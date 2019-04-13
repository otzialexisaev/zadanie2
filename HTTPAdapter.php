<?php
/**
 * Класс-адаптер для HTTP.
 */
class HttpAdapter extends AdapterBase
{
    /**
     * Переменная хранения конфига.
     */
    private $data;
    // public function __construct(string $path){
    //   $this->data = AdapterBase::getConfig($path);
    // }

    /**
     * Конструктор класса HttpAdapter.
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
     * Возвращает новый загрузчик HttpLoader.
     *
     * В загрузчик передается конфиг-массив.
     */
    public function setLoader(): LoaderInterface
    {
        return new HttpLoader($this->data);
    }
}
