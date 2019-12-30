<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Mdl;

class MainCtrl extends Ctrl{
    public function handle($req,$args){
        $uri=$req->getUri();
        $path=$uri->getPath();
        $struct=$this->container->get("struct");
        $article=$this->container->get("article");
        $detail=$struct->get($path);
        $data=$article->get($path);
        $theme=new Mdl\Theme($detail["theme"]);
        $data["menu"]=$article->getMenu($detail["datafile"]);
        $res=$this->createResponse();
        $body=$res->getBody();
        $body->write(
            $theme->render($detail["type"],$data)
        );
        return $res->withBody($body);
    }
}
