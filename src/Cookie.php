<?php

namespace Boke0\Mechanism;

class Cookie{
    static public function get($k){
        return $_COOKIE[$k];
    }
    static public function set($k,$v,$e=time(),$p="/"){
        setcookie($k,$v,$e,$p);
    }
    static public function delete($k){
        setcookie($k,NULL,0);
    }
}
