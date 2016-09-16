<?php
$urovne = $user->vyzkum_urovne();
if(isset($urovne[$_POST["vid"]])){
    if($mesto->budova_pozadavky($_POST["vid"],$user) && $hodnoty["vyzkum"][$_POST["vid"]]["maximum"] >= $urovne[$_POST["vid"]]){
        $uroven = $urovne[$_POST["vid"]];
        $cena = $user->vyzkum_cena($_POST["vid"],$uroven);

        if($cena <= $user->penize){
            $user->refresh();
            $user->penize_odecti($cena);
            $user->vyzkum_postav($user->vyzkum_cas($mesto->data["b7"],$_POST["vid"],$urovne[$_POST["vid"]]),$_POST["vid"]);
            echo json_encode([1]);
        }
    }		
}
?>