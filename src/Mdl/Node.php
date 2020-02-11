<?php

namespace Boke0\Mechanism\Mdl;

class Node{
    const CONTENT_DIR=__DIR__."/../../contents/";
    public function __construct($path,$converter,$env){
        $this->converter=$converter;
        $this->path=$path;
        $this->twig_env=$env;
        $data=$this->getMdContent($path);
        foreach($data as $k=>$v){
            $this->$k=$v;
        }
    }
    public function getMdContent($path){
        $data=array();
        if(is_dir(self::CONTENT_DIR.$path)){
            $path.="/__index.md";
        }else{
            $path.=".md";
        }
        $text=$this->converter->parse(
            file_get_contents(realpath(self::CONTENT_DIR.$path))
        );
        $data["datetime"]=filemtime(realpath(self::CONTENT_DIR.$path));
        $data+=(array)$text->getYAML();
        $data["content"]=$this->twig_env->createTemplate($text->getContent());
        return $data;
    }
}

