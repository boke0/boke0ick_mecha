<?php

namespace Boke0\Mechanism\Ctrl;
use Boke0\Mechanism\Cookie;

class Ctrl{
    public function __construct($container){
        $this->factory=$container->get("responseFactory");
        $this->container=$container;
        $plugin=$this->container->get("plugin");
    }
    public function json($data,$status=200,$reason="OK"){
        $res=$this->factory->createResponse($status,$reason)
                           ->withHeader("Content-Type","application/json");
        $body=$res->getBody();
        $body->write(json_encode($data));
        return $res->withBody($body);
    }
    public function createResponse($status=200,$reason="OK"){
        return $this->factory->createResponse($status,$reason);
    }
}
