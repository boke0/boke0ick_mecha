<?php

namespace Boke0\Mechanism\Api;

abstract class Menu{
    abstract public function (
        $page_title,
        $menu_title,
        $menu_slug,
        closure $function
    );
}
