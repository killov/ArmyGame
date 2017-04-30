<?php
if(isset($_POST["jmeno"]) and isset($_POST["heslo"])){
    $errors = [0];
    $user = new user();
    $userid = $user->login($_POST["jmeno"],$_POST["heslo"]);
    if(empty($_POST["jmeno"])){
        $errors[0] = 1;
    }
    elseif(!$userid){
        $errors[0] = 2;
    }
    if($errors == [0]){
        $_SESSION["userid"] = $userid;
        setcookie("ag_chat", "", time() - 3600,"/");
        setcookie("ag_chatmin", "", time() - 3600,"/");
    }
    echo json_encode($errors);
}
?>