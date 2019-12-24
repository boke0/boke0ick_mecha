<?php

namespace Boke0\Mechanism\Ctrl;

class MainCtrl extends Ctrl{
    public function handle($req,$args){
        $uri=$req->getUri();
        $path=$uri->getPath();
        $theme=$this->container->get("theme");
        $struct=$this->container->get("struct");
        $detail=$struct->get($path);
        $res=$this->createResponse();
        $body=$res->getBody();
        $body->write(
            $theme->render($detail["type"],)
        );
        return $res->withBody($body);
    }
}
