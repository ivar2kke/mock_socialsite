<?php
require_once("../autoloader.php");
session_start();


$data = "";
$action = "";
if(json_decode($_GET['data'])){
    $data = json_decode($_GET['data']);
    $action = $data->action;
}

if($action == "load_posts"){
    $posts = new Posts();
    $drawhtml = "";
    if(isset($data->post_id)){
        $post = $posts->getPost($data->post_id);
        $drawhtml = $posts->drawPost($post['user_name'], $post['text'], $post['added_date'], $post['ID'], $post['uid']);
    }else{
        $post = $posts->getPostsForUser($_SESSION['id']);

        foreach($post as $p){
            $drawhtml .= $posts->drawPost($p['user_name'], $p['text'], $p['added_date'], $p['ID'], $p['uid']);
        }
    }

    $response = ["html" => $drawhtml, "status" => "success"];
    echo json_encode($response);
}

if($action == "load_comments"){
    $posts = new Posts();
    $drawhtml = $posts->drawComments($data->post_id);
    $comments = $posts->drawCommentCounter($data->post_id);

    $response = ["html" => $drawhtml, "comments" => $comments, "status" => "success"];
    echo json_encode($response);
}