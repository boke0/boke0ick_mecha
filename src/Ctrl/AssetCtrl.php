<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Mdl;
use \Boke0\Mechanism\Cfg;

class AssetCtrl extends Ctrl{
    public function handle($req,$arg){
        $qs=$req->getQueryParams();
        $type=isset($qs["type"])?$qs["type"]:NULL;
        $filename=urldecode($qs["filename"]);
        $res=$this->createResponse();
        $themeMdl=new Mdl\Theme(Cfg::get("theme"),$this->container->get("plugin"));
        if($type=="static"||file_exists(__DIR__."/../../static/{$filename}")){
            $body=$res->getBody();
            $body->write(
                file_get_contents(__DIR__."/../../static/{$filename}")
            );
            $mime=$themeMdl->mimeContentType(__DIR__."/../../static/{$filename}");
            return $res->withBody($body)
                       ->withHeader("Content-Type",$mime);
        }else if($themeMdl->assetExists($filename)){
            $res=$themeMdl->asset($res,$filename);
        }else{
            $res=$res->withStatus(404,"Not Found");
        }
        return $res;
    }
}
