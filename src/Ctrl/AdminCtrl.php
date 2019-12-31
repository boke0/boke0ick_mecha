<?php

namespace Boke0\Mechanism\Ctrl;
use Boke0\Mechanism\Cookie;
use Boke0\Mechanism\Mdl;

class AdminCtrl extends Ctrl{
    public function __construct($c){
        parent::__construct($c);
        $this->userMdl=new Mdl\User();
    }
    private function getSession($token){
        $session=$this->userMdl->session($token);
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
    public function logout($req,$args){
        try{
            $session=$this->getSession($req->getCookieParams()["boke0ick-jwt"]);
        }catch(\Exception $e){
            return $this->createResponse(403,"Forbidden")
                        ->withHeader("Location","/admin/login");
        }
        Cookie::delete("boke0ick-jwt");
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
