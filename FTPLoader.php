<?php
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
     * @return mixed|void
     */
    public function doSomething()
    {
        print_r($this->data);
        echo "FtpLoader getValues";
    }
}
