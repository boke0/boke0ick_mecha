<?php
session_start();
require_once(__DIR__."/../vendor/autoload.php");

use Boke0\Scapula\App;
use Boke0\Clavicle\ResponseFactory;
use Boke0\Clavicle\ServerRequestFactory;
use Boke0\Clavicle\UploadedFileFactory;
use Boke0\Clavicle\StreamFactory;
use Boke0\Skull\Dispatcher;
use Boke0\Rose\Container;
use Boke0\Mechanism\Ctrl;
use Boke0\Mechanism\Mdl;
use Boke0\Mechanism\Mdlw;
use Boke0\Mechanism\Cfg;
use Boke0\Mechanism\MarkdownParser;

$container=new Container();
$container->add("responseFactory",function($c){
    return new ResponseFactory();
});
$container->add("serverRequestFactory",function($c){
    return new ServerRequestFactory();
});
$container->add("uploadedFileFactory",function($c){
    return new UploadedFileFactory();
});
$container->add("streamFactory",function($c){
    return new StreamFactory();
});

/* コントローラ解決 */
$container->add("installCtrl",function($c){
    return new Ctrl\InstallCtrl($c);
});
$container->add("loginCtrl",function($c){
    return new Ctrl\LoginCtrl($c);
});
$container->add("assetCtrl",function($c){
    return new Ctrl\AssetCtrl($c);
});
$container->add("mainCtrl",function($c){
    return new Ctrl\MainCtrl($c);
});

/* モデル解決 */
$container->add("plugin",function($c){
    return new Mdl\Plugin();
});
$container->add("theme",function($c){
    return new Mdl\Theme();
});
$container->add("struct",function($c){
    return new Mdl\Struct();
});
$container->add("article",function($c){
    return new Mdl\Article($c->get("parser"),$c->get("struct"));
});
$container->add("parser",function($c){
    return new \Mni\FrontYAML\Parser(
        NULL,
        $c->get("md")
    );
});
$container->add("md",function($c){
    return new MarkdownParser($c->get("parsedown"));
});
$container->add("parsedown",function($c){
    return new ParsedownExtra();
});
$container->add("csrfMdlw",function($c){
    return new Mdlw\CsrfMdlw($c->get("responseFactory"));
});
$app=new App(
    $container->get("serverRequestFactory"),
    $container->get("uploadedFileFactory"),
    $container->get("streamFactory")
);
$router=new Dispatcher(
    $container->get("responseFactory"),
    $container
);

$router->get("/asset","assetCtrl");
$router->any("/*","mainCtrl");
$endpoints=$container->get("plugin")->getEndpoints();
foreach($endpoints as $endpoint){
    $method=$endpoint["method"];
    $instance=new $endpoint["class"]($container->get("responseFactory"));
    $router->$method($endpoint["path"],$instance);
}
$app->pipe($container->get("csrfMdlw"));
$app->pipe($router);

$app->run();
