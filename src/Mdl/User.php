<?php

namespace Boke0\Mechanism\Mdl;
use \Boke0\Mechanism\Cfg;

class User extends Mdl{
    public function login($user,$password){
        $result=$this->query("select id from user where screen_name=:user and password=:password",[
            ":user"=>$user,
            ":password"=>$this->getPasswd($password)
        ])[0];
        return $result["id"];
    }
    public function signup($token,$user,$password){
        $invite=$this->invite->get($token);
        $this->query("insert into user (screen_name,password,email) values (:screen_name,:password,:email)",[
            ":screen_name"=>$screen_name,
            ":password"=>$password,
            ":email"=>$invite["email"]
        ]);
        return $this->lastInsertId();
    }
    public function get($id){
        $result=$this->query("select screen_name from user where id=:id")[0];
        return $result;
    }
    public function session($token){
        list($head,$body,$sig)=explode(".",$token);
        $certain=hash("sha256",$head.".".$body.Cfg::get("jwt_secret"));
        if($certain!=$sig) throw new \Exception("Forbidden");
        $head=json_decode(base64_decode($head));
        $body=json_decode(base64_decode($body));
        $id=$body->userId;
        return $this->get($id);
    }
    public function changePasswd($id,$password){
        $this->query("update user set password=:password where id=:id",[
            ":password"=>$this->getPasswd($password),
            ":id"=>$id
        ]);
    }
    public function getPasswd($password){
        return hash("sha256",$password.Cfg::get("passwd_key"));
    }
}
