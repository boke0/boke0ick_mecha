<?php

namespace Boke0\Mechanism\Mdl;

class Theme{
    public function __construct($theme,$plugin){
        $twig=new \Twig\Loader\FilesystemLoader(__DIR__."/../../themes/{$theme}");
        $this->twig=new \Twig\Environment($twig,[
            "autoescape"=>false,
            "charset"=>"utf-8",
        ]);
        $functions=$plugin->getTemplateExtensions();
        foreach((array)$functions as $function){
            $this->twig->addExtension(new $function["class"]());
        }
        $this->theme=$theme;
    }
    public function getEnvironment(){
        return $this->twig;
    }
    public function render($type,$data){
        return $this->twig->render("{$type}.tpl.html",$data);
    }
    public function asset($res,$name){
        $path=__DIR__."/../../themes/{$this->theme}/$name";
        $body=$res->getBody();
        $body->write(
            file_get_contents($path)
        );
        $mime=$this->mimeContentType($path);
        return $res->withBody($body)
                   ->withHeader("Content-Type",$mime);
    }
    public function assetExists($filename){
        return file_exists(__DIR__."/../../themes/{$this->theme}/{$filename}");
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
