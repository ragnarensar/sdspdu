#!/usr/bin/php
<?php

require_once('TetraSDS/PDU.decode.php');
require_once('TetraSDS/STATUS.decode.php');

$longopts  = array(                     
    "pdu:",            // required value for a PDU value of a Tetra transport layer SDS
    "status:",         // required value for a STATUS value of a Tetra status SDS
    "output:",         // output format: json (--output json)
);                                                                                   
                                                                                     
$args=array();                                                                       
                                                                                     
foreach(getopt("", $longopts) as $key => $value) $args[$key] = $value;

if (array_key_exists('pdu', $args)) {
    $decoded = decode_PDU($args['pdu']);
} else if (array_key_exists('status', $args)) {
    $decoded = decode_STATUS($args['status']);
} else {
    fwrite(STDERR, "missing required option either --pdu or --status\n");
    exit(1);
}

if (array_key_exists('output', $args) && $args['output'] == 'json') 
{
    echo json_encode($decoded,JSON_UNESCAPED_UNICODE)."\n";
}
else
{
    foreach($decoded as $key => $value)
    {
        if (!(is_array($value))) 
        {
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
