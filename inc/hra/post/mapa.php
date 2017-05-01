<?php
$x=$_GET["x"];

$data = [];
$bloky = json_decode($x);
$map = new Mapa();
$mapa = $map->nacti($bloky);
$verze = $map->nacti_verze($bloky);
$staty = [];
if($mapa){
    foreach($bloky as $b){
        if(isset($mapa[$b[0]][$b[1]])){
            foreach($mapa[$b[0]][$b[1]] as $m){
                $staty[$m["stat"]] = $m["stat"];
                $a = [
                    intval($m["x"]),
                    intval($m["y"]),
                    intval($m["typ"]),
                    intval($m["id"]),
                    intval($m["hrana"]),
                    intval($m["stat"]),
                    intval($m["hranice"]),
                ];
                if($m["typ"] == 1){
                    $a[] = intval($m["populace"]);
                    $a[] = htmlspecialchars($m["jmeno"]);
                    $a[] = htmlspecialchars($m["userjmeno"]);
                }  
                $data[$b[0]][$b[1]][] = $a;
            }
            $data[$b[0]][$b[1]][] = $verze[$b[0]][$b[1]];
        }else{
            $data[$b[0]][$b[1]][] = [];
        }

    }
    
    echo json_encode([$data,$map->nactistatyjmena($staty)]);
}

?>