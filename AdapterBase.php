<?php

abstract class AdapterBase{

	const configPath = "config";
	
  public static function getConfig(string $path):array{
    $pricelistConfig = include self::configPath."/".$path;
    return $pricelistConfig;
	}
	
	abstract function setLoader();
}

/*
Генератор который проходит по строкам конфига
*/