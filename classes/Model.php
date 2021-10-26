<?php

class Model
{

    protected function getTableColumns($class){
        $db = new Db();
        $res = $db->connection()->query("DESC ".$class);
        $ret = [];
        $row = $res->fetchAll();
        foreach($row as $r){
            array_push($ret, $r['Field']);
        }

        return $ret;
    }

    public function save($data, $id = "", $class = ""){
        $db = new Db();
        if(empty($class)){
            $class = get_called_class();
        }

        if($id){
            $sql = "UPDATE ".$class." SET ";

            foreach($data as $key => $val){
                $sql .= $key ." = '" .$val. "'";
            }

            $sql .= " WHERE ID = ".$id;
            $db->connection()->query($sql);
            return $id;
        }else{
            $values = "";
            $keys = "";
            foreach($data as $key => $val){
                if($key === array_key_last($data)){
                    $values .= "'". $val ."'";
                    $keys .= $key;
                }else{
                    $values .= "'". $val ."', ";
                    $keys .= $key . ", ";
                }

            }
            $sql = "INSERT INTO ".$class." (".$keys.") VALUES (".$values.")";
            $db->connection()->query($sql);
            $last_insert_id = $db->connection()->lastInsertId();
            return $last_insert_id;
        }
    }

    public function getById($id, $class = ""){
        if(empty($class)){
            $class = get_called_class();
        }
        $db =  new Db();
        return $db->connection()->query("SELECT * FROM ".$class." WHERE ID = ".$id)->fetch();
    }

    public function getByData($data, $order = "ASC", $by = "ID", $limit = "", $fetchType = "fetchAll", $class = ""){
        if(empty($class)){
            $class = get_called_class();
        }
        $db = new Db;
        $sql = "SELECT * FROM ".$class." WHERE ";
        foreach ($data as $key){
            if($key === array_key_last($data)){
                $sql .= $key . " AND ";
            }else{
                $sql .= $key." ";
            }
        }
        $sql .= "ORDER BY ".$by." ".$order;
        if($limit){
            $sql .= " LIMIT ".$limit;
        }

        switch($fetchType){
            case("fetchAll"):
                return $db->connection()->query($sql)->fetchAll();
            case("numRows"):
                return $db->connection()->query($sql)->rowCount();
            case("fetch"):
                return $db->connection()->query($sql)->fetch();
        }
        return false;
    }

    public function delete($id, $class = ""){
        if(empty($class)){
            $class = get_called_class();
        }
        $db = new Db;
        $sql = "DELETE FROM ".$class." WHERE ID = ".$id;
        $db->connection()->query($sql);
        return true;
    }
}