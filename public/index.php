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
    return new Mdl\Invite($c->get("db"));
});
$container->add("user",function($c){
    return new Mdl\User($c->get("db"),$c->get("invite"));
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
$container->add("db",function($c){
    return new Mdl\DB(
        new \PDO(
            Cfg::get("dsn"),
            Cfg::get("dbuser"),
            Cfg::get("dbpass")
        )
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
$router->any("/admin/*",function($req,$arg){
    $admin_app=new App(
        $container->get("serverRequestFactory"),
        $container->get("uploadedFileFactory")
    );
    $admin_router=new Dispatcher($container->get("responseFactory"),$container);
    $admin_router->any("/admin","adminCtrl");
    $admin_router->any("/admin/logout","adminCtrl","logout");
    $admin_router->any("/admin/plugins","adminCtrl","plugins");
    $admin_router->any("/admin/struct","adminCtrl","struct");
    $admin_router->any("/admin/articles","adminCtrl","articles");
    $admin_router->any("/admin/themes","adminCtrl","themes");
    $admin_router->any("/admin/comments","adminCtrl","comments");
    $admin_router->any("/admin/users","adminCtrl","users");
    $admin_app->pipe(new AdminUserMdlw($container->get("adminUserMdlw")));
    $admin_app->pipe($admin_router);
    return $admin_app->handle();
});
$router->any("/*","mainCtrl");

$endpoints=$container->get("plugin")->getEndpoints();
foreach($endpoints as $endpoint){
    $router->any($endpoint->path,$endpoint);
}
$app->pipe($router);

$app->run();
