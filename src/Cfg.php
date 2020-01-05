<?php

namespace Boke0\Mechanism;

class Cfg{
    public function get($key){
        return self::fromJSON()[$key];
    }
    public function fromJSON(){
        return json_decode(file_get_contents(__DIR__."/../config.json"),TRUE);
    }
    public function set($key,$value){
        $json=self::fromJSON();
        $json[$key]=$value;
        file_put_contents(__DIR__."/../config.json",json_encode($json));
    }
}
