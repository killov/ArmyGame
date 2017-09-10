<?php

$id = $_GET["id"];
$source = isset($_GET["source"]) ? $_GET["source"] : 0;

if($source == 0){
    $x1 = $mesto->data["x"];
    $y1 = $mesto->data["y"];
}else{
    $p = new Podpory();
    $podpora = $p->nacti($source, $mesto->data["id"]);
    if(!$podpora || $podpora["kde"] == $id){
        exit;
    }
    
    $m = new Mesto();
    $m->nacti($podpora["kde"]);
    $x1 = $m->data["x"];
    $y1 = $m->data["y"];
}


$m = new Mesto();



if($m->nacti($id)){
    $return = [];
    $x2 = $m->data["x"];
    $y2 = $m->data["y"];

    $pohyb = new Pohyb();
    Tracy\Debugger::barDump($m->data);
    
    
    $return["cesta"] = $pohyb->cesta(intval($x1), intval($y1), intval($x2), intval($y2));
    
    $distance = $return["cesta"][count($return["cesta"])-1][2];
    
    $return["city"] = [
        "x" => intval($x2),
        "y" => intval($y2),
        "id" => intval($m->data["id"]),
        "jmeno" => htmlspecialchars($m->data["jmeno"]),
        "user" => intval($m->data["user"]),
        "userjmeno" => htmlspecialchars($m->data["userjmeno"]),
        "stat" => intval($m->data["stat"]),
        "statjmeno" => htmlspecialchars($m->data["statjmeno"]),
        "distance" => $distance
    ];
    
    echo json_encode($return);
}
