<?php

namespace Boke0\Mechanism\Mdl;

class Site{
    public function __construct($converter,$env,$struct){
        $json=json_decode(file_get_contents(__DIR__."/../../contents/site.json"),TRUE);
        foreach($json as $k=>$v){
            $this->$k=$v;
        }
        $this->_struct=$struct;
    }
    public function __get($k){
        switch($k){
            case "pages":
                $dirname=__DIR__."/../../contents/";
                $files=explode("\n",`find $dirname -type f -print0 | xargs -0 ls -t`);
                $pages=array();
                foreach($files as $f){
                    $fname=pathinfo($f,PATHINFO_FILENAME);
                    if(substr($fname,0,2)!="__"||substr($fname,0,1)!="."){
                        continue;
                    }
                    array_push($pages,new Page($fname,$this->_struct,$this->converter,$this->env));
                }
                return $pages;
            case "sections":
                $dirname=__DIR__."/../../contents/";
                $dirs=explode("\n",`find $dirname -type d -print0 | xargs -0 ls -t`);
                $sections=array();
                foreach($dirs as $d){
                    $dname=array_pop(explode("/",$d));
                    if(substr($dname,0,1)!="."){
                        continue;
                    }
                    array_push($sections,new Section($dname,$this->_struct,$this->converter,$this->env));
                }
                return $pages;
            default:
                return $this->$k;
        }
    }
}
