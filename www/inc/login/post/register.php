<?php
if(!empty($_POST)){
    $user = new user();
    $errors = [0,0,0,0];
    if(empty($_POST["jmeno"])){
        $errors[0] = 1;
    }
    elseif(strlen($_POST["jmeno"]) > 20){
        $errors[0] = 2;
    }
    elseif($user->exist(trim($_POST["jmeno"]),"jmeno")){
        $errors[0] = 3;
    }
    if(empty($_POST["email"])){
        $errors[1] = 1;
    }	
    elseif(strlen($_POST["email"]) > 30){
        $errors[1] = 2;
    }
    elseif(!$user->check_email($_POST["email"])){
        $errors[1] = 3;
    }
    elseif($user->exist($_POST["email"],"email")){
        $errors[1] = 4;
    }
    if(empty($_POST["heslo"])){
        $errors[2] = 1;
    }
    elseif(strlen($_POST["heslo"]) < 4){
        $errors[2] = 2;
    }
    if(empty($_POST["heslo_znovu"])){
        $errors[3] = 1;
    }
    elseif($_POST["heslo"] != $_POST["heslo_znovu"]){
        $errors[3] = 2;
    }
    if($errors == [0,0,0,0]){
        $id = $user->registruj(trim($_POST["jmeno"]),$_POST["email"],$_POST["heslo"]);
        $_SESSION["userid"] = $id;
    }
    echo json_encode($errors);
}
?>