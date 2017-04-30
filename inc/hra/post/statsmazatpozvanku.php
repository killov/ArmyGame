<?php
if(!empty($_POST)){
    if($user->data["sp_all"] == 1){
        if(isset($_POST["id"])){
            $stat = new stat();
            $stat->pozvanka_zrusit($_POST["id"], $user->data["stat"]);
            echo json_encode([1]);
        }
       
        
        
    }
}
?>