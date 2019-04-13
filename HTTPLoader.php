<?php
/**
 * Загрузчик HTTP.
 */
class HttpLoader implements LoaderInterface
{
    /**
     * Переменная хранения конфига.
     */
    private $data;

    public function __construct(Configuration $arr)
    {
        $this->data = $arr;
    }

    public function doSomething()
    {
        print_r($this->data);
        echo "HttpLoader getValues";
    }
}
