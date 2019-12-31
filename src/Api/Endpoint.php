<?php

namespace Boke0\Mechanism\Api;

abstract class Endpoint{
    public $path;
    abstract public function handle($req,$args): ResponseInterface;
}
