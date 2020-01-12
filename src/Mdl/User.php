<?php

namespace Boke0\Mechanism\Mdl;
use \Boke0\Mechanism\Cfg;

class User extends Mdl{
    public function __construct($db,$invite){
        $this->invite=$invite;
        $this->db=$db;
    }
    public function login($user,$password){
        $result=$this->db->query("select id from user where screen_name=:user and password=:password",[
            ":user"=>$user,
            ":password"=>$this->getPasswd($password)
        ])[0];
        return $result["id"];
    }
    public function signup($token,$password){
        $invite=$this->invite->get($token);
        $this->db->query("insert into user (screen_name,password) values (:screen_name,:password)",[
            ":screen_name"=>$invite["screen_name"],
            ":password"=>$this->getPasswd($password)
        ]);
        $this->invite->delete($invite["id"]);
        return $this->db->lastInsertId();
    }
    public function get($id){
        $result=$this->db->query("select screen_name from user where id=:id",[
            ":id"=>$id
        ])[0];
        return $result;
    }
    public function session($token){
        list($head,$body,$sig)=explode(".",$token);
        $certain=hash("sha256",$head.".".$body.Cfg::get("jwt_secret"));
        if($certain!=base64_decode($sig)) throw new \Exception("Forbidden");
        $head=json_decode(base64_decode($head));
        $body=json_decode(base64_decode($body));
        $id=$body->userId;
        return $this->get($id);
    }
    public function changePasswd($id,$password){
        $this->db->query("update user set password=:password where id=:id",[
            ":password"=>$this->getPasswd($password),
            ":id"=>$id
        ]);
    }
    public function getPasswd($password){
        return hash("sha256",$password.Cfg::get("passwd_key"));
    }
    public function createJWT($id){
        $head=base64_encode(
            json_encode(
                [
                    "alg"=>"HS256",
                    "typ"=>"JWT"
                ]
            )
        );
        $body=base64_encode(
            json_encode(
                [
                    "userId"=>$id,
                    "iat"=>time()
                ]
            )
        );
        $sig=base64_encode(hash("sha256",$head.".".$body.Cfg::get("jwt_secret")));
        return "{$head}.{$body}.{$sig}";
    }
}
