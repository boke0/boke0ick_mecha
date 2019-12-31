<?php

namespace Boke0\Mechanism\Mdl;

class Plugin extends Mdl{
    public function __construct(){
        $plugin_dir_path=__DIR__."/../../plugins/";
        $plugin_dirs=scandir($plugin_dir_path);
        $this->endpoints=array();
        $this->functions=array();
        $this->hooks=array();
        $this->menus=array();
        foreach($plugin_dirs as $dir){
            if(file_exists($plugin_dir_path.$dir."/__construct.php")){
                $plugin=require($plugin_dir_path.$dir."/__construct.php");
                $this->endpoints+=$plugin->getEndpoints();
                $this->functions+=$plugin->getTemplateFunctions();
                $this->hooks+=$plugin->getHooks();
                $this->menus+=$plugin->getAdditionalMenus();
            }
        }
    }
    public function getTemplateFunctions(){
        return $this->functions;
    }
    public function getHooks(){
        return $this->hooks;
    }
    public function getEndpoints(){
        return $this->endpoints;
    }
    public function getAdditionalMenu(){
        return $this->menus;
        
    }
}
