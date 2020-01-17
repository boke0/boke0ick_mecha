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
        list($data,$loader)=$article->get($path);
        $data["menu"]=$article->getMenu($detail["datafile"]);
        $theme=new Mdl\Theme(
            $detail["theme"],
            $loader,
            $this->container->get("plugin")
        );
        $res=$this->createResponse();
        $body=$res->getBody();
        $body->write(
            $theme->render(
                $detail["type"],
                $data
            )
        );
        return $res->withBody($body);
    }
}
