<?php
if(!empty($_POST)){
    if(!$user->data["stat"]){
        $errors = [0];
        if(empty($_POST["jmeno"])){
            $errors[0] = 1;
        }
        elseif(strlen(trim($_POST["jmeno"])) > 20){
            $errors[0] = 2;
        }
        if($errors == [0]){
            $stat = new stat();
            $id = $stat->vytvor(trim(($_POST["jmeno"])),$user->data["id"]);
        }
        echo json_encode([$errors[0],$id]);
    }
}
?>