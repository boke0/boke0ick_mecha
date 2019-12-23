<?php

namespace Boke0\Mechanism\Ctrl;

class MainCtrl extends Ctrl{
    public function handle($req,$args){
        $server=$req->getServerParams();
        $uri=$req->getUri();
        $path=$uri->getPath();
        $theme=$this->container->get("theme");
        $struct=$this->container->get("struct");
        $article=$this->container->get("article");
        $detail=$struct->get($path);
        $res=$this->createResponse();
        $body=$res->getBody()->write($theme->render($detail["type"]));
        return $res->withBody($body);
    }
}
