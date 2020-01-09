<?php

namespace Boke0\Mechanism\Api;

abstract class Menu{
    public function __construct($container,$viewPath="/tpl"){
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
    public function twig($file,$arg){
        $arg=array_merge($arg,[
            "plugins"=>$this->plugins->getAdditionalMenus()
        ]);
        return $this->twig->render(
            $file,
            $arg
        );
    }
}
