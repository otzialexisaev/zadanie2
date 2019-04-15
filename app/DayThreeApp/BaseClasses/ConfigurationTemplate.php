<?php
namespace DayThreeApp\BaseClasses;

/**
 * Абстрактный класс для классов конфигурации.
 */
abstract class ConfigurationTemplate
{
    /**
     * @param array $config
     */
    abstract public function __construct(array $config);
}
