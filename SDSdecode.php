#!/usr/bin/php
<?php

require_once('TetraSDS/PDU.decode.php');

$longopts  = array(                     
    "pdu:",             // Required value PDU of a Tetra Transport Layer SDS
    "output:",         // output format: json (--output json)
);                                                                                   
                                                                                     
$args=array();                                                                       
                                                                                     
foreach(getopt("", $longopts) as $key => $value) $args[$key] = $value;

if (!(array_key_exists('pdu', $args))) {

    fwrite(STDERR, "missing required option --pdu\n");
    exit(1);

}

$decoded = decode_PDU($args['pdu']);

if (array_key_exists('output', $args) && $args['output'] == 'json') {

    $JSON_PDU = json_encode($decoded,JSON_UNESCAPED_UNICODE);
    echo $JSON_PDU."\n";

}
else
{
    foreach($decoded as $key => $value)
    {
        if (!(is_array($value))) {
            echo "PDUelements[\"".$key."\"]=\"".$value."\"\n";
        }
        else
        {
            foreach($value as $key2 => $value2)
            {
                echo "PDUelements[\"".$key."_".$key2."\"]=\"".$value2."\"\n";
            }
        }
    }
}
?>
