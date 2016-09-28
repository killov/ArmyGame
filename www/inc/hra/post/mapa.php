<?php
$x=$_GET["x"];

$data = [];
$bloky = json_decode($x);
$map = new mapa();
$mapa = $map->nacti($bloky);
$verze = $map->nacti_verze($bloky);
if($mapa){
    foreach($bloky as $b){
        if(isset($mapa[$b[0]][$b[1]])){
            foreach($mapa[$b[0]][$b[1]] as $m){
                $data[$b[0]][$b[1]][] = array(
                    "0" => $m["x"],
                    "1" => $m["y"],
                    "2" => $m["typ"],
                    "3" => $m["id"],
                    "4" => htmlspecialchars($m["jmeno"]),
                    "5" => htmlspecialchars($m["userjmeno"]),
                    "6" => $m["populace"],
                    "7" => $m["hrana"],
                    "8" => $m["stat"],
                    "9" => htmlspecialchars($m["statjmeno"]),
                    "10" => $m["hranice"],
                );
            }
            $data[$b[0]][$b[1]][] = $verze[$b[0]][$b[1]];
        }else{
            $data[$b[0]][$b[1]][] = [];
        }

    }
    echo json_encode($data);
}

?>