<?php

if(isset($_POST["jednotka"]) && isset($_POST["pocet"]) && in_array($_POST["jednotka"],range(1,8)) && is_numeric($_POST["pocet"])){

    if($mesto->data["v".$_POST["jednotka"]]){
        $pocet = intval($_POST["pocet"]);
        $max = $mesto->jednotky_max($_POST["jednotka"]);
        if($pocet > $max){
            $pocet = $max;
        }
        if($pocet > 0){
            $surovina1 = $hodnoty["jednotky"][$_POST["jednotka"]]["surovina1"]*$pocet;
            $surovina2 = $hodnoty["jednotky"][$_POST["jednotka"]]["surovina2"]*$pocet;
            $surovina3 = $hodnoty["jednotky"][$_POST["jednotka"]]["surovina3"]*$pocet;
            $surovina4 = $hodnoty["jednotky"][$_POST["jednotka"]]["surovina4"]*$pocet;
            $mesto->suroviny_refresh(time());
            $mesto->suroviny_odecti($surovina1,$surovina2,$surovina3,$surovina4);
            if(in_array($_POST["jednotka"],[1,2,3,4])){
                $b = 1;
                $d = $mesto->data["b10"];
            }else{
                $b = 2;
                $d = $mesto->data["b11"];
            }
            
            $mesto->jednotky_postav($mesto->jednotky_cas($_POST["jednotka"],$d),$_POST["jednotka"],$b,$pocet);
            echo json_encode([1]);
        }
    }	
}
?>