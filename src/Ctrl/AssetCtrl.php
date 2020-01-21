<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Mdl;

class AssetCtrl extends Ctrl{
    public function handle($req,$arg){
        $qs=$req->getQueryParams();
        $type=isset($qs["type"])?$qs["type"]:NULL;
        $filename=urldecode($qs["filename"]);
        $res=$this->createResponse();
        if($type=="theme"||isset($qs["theme"])){
            $themeMdl=new Mdl\Theme($qs["theme"],[],$this->container->get("plugin"));
            if($themeMdl->assetExists($filename)){
                return $themeMdl->asset($res,$filename);
            }else{
                return $res->withStatus(404,"Not Found");
            }
        }else if($type="static"||file_exists(__DIR__."/../../static/{$filename}")){
            $body=$res->getBody();
            $body->write(
                file_get_contents(__DIR__."/../../static/{$filename}")
            );
            $mime=$themeMdl->mimeContentType(__DIR__."/../../static/{$filename}");
            return $res->withBody($body)
                       ->withHeader("Content-Type",$mime);
        }
        return $res->withStatus(404,"Not Found");
    }
}
