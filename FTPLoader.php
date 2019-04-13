<?php
/**
 * Загрузчик FTP.
 */
class FtpLoader implements LoaderInterface
{
    /**
     * Переменная хранения конфига.
     */
    private $data;

    public function __construct(Configuration $conf)
    {
        $this->data = $conf;
    }

    public function doSomething()
    {
        print_r($this->data);
        echo "FtpLoader getValues";
    }
}
