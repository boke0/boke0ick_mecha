<?php

namespace Boke0\Mechanism\Mdl;

class Section extends Node{
    public function __construct($path,$struct,$converter,$twig_env){
        parent::__construct($path,$converter,$twig_env);
        $this->struct=$struct;
    }
    public function parent(){
        return new Section(dirname($this->path),$this->struct,$this->converter,$this->twig_env);
    }
    public function children(){
        $dir=scandir(self::CONTENT_DIR.$this->path);
        $children=array();
        foreach($dir as $d){
            if(substr($d,0,1)!="."&&is_dir(self::CONTENT_DIR."$this->path/$d")&&realpath(self::CONTENT_DIR."$this->path/$d")!=realpath(self::CONTENT_DIR.$this->path)){
                $sec=new Section("{$this->path}/$d",$this->struct,$this->converter,$this->twig_env);
                $children[$sec->slug]=$sec;
            }
        }
        return $children;
    }
    public function pages(){
        $dir=scandir(self::CONTENT_DIR.$this->path);
        $children=array();
        foreach($dir as $f){
            if(substr($f,-3)==".md"&&substr($f,0,1)!="."&&is_file(self::CONTENT_DIR."$this->path/$f")&&substr($f,0,2)=="__"){
                $f=substr($f,0,-3);
                $page=new Page("{$this->path}/$f",$this->struct,$this->converter,$this->twig_env);
                $children[$page->slug]=$page;
            }
        }
        return $children;
    }
    public function permalink(){
        return $this->struct->link($this->path);
    }
    public function menu(){
        return (new Menu($this->path,$this->converter,$this->twig_env))->content;
    }
}
