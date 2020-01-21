<?php

namespace Boke0\Mechanism;

class Cookie{
    static public function get($k){
        return $_COOKIE[$k];
    }
    static public function set($k,$v,$e=NULL,$p="/"){
        if($e==NULL) $e=time();
        setcookie($k,$v,$e,$p);
        $_COOKIE[$k]=$v;
    }
    static public function delete($k,$p="/"){
        setcookie($k,NULL,0,$p);
        unset($_COOKIE[$k]);
    }
}
