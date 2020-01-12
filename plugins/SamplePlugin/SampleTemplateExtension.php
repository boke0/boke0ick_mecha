<?php

namespace Boke0\Mechanism\Plugins\SamplePlugin;
use \Boke0\Mechanism\Api\TemplateExtension;

class SampleTemplateExtension extends TemplateExtension{
    public function getFunctions(){
        return [
            new Twig_SimpleFunction("sample",function(){
                echo "sample function this is";
            })
        ];
    }
}
