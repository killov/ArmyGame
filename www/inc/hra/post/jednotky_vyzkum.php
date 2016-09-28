<?php
$urovne = $mesto->jednotky_vyzkum_urovne();
if(isset($urovne[$_POST["jid"]])){

    if($mesto->jednotky_vyzkum_pozadavky($_POST["jid"],$user) and !$urovne[$_POST["jid"]] and !$mesto->data["v".$_POST["jid"]]){
        
        
        $surovina1 = $hodnoty["jednotky"][$_POST["jid"]]["vyzkum_surovina1"];
        $surovina2 = $hodnoty["jednotky"][$_POST["jid"]]["vyzkum_surovina2"];
        $surovina3 = $hodnoty["jednotky"][$_POST["jid"]]["vyzkum_surovina3"];
        $surovina4 = $hodnoty["jednotky"][$_POST["jid"]]["vyzkum_surovina4"];
        
        if($surovina1 <= $mesto->surovina1 && $surovina2 <= $mesto->surovina2 && $surovina3 <= $mesto->surovina3 && $surovina4 <= $mesto->surovina4){
            $mesto->suroviny_refresh(time());
            $mesto->suroviny_odecti($surovina1,$surovina2,$surovina3,$surovina4);
            
            if(in_array($_POST["jid"],[1,2,3,4])){
                $b = 1;
                $d = $mesto->data["b10"];
            }else{
                $b = 2;
                $d = $mesto->data["b11"];
            }
            $mesto->jednotky_vyzkum_postav($mesto->jednotky_vyzkum_cas($_POST["jid"],$d),$_POST["jid"],$b);
            echo json_encode([1]);
        }
    }		
}
?>