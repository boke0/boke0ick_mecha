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
                if($d=="__menu.md") continue;
                if(substr($path,-1,1)!="/") $path.="/";
                if($d=="__index.md"){
                    $data+=$this->getMdContent("{$path}{$d}");
                }else{
                    $data["section"][$d]=$this->get("{$path}{$d}");
                    $data["section"][$d]["id"]=$d;
                }
            }
            return $data;
        }
    }
    public function getMenu($path){
        if(substr($path,-1,1)=="/") $path=substr($path,0,strlen($path)-1);
        $ds=explode("/",$path);
        $menu=array();
        $cpath="";
        foreach($ds as $d){
            $dir=scandir(self::CONTENT_DIR.$cpath);
            if(array_search("__menu.md",$dir)){
                $text=$this->converter->parse(
                    file_get_contents(self::CONTENT_DIR.$cpath."__menu.md")
                );
                $menu=$text->getContent();
            }
            $cpath.="{$d}/";
        }
        return $menu;
    }
    public function getMdContent($path){
        $data=array();
        $text=$this->converter->parse(
            file_get_contents(self::CONTENT_DIR.$path)
        );
        $data+=(array)$text->getYAML();
        $data["content"]=$text->getContent();
        return $data;
    }
}
