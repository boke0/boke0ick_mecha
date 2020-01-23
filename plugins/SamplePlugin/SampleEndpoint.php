<?php

namespace Boke0\Mechanism\Plugins\SamplePlugin;
use \Boke0\Mechanism\Api\Endpoint;

/**
 * @path /sampleendpoint
 */
class SampleEndpoint extends Endpoint{
    public function handle($req,$args){
        return $this->twig("sample.tpl.html");
    }
}
