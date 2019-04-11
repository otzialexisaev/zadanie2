<?php
/**
 * Интерфейс адаптеров.
 */
interface AdapterInterface{
  public static function getConfig(string $path);
	
	public function setLoader();
}