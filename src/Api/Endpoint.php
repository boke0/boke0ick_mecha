<?php

namespace Boke0\Mechanism\Api;
use Twig;
abstract class Endpoint{
    public function __construct($factory){
        $trace=debug_backtrace();
        $ref=new \ReflectionClass($trace[0]["object"]);
        $dir=pathinfo($ref->getFileName())["dirname"];
        $twig=new \Twig\Loader\FilesystemLoader($dir."/tpl");
        $this->twig=new \Twig\Environment($twig,[
            "autoescape"=>false,
            "charset"=>"utf-8",
        ]);
        $this->factory=$factory;
    }
    abstract public function handle($req,$args);
    public function createResponse($code=200,$reason="OK"){
        return $this->factory->createResponse($code,$reason);
    }
    public function twig($filename,$values=[],$code=200,$reason="OK"){
        $res=$this->createResponse($code,$reason);
        $body=$res->getBody();
        $body->write($this->twig->render($filename,$values));
        return $res->withBody($body);
    }
}
