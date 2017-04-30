<?php

    if(isset($_POST["x"]) && isset($_POST["y"])){
        $cesta = new pohyb();
        
        $c = $cesta->cesta($mesto->data["x"], $mesto->data["y"], intval($_POST["x"]), intval($_POST["y"]));
    
        echo json_encode($c);
    }