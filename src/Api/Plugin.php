<?php

namespace Boke0\Mechanism\Api;

class Plugin{
    public function __construct(){
        $this->endpoint=array();
        $this->function=array();
        $this->hook=array();
        $this->menu=array();
    }
    public function parseRef($ref){
        $data=array();
        $l=explode("*",$ref->getDocComment());
        foreach($l as $i=>$s){
            $l[$i]=trim($s);
        }
        $l=array_filter($l,function($s){
            return strlen($s)>0&&$s!="/";
        });
        foreach($l as $i=>$s){
            $t=explode(" ",$s);
            $data[substr($t[0],1)]=$t[1];
        }
        return $data;
    }
    public function endpoint($ep){
        $ep_=array(
            "class"=>$ep,
            "method"=>"any"
        );
        $ref=new \ReflectionClass($ep);
        $ep_=array_merge($ep_,$this->parseRef($ref));
        array_push($this->endpoint,$ep_);
    }
    public function templateFunction($function){
        $funcion_=array(
            "class"=>$function
        );
        $ref=new \ReflectionClass($function_);
        $function_=array_merge($function_,$this->parseRef($ref));
        array_push($this->function,$function_);
    }
    public function menu($menu){
        $menu_=array(
            "class"=>$menu
        );
        $ref=new \ReflectionClass($menu);
        $menu_=array_merge($menu_,$this->parseRef($ref));
        array_push($this->menu,$menu_);
    }
    public function hook($hook){
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
