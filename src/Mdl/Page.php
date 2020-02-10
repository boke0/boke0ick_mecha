<?php

namespace Boke0\Mechanism\Mdl;

class Page extends Node{
    public function __construct($path,$struct,$converter,$env){
        parent::__construct($path,$converter,$env);
        $this->struct=$struct;
    }
    public function __get($k){
        switch($k){
            case "section":
                return new Section(dirname($this->path),$this->struct,$this->converter,$this->env);
            case "permalink":
                return $this->struct->link($this->path);
            case "menu":
                return new Menu($this->path,$this->struct,$this->converter,$this->env);
            default:
                return $this->$k;
        }
    }
}
