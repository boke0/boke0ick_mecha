<?php

namespace Boke0\Mechanism\Api;

abstract class Menu{
    public function __construct($container,$viewPath){
        $this->plugins=$container->get("plugin");
        $this->factory=$container->get("responseFactory");
        $twig=new \Twig\Loader\FilesystemLoader([
            __DIR__."/../Tpl/",    
            dirname(
                (new \ReflectionClass($this))->getFileName()
            ).$viewPath
        ]);
        $this->twig=new \Twig\Environment($twig);
    }
    abstract public function handle($req,$args);
    public function twig($file,$arg=[],$code="200",$reason="OK"){
        $res=$this->factory->createResponse($code,$reason);
        $body=$res->getBody();
        $arg=array_merge($arg,[
            "plugins"=>$this->plugins->getAdditionalMenu()
        ]);
        $body->write(
            $this->twig->render(
                $file,
                $arg
            )
        );
        return $res->withBody($body);
    }
}
