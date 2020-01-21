<?php

namespace Boke0\Mechanism\Api;

class Plugin{
    public function __construct(){
        $this->endpoint=array();
        $this->extension=array();
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
    public function templateExtension($ext){
        $ext_=array(
            "class"=>$ext
        );
        $ref=new \ReflectionClass($ext);
        $function_=array_merge($ext_,$this->parseRef($ref));
        array_push($this->extension,$ext_);
    }
    public function getTemplateExtensions(){
        return $this->extension;
    }
    public function getEndpoints(){
        return $this->endpoint;
    }
}
