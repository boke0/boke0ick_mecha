<?php

namespace Boke0\Mechanism\Mdl;
use Boke0\Skull\Router;
use \Mustache_Engine as Engine;

class Struct extends Mdl{
    public function __construct(){
        $this->struct=json_decode(
            file_get_contents(__DIR__."/../../struct.json")
        ,TRUE);
        $this->router=new Router();
        $this->engine=new Engine();
        foreach($this->struct as $k=>$v){
            $this->router->any($k,$v);
        }
    }
    public function get($uri){
        $result=$this->router->match($uri,"GET");
        $data=$result["callable"];
        $path=$this->engine->render($data["rule"],$result["params"]);
        return [
            "datafile"=>$path,
            "type"=>$data["type"]
        ];
    }
}
