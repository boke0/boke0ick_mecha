<?php

namespace Boke0\Mechanism\Plugins\SamplePlugin;
use \Boke0\Mechanism\Api\Menu;

/**
 * @title サンプル
 * @slug sample
 */
class SampleMenu extends Menu{
    public function handle($req,$args){
        $res=$this->twig("sample.html");
        return $res;
    }
}
