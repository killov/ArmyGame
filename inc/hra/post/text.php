<?php

$map = new Mapa();
for($x=0;$x<20;$x++){
    for($y=0;$y<10;$y++){
        $bloky[] = [$x,$y];
    }
}
$times = time();
$mapa = $map->nacti($bloky);

