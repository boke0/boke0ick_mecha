<?php

namespace Boke0\Mechanism\Mdl;
use \Boke0\Mechanism\Cfg;

class Mdl{
    public function conDb(){
        $this->dbh=new \PDO(
            Cfg::get("dsn"),
            Cfg::get("dbuser"),
            Cfg::get("dbpasswd")
        );
    }
    public function query($sql,$array=[]){
        $stmt=$this->dbh->prepare($sql);
        $stmt->execute($array);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
