<?php
if(isset($p[1])){
    $cesta = "inc/hra/pages/budovy/".$p[1].".php";
    if(file_exists($cesta)){
        if($mesto->data["b".$p[1]]){
            echo "<h2>".$lang_budova[$p[1]-1]." (".$lang[31].": ".$mesto->data["b".$p[1]].")"
                    . "<i class=\"faq\" onclick=\"faq_load('budovy&b=".$p[1]."');\">?</i></h2>";
            include $cesta;
        }
    }
}
?>