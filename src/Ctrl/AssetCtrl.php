<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Mdl;

class AssetCtrl extends Ctrl{
    public function handle($req,$arg){
        $qs=$req->getQueryParams();
        $type=isset($qs["type"])?$qs["type"]:FALSE;
        $filename=urldecode($qs["filename"]);
        $res=$this->createResponse();
        if(!$type){
            if(isset($qs["theme"])){
                $type="theme";
            }else if(isset($qs["plugin"])){
                $type="plugin";
            }else{
                $type="static";
            }
        }
        switch($type){
            case "theme":
                $themeMdl=new Mdl\Theme($qs["theme"],[],$this->container->get("plugin"));
                if($themeMdl->assetExists($filename)){
                    return $themeMdl->asset($res,$filename);
                }else{
                    return $res->withStatus(404,"Not Found");
                }
            case "static":
                $body=$res->getBody();
                $body->write(
                    file_get_contents(__DIR__."/../../static/{$filename}")
                );
                $mime=$themeMdl->mimeContentType(__DIR__."/../../static/{$filename}");
                return $res->withBody($body)
                           ->withHeader("Content-Type",$mime);
            case "plugin":
                $body=$res->getBody();
                $body->write(
                    file_get_contents(__DIR__."/../../plugins/{$qs["plugin"]}/assets/{$filename}")
                );
                $mime=$themeMdlr>mimeContentType(__DIR__."/../../plugins/{$qs["plugin"]}/assets/{$filename}");
                return $res->withBody($body)
                           ->withHeader("Content-Type",$mime);
        }
        return $res->withStatus(404,"Not Found");
    }
}
