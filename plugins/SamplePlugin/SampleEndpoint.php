<?php

namespace Boke0\Mechanism\Plugins\SamplePlugin;
use \Boke0\Mechanism\Api\Endpoint;

/**
 * @path /sampleendpoint
 */
class SampleEndpoint extends Endpoint{
    public function handle($req,$args){
        $res=$this->createResponse();
        $body=$res->getBody();
        $body->write("Hello world!");
        return $res->withBody($body);
    }
}
