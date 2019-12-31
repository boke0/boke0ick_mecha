<?php

namespace Boke0\Mechanism\Mdl;

class DB{
    public function query($sql){
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
}
