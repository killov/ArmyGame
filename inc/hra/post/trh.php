<?php
if(!empty($_POST) && isset($_POST["typ"])){
    $errors = [0];
    if($_POST["typ"] == 0){
        $soucet = 0;
        if(isset($_POST["surovina1"])){
            $surovina1 = abs(intval($_POST["surovina1"]));
            $soucet += $surovina1;
            if($surovina1 > $mesto->surovina1){
                $errors[0] = 1;
            }
        }
        if(isset($_POST["surovina2"])){
            $surovina2 = abs(intval($_POST["surovina2"]));
            $soucet += $surovina2;
            if($surovina2 > $mesto->surovina2){
                $errors[0] = 1;
            }
        }
        if(isset($_POST["surovina3"])){
            $surovina3 = abs(intval($_POST["surovina3"]));
            $soucet += $surovina3;
            if($surovina3 > $mesto->surovina3){
                $errors[0] = 1;
            }
        }
        if(isset($_POST["surovina4"])){
            $surovina4 = abs(intval($_POST["surovina4"]));
            $soucet += $surovina4;
            if($surovina4 > $mesto->surovina4){
                $errors[0] = 1;
            }
        }
        $obchodnici = ceil($soucet/$hodnoty["trziste"]["nosnost"]);
        if($mesto->obchodnici_dostupni($mesto->data["b9"]) < $obchodnici){
            $errors[0] = 2;
        }
        if($soucet == 0){
            $errors[0] = 4;
        }
        if($errors == [0]){
            $penize = floor($soucet*$hodnoty["trziste"]["pomer"]);
            $mesto->suroviny_refresh(time());
            $mesto->suroviny_odecti($surovina1,$surovina2,$surovina3,$surovina4);
            $mesto->obchod_odesli($penize,$obchodnici);
        }
    }else{
        $soucet = 0;
        if(isset($_POST["surovina1"])){
            $surovina1 = abs(intval($_POST["surovina1"]));
            $soucet += $surovina1;
        }
        if(isset($_POST["surovina2"])){
            $surovina2 = abs(intval($_POST["surovina2"]));
            $soucet += $surovina2;
        }
        if(isset($_POST["surovina3"])){
            $surovina3 = abs(intval($_POST["surovina3"]));
            $soucet += $surovina3;
        }
        if(isset($_POST["surovina4"])){
            $surovina4 = abs(intval($_POST["surovina4"]));
            $soucet += $surovina4;
        }
        $obchodnici = ceil($soucet/$hodnoty["trziste"]["nosnost"]);
        if($mesto->obchodnici_dostupni($mesto->data["b9"]) < $obchodnici){
            $errors[0] = 2;
        }
        $penize = floor($soucet/$hodnoty["trziste"]["pomer"]);
        if($penize > $user->penize){
            $errors[0] = 3;
        }
        if($soucet == 0){
            $errors[0] = 4;
        }
        
        
        if($errors == [0]){
            $user->refresh();
            $user->penize_odecti($penize);
            $mesto->obchod_prijmi($surovina1,$surovina2,$surovina3,$surovina4,$obchodnici);
        }
    }

    
    echo json_encode([$errors]);
}


?>

