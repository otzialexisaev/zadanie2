<?php
namespace DayThreeApp\Interfaces;

/**
 * Интерфейс адаптеров.
 */
interface AdapterInterface
{
    /**
     * @param string $path
     * @return array
     */
    public static function getConfig(string $path): array;

    /**
     * @return LoaderInterface
     */
    public function setLoader(): LoaderInterface;
}
