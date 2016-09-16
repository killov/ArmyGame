<?php
if(!empty($_POST)){
    $p["jmeno"] = trim(strip_tags($_POST["jmeno"]));
    if(strlen($p["jmeno"]) > 0 and strlen($p["jmeno"]) <= 20){
        $mesto->nastav($mesto->data["id"],$p);
        $z = [1,htmlspecialchars($p["jmeno"])];
        echo json_encode($z);
    }else{
        echo json_encode([2]);
    }
}

?>