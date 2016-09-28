<?php
    if($user->data["stat"]){
        $stat = new stat();
        $stat->odeberclena($user->data["id"]);
    }
?>