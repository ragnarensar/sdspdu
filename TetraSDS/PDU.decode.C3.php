<?php

function get_CallOutPDU ($PDUbin, $StringPos)
{
    // TCCA
    // TTR 001-21
    // TETRA Interoperability Profile (TIP); Part 21: Callout
    // TTR001-21_v211_Callout.pdf
    // Simple Callout Service
    $ret=array();
    foreach(get_tbd2($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_MsgRef($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_tbd4($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_tbd5h($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

function get_tbd2 ($PDUbin, $StringPos)
{
    // tbd2 8 Bit [0-255]
    // to be defined Byte 2 of PDU - no idea about
    // free text answers has "04", alerts and fix text answers has "00"
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=8;
    $ret["COtbd2"] = array();
           $ret["COtbd2"]["value"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    switch($ret["COtbd2"]["value"]) {
        case 0:            // alert or fix-text answer
          $ret["COtbd2"]["name"] = "alert or fix-text answer";  
          break;
        case 4:            // free-text answer
          $ret["COtbd2"]["name"] = "free-text answer";
          break;
        default:
          $ret["COtbd2"]["name"] = "unhandled (value=".$ret["COtbd2"]["value"].")";
    }    
    return $ret;
}

function get_tbd4 ($PDUbin, $StringPos)
{
    // tbd4 8 Bit [0-255]
    // to be defined, Byte 4 of PDU - no idea about
    // all messages have "01" to now
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=8;
    $ret["COtbd4"] = array();
    $ret["COtbd4"]["value"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    switch($ret["COtbd4"]["value"]) {
        case 1:            // no idea
          $ret["COtbd4"]["name"] = "to be defined";  
          break;
        default:
          $ret["COtbd4"]["name"] = "unhandled (value=".$ret["COtbd4"]["value"].")";
    }    
    return $ret;
}

function get_tbd5h ($PDUbin, $StringPos)
{
    // tbd5h 4 Bit [0-15]
    // to be defined, Bits 7-4 of Byte 5 of PDU - no idea about
    // "1" seems to be an alert, "3" seems to be an answer
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=4;
    $ret["COtbd5h"] = array();
    $ret["COtbd5h"]["value"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    switch($ret["COtbd5h"]["value"]) {
        case 1:            // seems to be an alert
          $ret["COtbd5h"]["name"] = "seems to be an alert";  
          foreach(get_COallert($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        case 3:            // seems to be an answer
          $ret["COtbd5h"]["name"] = "seems to be an answer";  
          foreach(get_COanswer($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        default:
          $ret["COtbd5h"]["name"] = "unhandled (value=".$ret["COtbd5h"]["value"].")";
    }    
    return $ret;
}

function get_COallert ($PDUbin, $StringPos)
{
    // Call-Out alert
    // get parts of Call-Out alert 
    // 
    $ret=array();
    foreach(get_COid($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_COseverity($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_tbd7($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_COtext($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

function get_COanswer ($PDUbin, $StringPos)
{
    // Call-Out answer
    // get parts of Call-Out answer 
    // 
    $ret=array();
    foreach(get_COid($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_COseverity($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_COtext($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

function get_COid ($PDUbin, $StringPos)
{
    // Call-Out ID 8 Bit [0-255]
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=8;
    $ret["COid"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    return $ret;
}

function get_COseverity ($PDUbin, $StringPos)
{
    // Call-Out severity 4 Bit [0-15]
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=4;
    $ret["COseverity"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    return $ret;
}

function get_tbd7 ($PDUbin, $StringPos)
{
    // tbd7 8 Bit [0-255]
    // to be defined, Byte 7 of PDU - no idea about
    // alerts seems to have "00" to now
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=8;
    $ret["COtbd7"] = array();
    $ret["COtbd7"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    switch($ret["COtbd7"]["value"]) {
        case 0:            // no idea
          $ret["COtbd7"]["name"] = "to be defined";  
          break;
        default:
          $ret["COtbd7"]["name"] = "unhandled (value=".$ret["COtbd7"]["value"].")";
    }    
    return $ret;
}

function get_COtext ($PDUbin, $StringPos)
{
    // User data - Bit Length variable
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=(strlen($PDUbin) - $StringPos);
    $TextHex = bintohex(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    // generate string from the hex character string based on the code page used
    $ret["COtext"] = mb_convert_encoding(pack('H*', $TextHex), mb_internal_encoding(), "ISO-8859-1");
    return $ret;
}

?>

