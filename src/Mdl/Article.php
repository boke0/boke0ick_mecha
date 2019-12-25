<?php

namespace Boke0\Mechanism\Mdl;

class Article extends Mdl{
    const CONTENT_DIR=__DIR__."/../../contents/";
    public function __construct($converter){
        $this->converter=$converter;
    }
    public function get($path){
        if(!is_dir(self::CONTENT_DIR.$path)){
            return $this->getMdContent($path);
        }else{
            $data=array();
            $data["section"]=array();
            $dir=scandir(self::CONTENT_DIR.$path);
            foreach($dir as $d){
                if($d[0]==".") continue;
                if(!is_dir(self::CONTENT_DIR."{$path}/{$d}")){
                    $data["section"][$d]=$this->get("{$path}/{$d}");
                }else if($d=="__index.md"){
                    array_merge($data["section"],$this->getMdContent($path));
                }
            }
            return $data;
        }
    }
    public function getMdContent($path){
        $data=array();
        $text=$this->converter->parse(
            file_get_contents(self::CONTENT_DIR)
        );
        array_merge($data,(array)$text->getYAML());
        $data["content"]=$text->getContent();
        return $data;
    }
}
