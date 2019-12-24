<?php

namespace Boke0\Mechanism\Mdl;

class Theme extends Mdl{
    public function __construct($theme){
        $twig=new \Twig\Loader\FilesystemLoader(__DIR__."/../../themes/{$theme}");
        $this->twig=new \Twig\Environment($twig,[
            "autoescape"=>false,
            "charset"=>"utf-8",
        ]);
        $this->theme=$theme;
    }
    public function render($type,$data){
        return $this->twig->render("{$type}.tpl.html",$data);
    }
    public function asset($res,$name){
        $path=__DIR__."/../../themes/{$this->theme}/$name";
        $body=$res->getBody()->write(
            file_get_contents($path)
        );
        $mime=mime_content_type($path);
        return $res->withBody($body)
                   ->withHeader("Content-Type",$mime);
    }
    public function assetExists($filename){
        return file_exists(__DIR__."/../../themes/{$this->theme}/{$filename}");
    }
}
