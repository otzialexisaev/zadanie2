<?php

declare(strict_types=1);

/**
 * Class ConfigTemp. 
 * Класс-основа для получения конфиг-файлов по пути и слияния их.
 * Реализует интерфейс ConfigBaseInterface.
 */
abstract class ConfigTemp implements ConfigBaseInterface {

    /**
     * Константа, хранящяя путь к корневому каталогу с конфигами.
     */
    const configPath = "config";
    /**
     * Константа, хранящяя путь до каталога с конфигами в работе, хранящийся в корневом каталоге.
     */
    const distPref = "/dist";
    /**
     * Константа, хранящяя путь до каталога с пользовательскими конфигами, хранящийся в корневом каталоге.
     */
    const localPref = "/local";

    /**
     * Функция состоавяляет полный путь из констант и переданного пути, 
     * получает два конфиг-файла, хранящихся в виде массива, 
     * и сливает их с array_replace_recursive.
     * 
     * @param string $path - переданный путь, который должен соответствовать пути до конфигов из директорий->configPath.*Pref.
     * @return array - возвращаемый слитый из двух массив.
     */
    public static function getConfig(string $path):array{
        $distConfValue = include self::configPath.self::distPref."/".$path;
        $localConfValue = include self::configPath.self::localPref."/".$path;

        return array_replace_recursive($distConfValue, $localConfValue);
    }
}
?>