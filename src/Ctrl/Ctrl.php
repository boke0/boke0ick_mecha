<?php

namespace Boke0\Mechanism\Ctrl;
use Boke0\Mechanism\Cookie;

class Ctrl{
    public function __construct($container){
        $this->factory=$container->get("responseFactory");
        $this->container=$container;
        $loader=new \Twig\Loader\FilesystemLoader(__DIR__."/../Tpl");
        $this->twig=new \Twig\Environment($loader);
        $plugin=$this->container->get("plugin");
    }
    public function twig($tpl,$array=[],$status="200",$reason="OK"){
        $array=array_merge(
            $array,
            [
                "csrftoken"=>Cookie::get("token")
            ]
        );
        $res=$this->factory->createResponse($status,$reason);
        $body=$res->getBody();
        $body->write(
            $this->twig->render($tpl.".html",$array)
        );
        return $res->withBody($body);
    }
    public function json($data,$status=200,$reason="OK"){
        $res=$this->factory->createResponse($status,$reason)
                           ->withHeader("Content-Type","application/json");
        $body=$res->getBody();
        $body->write(json_encode($data));
        return $res->withBody($body);
    }
    public function csrfTokenCheck($token){
        if(Cookie::get("csrftoken")!=$token){
            throw new Exception("攻撃を検知しました");
        }
    }
    public function csrfTokenSet(){
        Cookie::set(
            "csrftoken",
            hash("sha256",uniqid().mt_rand()),
            3600*24
        );
    }
    public function createResponse($status=200,$reason="OK"){
        return $this->factory->createResponse($status,$reason);
    }
}
