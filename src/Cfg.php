<?php

namespace Boke0\Mechanism;

class Cfg{
    public function get($key){
        return self::fromJSON()->$key;
    }
    public function fromJSON(){
        return json_decode(file_get_contents(__DIR__."/../config.json"));
    }
}
