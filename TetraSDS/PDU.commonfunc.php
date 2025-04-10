<?php

// converting a hexadecimal string into a binary string
// each hex digit is converted into a full 4 bits
function hextobin($hex) {
    $sbin="";
        for($i=0;$i<strlen($hex);$i+=1)
        {   
            $j = 0;	
            $c = decbin(hexdec(substr($hex,$i,1)));
            $q = 4-strlen($c);
            if ($q > 0) { while($j<$q) { $c="0".$c; $j+=1; } }
                $sbin.=$c;
        }
        return $sbin;
}

// converting a binary string into a hexadecimal string
// each 4 bits are converted into a hex digit
function bintohex($bin) {
	$shex="";
        for($i=0;$i<strlen($bin);$i+=4)
        {   
            $b = dechex(bindec(substr($bin,$i,4)));
            $shex.=$b;
        }
        return $shex;
}
?>
