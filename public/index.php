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

/* コントローラ解決 */
$container->add("installCtrl",function($c){
    return new Ctrl\InstallCtrl($c);
});
$container->add("adminCtrl",function($c){
    return new Ctrl\AdminCtrl($c);
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
$container->add("invite",function($c){
    $db=$c->get("db");
    if(!$db) return FALSE;
    return new Mdl\Invite($c->get("db"));
});
$container->add("user",function($c){
    $db=$c->get("db");
    if(!$db) return FALSE;
    return new Mdl\User($db,$c->get("invite"));
});
$container->add("plugin",function($c){
    return new Mdl\Plugin();
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
$container->add("adminSessionMdlw",function($c){
    return new Mdlw\AdminSessionMdlw($c->get("responseFactory"),$c->get("user"));
});
$container->add("db",function($c){
    try{
        $pdo=new \PDO(
            Cfg::get("dsn"),
            Cfg::get("dbuser"),
            Cfg::get("dbpass"),
            [
                \PDO::ATTR_EMULATE_PREPARES=>false,
                \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
            ]
        );
    }catch(\Exception $e){
        return FALSE;
    }
    return new Mdl\DB(
        $pdo
    );
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
$router->any("/admin/login","loginCtrl");
$router->any("/admin/logout","loginCtrl","logout");
$router->any("/admin/*",function($req,$arg){
    global $container;
    $admin_app=new App(
        $container->get("serverRequestFactory"),
        $container->get("uploadedFileFactory")
    );
    $admin_router=new Dispatcher($container->get("responseFactory"),$container);
    $admin_router->any("/admin","adminCtrl");
    $admin_router->any("/admin/plugins","adminCtrl","plugins");
    $admin_router->any("/admin/articles","adminCtrl","articles");
    $admin_router->any("/admin/themes","adminCtrl","themes");
    $admin_router->any("/admin/comments","adminCtrl","comments");
    $admin_router->any("/admin/users","adminCtrl","users");
    $menus=$container->get("plugin")->getAdditionalMenu();
    foreach($menus as $menu){
        $title=$menu["title"];
        $instance=new $menu["class"]($container,$menu["view"]);
        $admin_router->any("/admin/{$menu["slug"]}",$instance);
    }
    $admin_app->pipe($container->get("adminSessionMdlw"));
    $admin_app->pipe($admin_router);
    return $admin_app->handle($req);
});
$router->any("/*","mainCtrl");
$endpoints=$container->get("plugin")->getEndpoints();
foreach($endpoints as $endpoint){
    $method=$endpoint["method"];
    $instance=new $endpoint["class"]($container->get("responseFactory"));
    $router->$method($endpoint["path"],$instance);
}
$app->pipe($router);

$app->run();
