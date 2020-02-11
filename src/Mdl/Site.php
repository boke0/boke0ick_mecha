<?php

namespace Boke0\Mechanism\Mdl;

class Site{
    public function __construct($converter,$env,$struct){
        $json=json_decode(file_get_contents(__DIR__."/../../contents/site.json"),TRUE);
        foreach($json as $k=>$v){
            $this->$k=$v;
        }
        $this->_struct=$struct;
        $this->converter=$converter;
        $this->env=$env;
    }
    public function pages(){
        $dirname=__DIR__."/../../contents/";
        $files=explode("\n",`cd $dirname;find . -type f -print0 | xargs -0 ls -t`);
        $pages=array();
        foreach($files as $f){
            $fname=pathinfo($f,PATHINFO_FILENAME);
            if(substr($fname,0,2)=="__"||$fname==""||substr($f,-3)!=".md"){
                continue;
            }
            array_push($pages,new Page(substr($f,0,-3),$this->_struct,$this->converter,$this->env));
        }
        return $pages;
    }
    public function sections(){
        $dirname=__DIR__."/../../contents/";
        $dirs=explode("\n",trim(`cd $dirname; find . -type d`));
        $sections=array();
        foreach($dirs as $d){
            $dname=basename($d);
            if(substr($dname,0,1)=="."){
                continue;
            }
            array_push($sections,new Section($d,$this->_struct,$this->converter,$this->env));
        }
        return $sections;
    }
}
