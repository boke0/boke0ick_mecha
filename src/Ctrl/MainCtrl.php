<?php

namespace Boke0\Mechanism\Ctrl;

class MainCtrl extends Ctrl{
    public function handle($req,$args){
        $uri=$req->getUri();
        $path=$uri->getPath();
        $theme=$this->container->get("theme");
        $struct=$this->container->get("struct");
        $article=$this->container->get("article");
        $detail=$struct->get($path);
        $data=$article->get($detail["datafile"]);
        $res=$this->createResponse();
        $body=$res->getBody();
        $body->write(
            $theme->render($detail["type"],$data)
        );
        return $res->withBody($body);
    }
}
