<?php

    function sectimatrix($A,$B){
        $C = [];
        foreach($A as $i => $a){
            foreach($B as $j => $b){
                $C[$i][$j] = $a+$b;
            }
        }
        return $C;
    }
    
    print_r(sectimatrix([[1,2],[3,4]], [[1,2],[3,4]]));
?>