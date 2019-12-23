<?php

namespace Boke0\Mechanism\Ctrl;
use Boke0\Mechanism\Cookie;
use Boke0\Mechanism\Mdl;

class AdminCtrl extends Ctrl{
    public function __construct(){
        $this->userMdl=new Mdl\User();
    }
    public function dash($req,$args){
        return $this->twig("dash.html");
    }
    public function login($req,$args){
        if($req->getServerParams()["REQUEST_METHOD"]=="POST"){
            $this->csrfTokenCheck();
            $post=$req->getParsedBody();            
            
        }else{
            Session::set(
                "csrftoken",
                hash("sha256",uniqid().mt_rand())
            );
            $this->csrfTokenSet();
        }
        return $this->twig("login.html");
    }
    public function logout($req,$args){
        Cookie::delete("boke0ick-jwt");
    }
    public function plugins($req,$args){
        return $this->twig("plugins.html");
    }
    public function struct($req,$args){
        return $this->twig("struct.html");
    }
    public function articles($req,$args){
        return $this->twig("articles.html");
    }
    public function themes($req,$args){
        return $this->twig("themes.html");
    }
    public function comments($req,$args){
        return $this->twig("comments.html");
    }
    public function users($req,$args){
        return $this->twig("users.html");
    }
}
