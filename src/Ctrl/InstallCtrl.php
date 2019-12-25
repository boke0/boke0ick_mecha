<?php

namespace Boke0\Mechanism\Ctrl;
use \Boke0\Mechanism\Cfg;

class InstallCtrl extends Ctrl{
    public function handle($req,$args){
        $server=$req->getServerParams();
        if($server["REQUEST_METHOD"]=="POST"){
            $this->csrfTokenCheck();
            $post=$req->getParsedBody();
            Cfg::set("dsn","mysql:host={$post["hostname"]};dbname={$post["dbname"]}");
            Cfg::set("dbuser",$post["dbuser"]);
            Cfg::set("dbpass",$post["dbpass"]);
            $db=$this->container->get("db");
            $db->query(
<<<EOT
create table user(
    id int not null primary key auto_increment,
    email varchar(255) not null unique,
    screen_name varchar(255) not null,
    password varchar(64) not null,
);
create table invite(
    id int not null primary key auto_increment,
    email varchar(255) not null unique,
    token varchar(64) not null
); 
EOT
            );
            return $req->withHeader("Location","/install/signup");
        }else{
            $this->csrfTokenSet();
        }
        return $this->twig("install/main");
    }
    public function signup($req,$args){
        $server=$req->getServerParams();
        if($server["REQUEST_METHOD"]=="POST"){
            $this->csrfTokenCheck();
            $post=$req->getParsedBody();
            $email=$post["email"];
            $invite=$this->container->get("invite");
            $invite->create($email);
            return $this->twig("install/mailed");
        }else{
            $this->csrfTokenSet();
        }
        return $this->twig("install/signup");
    }
}
