<?php
$urovne = $mesto->budova_urovne();
if(isset($urovne[$_POST["bid"]])){
    if($mesto->budova_pozadavky($_POST["bid"],$user) and $hodnoty["budovy"][$_POST["bid"]]["maximum"] >= $urovne[$_POST["bid"]]){
        $uroven = $urovne[$_POST["bid"]];
        $surovina1 = $mesto->budova_cena("surovina1",$_POST["bid"],$uroven);
        $surovina2 = $mesto->budova_cena("surovina2",$_POST["bid"],$uroven);
        $surovina3 = $mesto->budova_cena("surovina3",$_POST["bid"],$uroven);
        $surovina4 = $mesto->budova_cena("surovina4",$_POST["bid"],$uroven);

        $surovina12 = $mesto->surovina1;
        $surovina22 = $mesto->surovina2;
        $surovina32 = $mesto->surovina3;
        $surovina42 = $mesto->surovina4;

        if($surovina1 <= $surovina12 and
            $surovina2 <= $surovina22 and
            $surovina3 <= $surovina32 and
            $surovina4 <= $surovina42){
            $mesto->suroviny_refresh(time());
            $mesto->suroviny_odecti($surovina1,$surovina2,$surovina3,$surovina4);
            $mesto->budova_postav($mesto->budova_cas($mesto->data["b1"],$_POST["bid"],$urovne[$_POST["bid"]]),$_POST["bid"]);

            echo json_encode([1]);
        }
    }		
}
?>