<?php

namespace Boke0\Mechanism\Mdl;

class Plugin{
    public function __construct(){
        $plugin_dir_path=__DIR__."/../../plugins/";
        $plugin_dirs=scandir($plugin_dir_path);
        $this->endpoints=array();
        $this->extension=array();
        foreach($plugin_dirs as $dir){
            if(file_exists($plugin_dir_path.$dir."/__construct.php")){
                $plugin=require($plugin_dir_path.$dir."/__construct.php");
                $this->endpoints=array_merge($this->endpoints,$plugin->getEndpoints());
                $this->extension=array_merge($this->extension,$plugin->getTemplateExtensions());
            }
        }
    }
    public function getTemplateExtensions(){
        return $this->extension;
    }
    public function getEndpoints(){
        return $this->endpoints;
    }
}
