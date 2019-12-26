<?php

namespace Boke0\Mechanism\Api;

abstract class Endpoint{
    abstract public function handle($req,$args): ResponseInterface;
}
