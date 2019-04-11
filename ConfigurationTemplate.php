<?php

/**
 * Абстрактный класс для классов конфигурации.
 */
abstract class ConfigurationTemplate{

  abstract public function __construct(array $config);

}