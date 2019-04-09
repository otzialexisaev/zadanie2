<?php
class Config extends ConfigTemp{

    private $data;

    // public function setData(array $data){
    //     //$this->data = $data;
    // }

    public function setData(string $path){
        $this->data = $this::getConfig($path);
    }

    public function getData():array{
        return $this->data;
    }

    public function isValidKey($key){
        return array_key_exists($key, $this->data);
    }

    public function set($key, $value){
        if($this->isValidKey($key)){
            $this->data[$key] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function get($key){
        return $this->isValidKey($key) ? $this->data[$key] : false;
    }
}