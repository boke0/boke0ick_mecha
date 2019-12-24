<?php

namespace Boke0\Mechanism\Mdl;

class Article extends Mdl{
    public function __construct($converter){
        $this->converter=$converter;
    }
    public function get($path){
        $text=$this->converter->parse(
            file_get_contents(__DIR__."/../../contents/{$path}")
        );
        $data=array();
        array_merge($data,(array)$text->getYAML());
        $data["content"]=$text->getContent();
        return $data;
    }
}
