<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Cfg;
use \Boke0\Mechanism\Cookie;

class InstallCtrl extends Ctrl{
    public function handle($req,$args){
        $server=$req->getServerParams();
        if($server["REQUEST_METHOD"]=="POST"){
            $post=$req->getParsedBody();
            $this->csrfTokenCheck($post["csrftoken"]);
            Cfg::set("dsn","mysql:host={$post["hostname"]};dbname={$post["dbname"]}");
            Cfg::set("dbuser",$post["dbuser"]);
            Cfg::set("dbpass",$post["dbpass"]);
            $db=$this->container->get("db");
            $db->query(
<<<EOT
create table user(
    id int not null primary key auto_increment,
    screen_name varchar(255) not null unique,
    password varchar(64) not null
);
create table invite(
    id int not null primary key auto_increment,
    screen_name varchar(255) not null,
    token varchar(64) not null,
    datetime datetime
); 
EOT
            );
            Cfg::set("jwt_secret",hash("sha256",uniqid().mt_rand()));
            return $this->createResponse()
                        ->withHeader("Location","/install/signup");
        }else{
            $this->csrfTokenSet();
        }
        return $this->twig("install/main");
    }
    public function signup($req,$args){
        $server=$req->getServerParams();
        if($server["REQUEST_METHOD"]=="POST"){
            $post=$req->getParsedBody();
            $this->csrfTokenCheck($post["token"]);
            $invite=$this->container->get("invite");
            $user=$this->container->get("user");
            $token=$invite->create($post["screen_name"]);
            $id=$user->signup($token,$post["password"]);
            Cookie::set("boke0ick-jwt",$user->createJWT($id),3600*24*12+time());
            return $this->createResponse()
                        ->withHeader("Location","/admin");
        }else{
            $this->csrfTokenSet();
        }
        return $this->twig("install/signup");
    }
}
