<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Mdl;

class MainCtrl extends Ctrl{
    public function handle($req,$args){
        $uri=$req->getUri();
        $path=$uri->getPath();
        $struct=new Mdl\Struct();
        $detail=$struct->get($path);
        $theme=new Mdl\Theme(
            $detail["theme"],
            $this->container->get("plugin")
        );
        $env=$theme->getEnvironment();
        $site=new Mdl\Site($this->container->get("parser"),$env,$struct);
        if(is_file(__DIR__."/../../contents/".$path)){
            $page=new Mdl\Page($path,$struct,$this->container->get("parser"),$env);
            $section=$page->section;
        }else{
            $section=new Mdl\Section($path,$struct,$this->container->get("parser"),$env);
        }
        $res=$this->createResponse();
        $body=$res->getBody();
        $body->write(
            $theme->render(
                $detail["type"],
                [
                    "site"=>$site,
                    "section"=>$section,
                    "page"=>isset($page)?$page:NULL
                ]
            )
        );
        return $res->withBody($body);
    }
}
