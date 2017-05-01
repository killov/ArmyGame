<?php
if(!empty($_POST)){
    if(!$user->data["stat"]){
        if(isset($_POST["id"])){
            $stat = new Stat();
            if($pozvanka = $stat->pozvanka($_POST["id"],$user->data["id"])){
                $stat->pridejclena($user->data["id"], $pozvanka["stat"], $pozvanka["statjmeno"], 0);
                $stat->pozvanka_zrusit($pozvanka["id"], $pozvanka["stat"]);
                echo json_encode([$pozvanka["stat"]]);
            }
        }       
    }
}
?>