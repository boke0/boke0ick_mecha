<?php

namespace Boke0\Mechanism\Ctrl;
use Boke0\Mechanism\Cookie;
use Boke0\Mechanism\Mdl;

class AdminCtrl extends Ctrl{
    public function __construct($c){
        parent::__construct($c);
        $this->userMdl=$c->get("user");
        $this->pluginMdl=$c->get("plugin");
    }
    public function twig($path,$arg=[],$status="200",$reason="OK"){
        $arg=array_merge($arg,[
            "plugins"=>$this->pluginMdl->getAdditionalMenu()
        ]);
        return parent::twig($path,$arg,$status,$reason);
    }
    public function handle($req,$args){
        var_dump($req);
        return $this->twig("dash");
    }
    public function plugins($req,$args){
        return $this->twig("plugins");
    }
    public function struct($req,$args){
        return $this->twig("struct");
    }
    public function articles($req,$args){
        return $this->twig("articles");
    }
    public function themes($req,$args){
        return $this->twig("themes");
    }
    public function users($req,$args){
        return $this->twig("users");
    }
}
