<?php
/**
 * Интерфейс загрузчиков.
 */
interface LoaderInterface
{
    /**
     * Конструктор класса LoaderInterface.
     *
     * Принимает объект класса Configuration, значение поля $data которого переносит в свое поле $data.
     *
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf);
    /**
     * @return mixed
     */
    public function doSomething();
}
