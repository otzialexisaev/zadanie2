<?php

interface ConfigInterface{
    public function getData():array;
    public function isValidKey($key):bool;

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