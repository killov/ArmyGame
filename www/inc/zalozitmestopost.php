<?php
if(!empty($_POST)){
    $errors = [0];
    if(empty($_POST["jmeno"])){
        $errors[0] = 1;
    }
    elseif(strlen(trim($_POST["jmeno"])) > 20){
        $errors[0] = 2;
    }

    if($errors == [0]){
        $mesto->vytvor_mesto(trim($_POST["jmeno"]),$user->data["id"],$_POST["smer"],$user->data["jmeno"]);
        $user->refresh();
    }
    echo json_encode($errors);
}
?>