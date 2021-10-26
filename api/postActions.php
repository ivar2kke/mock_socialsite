<?php
require_once("../autoloader.php");
session_start();


$data = "";
$action = "";
if(json_decode($_GET['data'])){
    $data = json_decode($_GET['data']);
    $action = $data->action;
}

if($action == "addPost"){
    $post_text = strip_tags($data->post_text);
    $posts = new Posts();
    $save_array = [
        "user_id" => $_SESSION['id'],
        "text" => $post_text
    ];
    $last_id = $posts->save($save_array);

    $curl = curl_init();
    $post_array = [
        "key" => "Xuec59yH4uLbQYrkAQJ8iOzzJONWMc4Pvuf0sD6E", //Demo key
        "secret" => "R0mDyyDj2ONdbx9RVsIi7OVn8I7NTdcL", //Demo secret
        "channelId" => 1,
        "message" => [
            "post_id" => $last_id,
            "type" => "new_post"
        ]
    ];

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://free3.piesocket.com/api/publish", // Cluster ID: demo
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($post_array),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));

    $resp = curl_exec($curl);
    curl_close($curl);

    $response['status'] = "success";
    echo json_encode($response);
}

if($action == "delete_post"){
    $id = $data->post_id;
    $posts = new Posts();
    $posts->delete($id);

    $curl = curl_init();
    $post_array = [
        "key" => "Xuec59yH4uLbQYrkAQJ8iOzzJONWMc4Pvuf0sD6E", //Demo key
        "secret" => "R0mDyyDj2ONdbx9RVsIi7OVn8I7NTdcL", //Demo secret
        "channelId" => 1,
        "message" => [
            "post_id" => $id,
            "type" => "delete_post"
        ]
    ];

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://free3.piesocket.com/api/publish", // Cluster ID: demo
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($post_array),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));

    $resp = curl_exec($curl);
    curl_close($curl);

    $response['status'] = "success";
    echo json_encode($response);
}

if($action == "like_post"){
    $uid = $_SESSION['id'];
    $pid = $data->post_id;

    $likes = new PostLikes();
    $id = $likes->userHasLiked($uid, $pid);

    $posts = new Posts();
    if(!$id){
        $likes->save(["user_id" => $uid, "post_id" => $pid]);
        $response['status'] = "success";
    }else{
        $likes->delete($id['ID']);
        $response['status'] = "success";
    }

    $returnhtml = $posts->drawLikeCounter($pid, $uid);
    $response['htmlOutput'] = $returnhtml;

    echo json_encode($response);
}

if($action == "new_comment"){
    $text = strip_tags($data->text);
    $posts = new Posts();
    $save_array = [
        "user_id" => $_SESSION['id'],
        "text" => $text,
        "parent_id" => $data->post_id,
        "type" => "comment"
    ];
    $last_id = $posts->save($save_array);

    $curl = curl_init();
    $post_array = [
        "key" => "Xuec59yH4uLbQYrkAQJ8iOzzJONWMc4Pvuf0sD6E", //Demo key
        "secret" => "R0mDyyDj2ONdbx9RVsIi7OVn8I7NTdcL", //Demo secret
        "channelId" => 1,
        "message" => [
            "post_id" => $data->post_id,
            "type" => "add_comment"
        ]
    ];

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://free3.piesocket.com/api/publish", // Cluster ID: demo
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($post_array),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));

    $resp = curl_exec($curl);
    curl_close($curl);

    $response['status'] = "success";
    echo json_encode($response);
}
