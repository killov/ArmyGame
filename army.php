<?php
echo '
<style>

body{
	background: #ddd;
}

tr{
	height: 22px;
}

td{
	width: 22px;
}

.nothing{
	background: #33CC33;
}

.area{
	background: #FF0000;
}



</style>
';

echo '<h1> ARMY GAME v2 - vilage area testing </h1>';


$x = 8;
$y = 10;

$pol = 5;

$opakování = 1000;

echo 'souradnice<br> x:' . $x . ' y:' . $y . '<br>
poloměr: '. $pol .'
<br><br>';

$start = time();

// tohle bude funkce
for($opak = $opakování; $opak>0; $opak--){

	$kon = $x + $pol;
	$kam = true;

	$krok = 1;
	$out = [];


	for($zac = $x - $pol;$zac <= $kon ; $zac++){
		$toX = $zac;
		for($i = 0; $i < $krok; $i++){
			$odecet = $krok - 1;
			for($neco = $krok; $neco> -$krok+1; $neco--){
				$tosave = array($toX, $y - $neco+1);
				if(!in_array($tosave, $out)){
					$out[] = $tosave;
				}
			}
		}
		if($kam){
			$krok++;
			if($krok>$pol){
				$zac;
				$kam = false;
			}
		} else{
			$krok--;
		}	
	}
}

$konec = time();

echo 'Výpočet souřadnic pro '.$opakování.' vesnic zabral ' . ($konec - $start) . ' sekund.<br><br>';

$sx = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
$sy = [15,14,13,12,11,10,9,8,7,6,5,4,3,2,1,0];
echo '<table>';
foreach ($sy as $souzy) {
	echo '<tr>';
		echo '<td>'.$souzy.'</td>';
		foreach ($sx as $souzx) {
			if(array($x, $y) == array($souzx, $souzy)){
				echo '<td class=""></td>';
			} elseif(in_array(array($souzx, $souzy), $out)){
				echo '<td class="area"></td>';
			} else{
				echo '<td class="nothing"></td>';
			}
		}
	echo '</tr>';
}
echo '<tr>';
	echo '<td></td>';
foreach ($sx as $souzx) {
	echo '<td>'.$souzx.'</td>';
}
echo '</tr>';
echo '</table>';



