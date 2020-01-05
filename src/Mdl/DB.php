<?php

namespace Boke0\Mechanism\Mdl;
use \PDO;

class DB{
    public function __construct($pdo){
        $this->dbh=$pdo;
    }
    public function query($sql,$values=[]){
        $stmt=$this->dbh->prepare($sql);
        foreach($values as $k=>$v){
            switch(gettype($v)){
                case "integer":
                    $stmt->bindValue($k,$v,PDO::PARAM_INT);
                    break;
                case "string":
                    $stmt->bindValue($k,$v,PDO::PARAM_STR);
                    break;
                case "boolean":
                    $stmt->bindValue($k,$v,PDO::PARAM_BOOL);
                    break;
                case "NULL":
                    $stmt->bindValue($k,$v,PDO::PARAM_NULL);
                    break;
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function lastInsertId($id="id"){
        return $this->dbh->lastInsertId($id);
    }
}
