<?php

namespace Boke0\Mechanism\Mdl;

class Page extends Node{
    public function __construct($path,$struct,$converter,$twig_env){
        parent::__construct($path,$converter,$twig_env);
        $this->struct=$struct;
    }
    public function section(){
        return new Section(dirname($this->path),$this->struct,$this->converter,$this->twig_env);
    }
    public function permalink(){
        return $this->struct->link($this->path);
    }
    public function menu(){
        return (new Menu($this->path,$this->converter,$this->twig_env))->content;
    }
}
