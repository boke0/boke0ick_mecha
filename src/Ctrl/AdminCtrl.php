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
    public function twig($path,$arg){
        $arg=array_merge($arg,[
            "plugins"=>$this->pluginMdl->getAdditionalMenus()
        ]);
        return parent::twig($path,$arg);
    }
    private function getSession($token){
        $session=$this->userMdl->session($token);
        var_dump($session,$token);
        return $session;
    }
    public function handle($req,$args){
        try{
            $session=$this->getSession($req->getCookieParams()["boke0ick-jwt"]);
        }catch(\Exception $e){
            return $this->createResponse(403,"Forbidden")
                        ->withHeader("Location","/admin/login");
        }
        return $this->twig("dash");
    }
    public function plugins($req,$args){
        try{
            $session=$this->getSession($req->getCookieParams()["boke0ick-jwt"]);
        }catch(\Exception $e){
            return $this->createResponse(403,"Forbidden")
                        ->withHeader("Location","/admin/login");
        }
        return $this->twig("plugins");
    }
    public function struct($req,$args){
        try{
            $session=$this->getSession($req->getCookieParams()["boke0ick-jwt"]);
        }catch(\Exception $e){
            return $this->createResponse(403,"Forbidden")
                        ->withHeader("Location","/admin/login");
        }
        return $this->twig("struct");
    }
    public function articles($req,$args){
        try{
            $session=$this->getSession($req->getCookieParams()["boke0ick-jwt"]);
        }catch(\Exception $e){
            return $this->createResponse(403,"Forbidden")
                        ->withHeader("Location","/admin/login");
        }
        return $this->twig("articles");
    }
    public function themes($req,$args){
        try{
            $session=$this->getSession($req->getCookieParams()["boke0ick-jwt"]);
        }catch(\Exception $e){
            return $this->createResponse(403,"Forbidden")
                        ->withHeader("Location","/admin/login");
        }
        return $this->twig("themes");
    }
    public function users($req,$args){
        try{
            $session=$this->getSession($req->getCookieParams()["boke0ick-jwt"]);
        }catch(\Exception $e){
            return $this->createResponse(403,"Forbidden")
                        ->withHeader("Location","/admin/login");
        }
        return $this->twig("users");
    }
}
