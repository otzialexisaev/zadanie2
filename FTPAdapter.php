<?php
/**
 * Класс-адаптер для FTP.
 */
class FtpAdapter extends AdapterBase
{
    /**
     * Переменная хранения конфига.
     */
    private $data;
    // public function __construct(string $path){
    //   $this->data = AdapterBase::getConfig($path);
    // }

    /**
     * Конструктор, оторый принимает конфиг-массив и присваивает его в поле $data.
     */
    public function __construct(Configuration $conf)
    {
        $this->data = $conf;
    }

    /**
     * Возвращает новый загрузчик FtpLoader.
     *
     * В загрузчик передается конфиг-массив.
     */
    public function setLoader(): LoaderInterface
    {
        return new FtpLoader($this->data);
    }
}
