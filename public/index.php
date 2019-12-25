<?php

require_once(__DIR__."/../vendor/autoload.php");

use Boke0\Scapula\App;
use Boke0\Clavicle\ResponseFactory;
use Boke0\Clavicle\ServerRequestFactory;
use Boke0\Clavicle\UploadedFileFactory;
use Boke0\Skull\Dispatcher;
use Boke0\Rose\Container;
use Boke0\Mechanism\Ctrl;
use Boke0\Mechanism\Mdl;
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

/* コントローラ解決 */
$container->add("installCtrl",function($c){
    return new Ctrl\InstallCtrl($c);
});
$container->add("adminCtrl",function($c){
    return new Ctrl\AdminCtrl($c);
});
$container->add("assetCtrl",function($c){
    return new Ctrl\AssetCtrl($c);
});
$container->add("mainCtrl",function($c){
    return new Ctrl\MainCtrl($c);
});

/* モデル解決 */
$container->add("invite",function($c){
    return new Mdl\Invite($c->get("db"));
});
$container->add("user",function($c){
    return new Mdl\User($c->get("db"),$c->get("invite"));
});
$container->add("theme",function($c){
    return new Mdl\Theme(Cfg::get("theme"));
});
$container->add("plugin",function($c){
    return new Mdl\Plugin();
});
$container->add("struct",function($c){
    return new Mdl\Struct();
});
$container->add("article",function($c){
    return new Mdl\Article($c->get("parser"));
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

$app=new App(
    $container->get("serverRequestFactory"),
    $container->get("uploadedFileFactory")
);
$router=new Dispatcher(
    $container->get("responseFactory"),
    $container
);

$router->any("/install","installCtrl");
$router->any("/install/signup","installCtrl","signup");
$router->get("/asset","assetCtrl");
$router->any("/admin","adminCtrl");
$router->any("/admin/login","adminCtrl","login");
$router->any("/admin/logout","adminCtrl","logout");
$router->any("/admin/plugins","adminCtrl","plugins");
$router->any("/admin/struct","adminCtrl","struct");
$router->any("/admin/articles","adminCtrl","articles");
$router->any("/admin/themes","adminCtrl","themes");
$router->any("/admin/comments","adminCtrl","comments");
$router->any("/admin/users","adminCtrl","users");
$router->any("/*","mainCtrl");

$app->pipe($router);

$app->run();
