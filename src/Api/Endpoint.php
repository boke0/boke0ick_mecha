<?php

namespace Boke0\Mechanism\Api;

abstract class Endpoint{
    public function __construct($factory){
        $this->factory=$factory;
    }
    abstract public function handle($req,$args);
    public function createResponse($code=200,$reason="OK"){
        return $this->factory->createResponse($code,$reason);
    }
}
