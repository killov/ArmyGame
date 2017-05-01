<?php
if(!empty($_POST)){
    if($user->data["sp_all"] == 1){
        $stat = new Stat();
        $us = new User();
        $errors = [0];
        if(empty($_POST["jmeno"])){
            $errors[0] = 1;
        }
        elseif(!$id = $us->nactijmeno($_POST["jmeno"])){
            $errors[0] = 2;
        }
        elseif($stat->pozvat_exist($user->data["stat"],$id)){
            $errors[0] = 3;
        }
        elseif($us->data["stat"] == $user->data["stat"]){
            $errors[0] = 5;
        }
        elseif(!$stat->pozvat_vzdalenost($user->data["stat"], $id, 50)){
            $errors[0] = 4;
        }
        if($errors == [0]){
            $stat = new Stat();
            $stat->pozvat($id, $us->data["jmeno"], $user->data["stat"], $user->data["statjmeno"]);
        }
        echo json_encode($errors);
    }
}
?>