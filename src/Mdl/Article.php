<?php

namespace Boke0\Mechanism\Mdl;

class Article extends Mdl{
    const CONTENT_DIR=__DIR__."/../../contents/.";
    public function __construct($converter,$struct){
        $this->converter=$converter;
        $this->struct=$struct;
        $this->loader=array();
    }
    public function get($path){
        $struct=$this->struct->get($path);
        $path=$struct["datafile"];
        if(!is_dir(self::CONTENT_DIR.$path)){
            $dirs=explode("/",$path);
            $dir_path=implode("/",array_slice($dirs,0,count($dirs)-1))."/";
        }else{
            if(substr($path,-1,1)!="/") $path.="/";
            $dir_path=$path;
            $path=$path."__index";
        }
        $data=$this->getMdContent($path.".md");
        $data["permalink"]=$this->struct->link($struct["datafile"]);
        $data["section"]=array();
        $dir=scandir(self::CONTENT_DIR.$dir_path);
        foreach($dir as $d){
            if($d[0]==".") continue;
            if($d=="__menu.md") continue;
            if($d=="struct.json") continue;
            if($d=="__index.md"){
                $data["section"]["index"]=$this->getMdContent("{$dir_path}{$d}");
                $data["section"]["index"]["permalink"]=$this->struct->link($dir_path);
                continue;
            }
            $id=is_dir(self::CONTENT_DIR.$dir_path.$d)?$d:substr($d,0,-3);
            if(!is_dir(self::CONTENT_DIR.$dir_path.$id)){
                $data["section"]["pages"][$id]=$this->getMdContent("{$dir_path}{$d}");
                $data["section"]["pages"][$id]["id"]=$id;
                $data["section"]["pages"][$id]["permalink"]=$this->struct->link("{$dir_path}{$id}");
            }else if($path!=$dir_path.$d){
                if(file_exists(self::CONTENT_DIR."{$dir_path}{$id}/__index.md")){
                    $data["section"]["children"][$id]=$this->getMdContent("{$dir_path}{$id}/__index.md");
                }
                $data["section"]["children"][$id]["id"]=$id;
                $data["section"]["children"][$id]["permalink"]=$this->struct->link("{$dir_path}{$id}");
            }
        }
        return [$data,$this->loader];
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
                    file_get_contents(self::CONTENT_DIR.$cpath."/__menu.md")
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
        $data["content"]=$path;
        $this->loader[$path]=$text->getContent();
        return $data;
    }
}
