<?php

namespace Boke0\Mechanism\Mdl;

class Invite extends Mdl{
    public function make($email){
        $token=hash("sha256",uniqid().mt_rand().$email);
        $this->query("insert into invite (email,token,datetime) values (:email,:token,now())",[
            ":email"=>$email,
            ":token"=>$token
        ]);
        return $token;
    }
    public function get($token){
        $result=$this->query("select id,email from invite where token=:token",[
            ":token"=>$token
        ])[0]; 
        return isset($result)?$result:FALSE;
    }
    public function delete($id){
        return $result=$this->query("delete from invite where id=:id",[
            ":id"=>$id
        ]);
    }
}
