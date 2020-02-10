<?php

namespace Boke0\Mechanism\Mdl;

class Menu extends Node{
    public function __construct($path,$converter,$env){
        if(substr($path,-1,1)!="/") $path=substr($path,0,-1);
        $ds=explode("/",$path);
        $menu=array();
        $cpath="";
        foreach($ds as $d){
            $dir=scandir(self::CONTENT_DIR.$cpath);
            if(array_search("__menu.md",$dir)){
                $this->path=$cpath."/__menu.md";
            }
            $cpath.="{$d}/";
        }
        parent::__construct($this->path,$converter,$env);
    }   
    public function __get($k){
        return $this->$k;
    }
}
