<?php

namespace Boke0\Mechanism\Mdl;

class Invite extends Mdl{
    public function __construct($db){
        $this->db=$db;
    }
    public function create($email){
        $token=hash("sha256",uniqid().mt_rand().$email);
        $this->db->query("insert into invite (screen_name,token,datetime) values (:email,:token,now())",[
            ":email"=>$email,
            ":token"=>$token
        ]);
       return $token;
    }
    public function get($token){
        $result=$this->db->query("select id,screen_name from invite where token=:token",[
            ":token"=>$token
        ])[0]; 
        return isset($result)?$result:FALSE;
    }
    public function delete($id){
        return $result=$this->db->query("delete from invite where id=:id",[
            ":id"=>$id
        ]);
    }
}
