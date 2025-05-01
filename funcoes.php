<?php
    function CriaID(){
        $nus = ["0","1","2","3","4","5","6","7","8","9"];
        $resposta = "";
        for($x = 0; count($nus) > $x ; $x++){
            $n = array_rand($nus);
            $resposta .= $nus[$n];
        }
        return $resposta;
    }
    
?>