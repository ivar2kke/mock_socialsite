<?php
require_once("../autoloader.php");


$data = "";
$action = "";
if(json_decode($_GET['data'])){
    $data = json_decode($_GET['data']);
    $action = $data->action;
}

if($action == 'register'){
    $user = new Users();
    $errors = [];
    if($user->getByData(["email = '".$data->email."'"], "ASC", "ID", 1)){
        array_push($errors, ["type" => "danger", "text" => "E-post on juba kasutusel!"]);
    }else{
        $pw = password_hash($data->pw, PASSWORD_DEFAULT);
        $saveData = [
            "first_name" => $data->firstname,
            "last_name" => $data->lastname,
            "email" => $data->email,
            "password" => $pw,
            "gender" => $data->gender
        ];
        $user->save($saveData);
        array_push($errors, ["type" => "success", "text" => "Kasutaja edukalt registreeritud!"]);
    }

    echo json_encode($errors);

}

if($action == 'login'){
    $user = new Users();
    $errors = [];
    if($rows = $user->getByData(["email = '".$data->email."'"], "ASC", "ID", 1, "fetch")){
        $inputPw = $data->pw;
        if(!password_verify($inputPw, $rows['password'])){
            array_push($errors, ["type" => "danger", "text" => "Paroolid ei klapi!"]);
            $errors['status'] = "error";
        }else{
            session_start();
            $_SESSION['id'] = $rows['ID'];
            $_SESSION['user'] = [
                "email" => $rows['email'],
                "fullname" => $rows['first_name'] . " " . $rows['last_name']
                ];
            date_default_timezone_set('Europe/Tallinn');
            $user->save(["last_login" => date('Y-m-d H:i:s')], $rows['ID']);
            array_push($errors, ["type" => "success", "text" => "Tere ".$_SESSION['user']["fullname"]."! Olete edukalt sisse logitud!"]);
            $errors['status'] = "success";
        }
    }else{
        array_push($errors, ["type" => "danger", "text" => "Sellise e-postiga kasutajat ei eksisteeri!"]);
        $errors['status'] = "error";
    }

    echo json_encode($errors);
}

if($action == 'logout'){
    session_start();
    $response = [];
    unset($_SESSION['user']);
    unset($_SESSION['id']);
    $response['status'] = "success";
    echo json_encode($response);
}
