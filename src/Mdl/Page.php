<?php

namespace Boke0\Mechanism\Mdl;

class Page extends Node{
    public function __construct($path,$struct,$converter,$env){
        parent::__construct($path,$converter,$env);
        $this->struct=$struct;
    }
    public function section(){
        return new Section(dirname($this->path),$this->struct,$this->converter,$this->env);
    }
    public function permalink(){
        return $this->struct->link($this->path);
    }
    public function menu(){
        return new Menu($this->path,$this->struct,$this->converter,$this->env);
    }
}
