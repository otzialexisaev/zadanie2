<?php
abstract class ConfigTemp implements ConfigBaseInterface {
    const configPath = "config";
    const distPref = "/dist";
    const localPref = "/local";

    private $distConfValue;
    private $localConfValue;
    private $filePath;
    // private $distConfPath;
    // private $localConfPath;

    // public function getConfigs(string $path){
    //     $this->filePath = $path;
    //     $this->distConfValue = include $this->configPath.$this->distPref."/".$path;
    //     $this->localConfValue = include $this->configPath.$this->localPref."/".$path;

    // }

    public static function getConfig(string $path):array{
        // echo self::configPath.self::distPref."/".$path;
        $distConfValue = include self::configPath.self::distPref."/".$path;
        $localConfValue = include self::configPath.self::localPref."/".$path;
        // echo "<br>";
        // print_r($distConfValue);
        // echo "<br>";
        // print_r($localConfValue);

        return array_replace_recursive($distConfValue, $localConfValue);
    }

    public function getConfigPath(){
        return print_r($this->distConfValue)."<br>".print_r($this->localConfValue);
    }

    public static function swapDist(array $array):bool{
        
    }
}
?>