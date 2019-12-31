<?php

namespace Boke\Mechanism\Api;

class Plugin{
    public function __construct(){
        $this->endpoint=array();
        $this->function=array();
        $this->hook=array();
        $this->menu=array();
    }
    public function endpoint(Endpoint $ep){
        array_push($this->endpoint,$ep);
    }
    public function templateFunction(TemplateFunction $function){
        array_push($this->function,$function);
    }
    public function menu(Menu $menu){
        array_push($this->menu,$menu);
    }
    public function hook(Hook $hook){
        array_push($this->hook,$hook);
    }
    public function getTemplateFunctions(){
        return $this->function;
    }
    public function getEndpoints(){
        return $this->endpoint;
    }
    public function getHooks(){
        return $this->hook;
    }
    public function getAdditionalMenus(){
        return $this->menu;
    }
}
