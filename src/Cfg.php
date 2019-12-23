<?php

namespace Boke0\Mechanism;

class Cfg{
    public function get($key){
        return $this->fromJSON()->$key;
    }
    private function fromJSON(){
        return json_decode(file_get_contents(__DIR__."/../config.json"));
    }
}
