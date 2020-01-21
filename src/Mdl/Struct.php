<?php

namespace Boke0\Mechanism\Mdl;
use Boke0\Skull\Router;

class Struct extends Mdl{
    public function __construct(){
        $this->struct=json_decode(
            file_get_contents(__DIR__."/../../contents/struct.json")
        ,TRUE);
        $this->router=new Router();
        $this->linkmaker=new Router();
        foreach($this->struct as $group){
            foreach($group["routes"] as $v){
                if(empty($v["rule"])) $v["rule"]=$v["path"];
                $v["theme"]=$group["theme"];
                $this->router->any($v["path"],$v);
                $this->linkmaker->any($v["rule"],$v);
            }
        }
    }
    public function get($uri){
        $result=$this->router->match($uri,"GET");
        $data=$result["callable"];
        $path=$data["rule"];
        foreach($result["params"] as $k=>$param){
            $path=str_replace(":{$k}",$param,$path);
        }
        return [
            "datafile"=>$path,
            "theme"=>$data["theme"],
            "type"=>$data["type"]
        ];
    }
    public function link($uri){
        $result=$this->linkmaker->match($uri,"GET");
        $data=$result["callable"];
        $path=$data["path"];
        foreach($result["params"] as $k=>$param){
            $path=str_replace(":{$k}",$param,$path);
        }
        return $path;
    }

}
