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
                $themeMdl=new Mdl\Theme($qs["theme"],$this->container->get("plugin"));
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
                $mime=$this->mimeContentType(__DIR__."/../../static/{$filename}");
                return $res->withBody($body)
                           ->withHeader("Content-Type",$mime);
            case "plugin":
                $body=$res->getBody();
                $body->write(
                    file_get_contents(__DIR__."/../../plugins/{$qs["plugin"]}/assets/{$filename}")
                );
                $mime=$this->mimeContentType(__DIR__."/../../plugins/{$qs["plugin"]}/assets/{$filename}");
                return $res->withBody($body)
                           ->withHeader("Content-Type",$mime);
        }
        return $res->withStatus(404,"Not Found");
    }
    public function mimeContentType($filename) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }else {
            return 'application/octet-stream';
        }
    }
}
