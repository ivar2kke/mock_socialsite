<?php

class Posts extends Model
{
    public function getPost($id){
        $db = new Db();
        $rows = $db->connection()->query("SELECT CONCAT_WS(' ', u.first_name, u.last_name) AS user_name, p.text, p.added_date, p.ID, u.ID as uid FROM Posts p
            LEFT JOIN Users u ON p.user_id = u.ID
            WHERE p.ID = ".$id);
        return $rows->fetch();
    }

    public function getPostsForUser($uid){
        $db = new Db;
        $res = [];
        $rows = $db->connection()->query("SELECT CONCAT_WS(' ', u.first_name, u.last_name) AS user_name, p.text, p.added_date, p.ID, u.ID as uid FROM Posts p
            LEFT JOIN Users u ON p.user_id = u.ID
            WHERE type = 'post'
            ORDER BY added_date DESC");
        foreach($rows as $r){
            array_push($res, $r);
        }

        return $res;
    }

    public function drawPost($name, $text, $added, $pid, $uid){
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $text, $match);
        $cnt = count($match);
        $queryvalues = ['v' => ""];
        foreach($match as $ma){
            foreach($ma as $m){
                if (filter_var($m, FILTER_VALIDATE_URL)) {
                    $text = str_replace($m, "<a href='$m' target='_blank'>$m</a>", $text);
                    $urlarray = parse_url($m);
                    if($urlarray['host'] == "www.youtube.com"){
                        parse_str($urlarray['query'], $queryvalues);
                    }
                }
            }
        }


        $ret = '<div class="row content-box bg-dark posts" id="post-id-'.$pid.'" style="margin-right:10px; padding-bottom:0; position:relative;">';

        if($uid == $_SESSION['id']){
            $ret .= '<div class="dropdown post-settings position-relative">
                        <a class="d-flex align-items-center text-white text-decoration-none position-absolute end-0" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false" style="margin-top:-15px;">
                            <i class="bi bi-three-dots"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item edit-post" data-id="'.$pid.'">Muuda postitust</a></li>
                            <li><a class="dropdown-item post-access-settings" data-id="'.$pid.'">Nähtavuse seaded</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" id="delete-post" data-id="'.$pid.'">Kustuta postitus</a></li>
                        </ul>
                    </div>';
        }

        $ret.= '';

        $ret .=   ' <div class="row post-header" style="height:70px;">
                            <div class="col-12">
                                <ul class="list-inline">
                                    <li class="list-inline-item align-middle"><img src="img/profile_placeholder.png" alt="" width="48" height="48" class="rounded-circle me-2"></li>
                                    <li class="list-inline-item align-middle">
                                        <h1 class="h6">'.$name.'</h1>
                                        <p class="small text-muted" style="margin:0;">'.$added.'</p>
                                    </li>
                                </ul>
                            </div>
                    </div>
                    <hr>
                    <div class="row post-content">
                        <div class="col-12">
                            <h2 class="h6 post-text">'.$text.'</h2>
                            '. $this->addThumbnailIfExists($queryvalues['v']) .'
                        </div>
                    </div>
                    <div class="row post-actions">
                        <div class="row display-data align-middle">
                            <div class="col-2">
                                <ul class="list-inline like-counter text-muted text-start" id="like-counter" data-id="'.$pid.'" style="margin-bottom:0;">';
        $ret .= $this->drawLikeCounter($pid, $_SESSION['id']);
      $ret.=                    '</ul>
                            </div>
                            <div class="col-10" style="padding-right:0;">
                                <ul class="list-inline other-counter text-end">
                                    <li class="list-inline-item align-middle"><small class="text-muted" id="comment-counter" data-id="'.$pid.'">'.$this->drawCommentCounter($pid).'</small></li>
                                    <li class="list-inline-item align-middle"><small class="text-muted" id="share-counter">1 jagamine</small></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row action-buttons">
                            <div class="col-12">

                            </div>
                        </div>
                    </div>
                    <div class="row" id="display-comments-'.$pid.'" style="padding-bottom:10px; display:none;">
                        '.$this->drawComments($pid).'
                    </div>
                </div>';

        return $ret;
    }

    public function drawLikeCounter($pid, $uid){
        $likes = new PostLikes();
        $hasliked = $likes->userHasLiked($uid, $pid);
        $ret = "";
        $likesAmount = $likes->getByData(["post_id = ".$pid], "ASC", "ID", "", "numRows");
        if($hasliked){
            $ret =  '<li class="list-inline-item align-middle" style="color:rgba(0, 123, 255, 1);"><i class="bi bi-hand-thumbs-up-fill like-button" ></i></li>';
        }else{
            $ret .=  '<li class="list-inline-item align-middle"><i class="bi bi-hand-thumbs-up-fill like-button" ></i></li>';
        }
        $ret .='<li class="list-inline-item align-middle"><small>'.$likesAmount.'</small></li>';

        return $ret;
    }

    public function addThumbnailIfExists($video_id){
        if(!empty($video_id)){
            $ret = '<div class="row thumbnail-container">
                        <div class="col-12">
                            <div class="row">
                            <object width="425" height="350" data="http://www.youtube.com/v/'.$video_id.'" type="application/x-shockwave-flash"><param name="src" value="http://www.youtube.com/v/'.$video_id.'" /></object>
                           
                            </div>
                        </div>
                    </div>';
            return $ret;
        }
        return "";
    }

    public function drawCommentCounter($pid){
        $comments = $this->getComments($pid, true);
        return $comments.' kommentaari';
    }

    public function getComments($parent_id, $count = false){
        $db = new Db();
        $res = [];
        $commentsQuery = $db->connection()->query("SELECT p.ID, p.text, p.added_date, CONCAT_WS(' ', u.first_name, u.last_name) AS user_name
            FROM Posts p 
            LEFT JOIN Users u ON p.user_id = u.ID
            WHERE p.parent_id = ".$parent_id." AND p.type = 'comment' ORDER BY p.added_date ASC LIMIT 10");
        if($count){
            return $commentsQuery->rowCount();
        }

        $comments = $commentsQuery->fetchAll();
        foreach($comments as $comment){
            array_push($res, $comment);
        }

        return $res;
    }

    public function drawComments($pid){
        $comments = $this->getComments($pid);
        $ret = '
                <hr>
                    <div class="col-12">
                    <div class="row new-comment" style="margin-bottom:20px;">
                        <form class="form-floating">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm comment-text" name="comment_text" id="comment-text-'.$pid.'" data-id="'.$pid.'" placeholder="Uus kommentaar...">
                                <span><button class="btn btn-primary" id="new-comment-post-'.$pid.'" data-id="'.$pid.'" disabled>Kommenteeri</button></span>
                            </div>
                        </form>
                    </div>';
        foreach($comments as $comment){
            $ret .= '<div class="row post-comments" style="margin-left:20px;">
                        <div class="col-8">
                            <ul class="list-inline comment" data-id="">
                                <li class="list-inline-item align-middle"><img src="img/profile_placeholder.png" alt="" width="32" height="32" class="rounded-circle me-2"></li>
                                <li class="list-inline-item align-middle">
                                    <div class="row">
                                         <small>'.$comment["user_name"].'</small>
                                    </div>
                                    <div class="row">
                                        <small>'.$comment["text"].'</small>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <ul class="list-inline comment-footer text-end">
                                    <li class="list-inline-item text-muted align-middle"><a style="font-size:12px;">Meeldib</a></li>
                                     <li class="list-inline-item text-muted align-middle"><small style="font-size:12px;">'.$comment["added_date"].'</small></li>
                                </ul>
                            </div>
                        </div>
                    </div>';
        }
        $ret .= '
                </div>
        ';
        return $ret;
    }

    public function delete($id, $class = ""){
        $db = new Db;
        $db->connection()->query("DELETE FROM Posts WHERE ID = ".$id." OR (parent_id = ".$id." AND type = 'comment')");
        $db->connection()->query("DELETE FROM PostLikes WHERE post_id = ".$id);
        return true;
    }
}