<?php

namespace Boke0\Mechanism\Ctrl;

class InstallCtrl extends Ctrl{
    public function handle($req,$args){
        return $this->twig("install.html");
    }
}
