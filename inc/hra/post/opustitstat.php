<?php
    if($user->data["stat"]){
        $stat = new Stat();
        $stat->odeberclena($user->data["id"]);
    }
?>