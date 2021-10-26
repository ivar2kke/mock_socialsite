<?php

class PostLikes extends Model
{
    public function userHasLiked($uid, $pid){
        $db = new Db;
        $class = __CLASS__;
        $count = $db->connection()->query("SELECT ID FROM ".$class." WHERE user_id = ".$uid." AND post_id = ".$pid)->fetch();
        if($count){
            return $count;
        }
        return false;
    }
}