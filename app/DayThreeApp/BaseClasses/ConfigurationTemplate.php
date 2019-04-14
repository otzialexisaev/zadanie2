<?php
namespace DayThreeApp\BaseClasses;

/**
 * Абстрактный класс для классов конфигурации.
 */
abstract class ConfigurationTemplate
{
    abstract public function __construct(array $config);
}
