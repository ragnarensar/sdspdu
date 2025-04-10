<?php

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.3  Delivery report request (Table 29.17: Delivery report request information element contents)
function make_DelivRepReq ($PDUelements)
{
    // Delivery report request 2 Bit [0-3]
    switch($PDUelements["DelivRepReq"]) {
        case 0:        // No delivery report requested
          $ret = "00";
          break;
        case 1:        // Message received report requested
          $ret = "01";
          break;
        case 2:        // Message consumed report requested
          $ret = "10";
          break;
        case 3:        // Message received and consumed report requested 
          $ret = "11";
          break;
      }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.10  Service selection/short form report (Table 29.22: Service selection/ short form report information element contents)
function make_ShrtFmRep ($PDUelements)
{
    // Service selection/short form report 1 Bit [0-1]
    switch($PDUelements["ShrtFmRep"]) {
        case 0:        // Use short form report
          $ret = "0";
          break;
        case 1:        // Only standard report
          $ret = "1";
          break;
      }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.12 Storage/forward control (Table 29.24: Storage/forward control information element contents)
function make_StorFwd ($PDUelements)
{
    // Storage/forward control  1 Bit [0-1]
    switch($PDUelements["StorFwd"]) {
        case 0:        // Storage/forward control information not available 
          $ret = "0";
          break;
        case 1:        // Storage/forward control information is available 
          $ret = "1";
          break;
        }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.7 Message reference (Table 29.19: Message reference information elements contents)
function make_MsgRef ($PDUelements)
{
    // Message reference 8 Bit [0-255]
    $ret=hextobin(dechex($PDUelements["MsgRef"]));
    $j = 0;    
    $q = 8-strlen($ret);
    if ($q > 0) { while($j<$q) { $ret="0".$ret; $j+=1; } }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.3.3  Text message transfer SDU (Table 29.28: Text message transfer SDU contents)
function make_TrfSDU ($PDUelements)
{
    // User data - Bit Length variable
    $ret = make_TimStmpUsd($PDUelements);
    $ret .= make_TxtCodSch($PDUelements);
    /*if($PDUelements["TimStmpUsd"] == 1) { $ret .= make_TimStmp($PDUelements); }*/
    if((isset($PDUelements["TxtCodSch"])) && ($PDUelements["TxtCodSch"] <= 26))
    {
        switch($PDUelements["TxtCodSch"]) {
        case 0:        // 7-bit alphabet
          $TxtCodSch_name = "7bit";
          break;
        case 1:        // ISO/IEC 8859-1 [40] Latin 1 (8-bit) alphabet
          $TxtCodSch_name = "ISO-8859-1";
          break;
        case 2:        // ISO/IEC 8859-2 [40] Latin 2 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-2";
          break;
        case 3:        // ISO/IEC 8859-3 [40] Latin 3 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-3";
          break;
        case 4:        // ISO/IEC 8859-4 [40] Latin 4 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-4";
          break;
        case 5:        // ISO/IEC 8859-5 [40] Latin/Cyrillic (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-5";
          break;
        case 6:        // ISO/IEC 8859-6 [40] Latin/Arabic (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-6";
          break;
        case 7:        // ISO/IEC 8859-7 [40] Latin/Greek (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-7";
          break;
        case 8:        // ISO/IEC 8859-8 [40] Latin/Hebrew (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-8";
          break;
        case 9:        // ISO/IEC 8859-9 [40] Latin 5 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-9";
          break;
        case 10:    // ISO/IEC 8859-10 [40] Latin 6 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-10";
          break;
        case 11:    // ISO/IEC 8859-13 [40] Latin 7 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-13";
          break;
        case 12:    // ISO/IEC 8859-14 [40] Latin 8 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-14";
          break;
        case 13:    // ISO/IEC 8859-15 [40] Latin 9 (8-bit) alphabet 
          $TxtCodSch_name = "ISO-8859-15";
          break;
        case 26:    // ISO/IEC 10646-1 [22] UCS-2/UTF-16BE (16-bit) alphabet 
          $TxtCodSch_name = "UCS-2";
          break;
      }
    // generate string from the hex character string based on the code page used
    $ret .= hextobin(bin2hex(mb_convert_encoding($PDUelements["Text"], $TxtCodSch_name, mb_internal_encoding())));
    }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.4.5 Timestamp used  (Table 29.41: Timestamp used information element contents)
function make_TimStmpUsd ($PDUelements)
{
    // Timestamp used 1 Bit [0-1]
    switch($PDUelements["TimStmpUsd"]) {
        case 0:        // Timestamp not present 
          $ret = "0";
          break;
        case 1:        // Timestamp present 
          $ret = "1";
          break;
      }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.4.1  Text coding scheme (Table 29.29: Text coding scheme information element contents)
function make_TxtCodSch ($PDUelements)
{
    // Text coding scheme 7 Bit [0-127]
    $ret=hextobin(dechex($PDUelements["TxtCodSch"]));
    $j = 0;    
    $q = 7-strlen($ret);
    if ($q > 0) { while($j<$q) { $ret="0".$ret; $j+=1; } }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.4.4 Time stamp (Table 29.40: Time stamp information element contents)
/*function get_TimStmp ($PDUbin, $StringPos)
{
      // Time stamp information 24 Bit
      $ret=array();
      $SubStrLen=2; //Timeframe type 2 Bit [0-3]
      if(($StringPos + 24) <= (strlen($PDUbin)))
      {
    $ret["TimStmpTyp"] = array();
    $ret["TimStmpTyp"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["TimStmpTyp"]["value"]) {
        case 0:        // application dependent
          $ret["TimStmpTyp"]["name"] = "application dependent";
          break;
        case 1:        // UTC
          $ret["TimStmpTyp"]["name"] = "UTC";
          break;
        case 2:        // Local time
          $ret["TimStmpTyp"]["name"] = "Local time";
          break;
        case 3:        // Local daylight saving time 
          $ret["TimStmpTyp"]["name"] = "Local daylight saving time";
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    $ret["StringPos"] += 2; // inserted to ensure that the following information elements are aligned to octet boundaries
    $SubStrLen=4; // Month 4 Bit [0-15]
    $month = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    $SubStrLen=5; // Day 5 Bit [0-31]
    $day = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    $SubStrLen=5; // Hour 5 Bit [0-31]
    $hour = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    if($hour < 10) { $hour = "0".$hour; }
    $ret["StringPos"] += $SubStrLen;
    $SubStrLen=6; // Minute 6 Bit [0-63]
    $minute = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    if($minute < 10) { $minute = "0".$minute; }
    $ret["StringPos"] += $SubStrLen;
    $ret["TimStmp"] = $month."/".$day." ".$hour.":".$minute;
      }
      return $ret;
}*/

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.14 Validity period (Table 29.25: Validity period information element contents)
/*function get_ValidityPeriod ($PDUbin, $StringPos)
{
    $SubStrLen=5; // Validity period (VP) 5 Bit [0-31]
    $ret=array();
    $ret["ValidityPeriod"] = array();
    $ret["ValidityPeriod"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    return $ret;
}*/

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.5  Forward address type (Table 29.18: Forward address type information element contents)
/*function get_FWDaddTyp ($PDUbin, $StringPos)
{
    $SubStrLen=3; // Forward address type 3 Bit [0-7]
    $ret=array();
    $ret["FWDaddTyp"] = array();
    $ret["FWDaddTyp"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["FWDaddTyp"]["value"]) {
        case 0:        // Short Number Address (SNA)
          $ret["FWDaddTyp"]["name"] = "SNA";
          break;
        case 1:        // Short Subscriber Identity (SSI)
          $ret["FWDaddTyp"]["name"] = "SSI";
          break;
        case 2:        // TETRA Subscriber Identity (TSI)
          $ret["FWDaddTyp"]["name"] = "TSI";
          break;
        case 3:        // External subscriber number
          $ret["FWDaddTyp"]["name"] = "External subscriber number";
          break;
        case 7:        // No forward address present
          $ret["FWDaddTyp"]["name"] = "No forward address present";
          break;
        default:
          $ret["FWDaddTyp"]["name"] = "unhandled (value=".$ret["FWDaddTyp"]["value"].")";
    }
    $ret["StringPos"] += $SubStrLen;
    return $ret;
}*/

// ETSI EN 300 392-2 V3.4.1
// 29.4.2.1 SDS-ACK (Table 29.11: SDS-ACK PDU contents)
/*function get_SDSAck ($PDUbin, $StringPos)
{
    $ret=array();
    // to do
    return $ret;
}*/

// ETSI EN 300 392-2 V3.4.1
// 29.4.2.2 SDS-REPORT (Table 29.12: SDS-REPORT PDU contents)
/*function get_SDSReport ($PDUbin, $StringPos)
{
    $ret=array();
    // Acknowledgement required  (1 Bit) 
    // Reserved (2 Bit)
    // Storage/forward control (1 Bit)
    // Delivery status (8 Bit)
    // Message reference (8 Bit)
    //
    // --> Elements conditional to storage value 1 are present
    // Validity period (5 Bit)
    // Forward address type (3 Bit)
    // Forward address short number address (8 Bit)
    // Forward address SSI (24 Bit)
    // Forward address extension (24 Bit)
    // Number of external subscriber number digits (8 Bit)
    // External subscriber number digit (4 Bit)
    //
    // User data (variable Bit)
    return $ret;
}*/

// ETSI EN 300 392-2 V3.4.1
// 29.4.2.4 SDS-TRANSFER (Table 29.14: SDS-TRANSFER PDU contents)
function make_SDSTransfer ($PDUelements)
{
    $ret = $PDUelements["PDUbin"];
    $ret .= make_DelivRepReq($PDUelements);
    $ret .= make_ShrtFmRep($PDUelements);
    $ret .= make_StorFwd($PDUelements);
    /*if($ret["StorFwd"]["value"] == 1)
    {
        // Elements conditional to storage value 1 are present
        foreach(get_ValidityPeriod($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
        foreach(get_FWDaddTyp($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
        switch($ret["FWDaddTyp"]["value"]) {
            case 0:            // Short Number Address (SNA)
              $SubStrLen = 8;    // Forward address SNA (8 Bit)
              $ret["FWDadd"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
              $ret["StringPos"] += $SubStrLen;
              break;
            case 1:            // Short Subscriber Identity (SSI)
              $SubStrLen = 24;    // Forward address SSI (24 Bit)
              $ret["FWDadd"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
              $ret["StringPos"] += $SubStrLen;
              break;
            case 2:            // TETRA Subscriber Identity (TSI)
              $SubStrLen = 48;     // 10 bits Mobile Country Code (MCC) + 14 bits Mobile Network Code (MNC) + 24 bits (SSI)
              $ret["FWDadd"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
              $ret["StringPos"] += $SubStrLen;
              break;
            case 3:            // External subscriber number
              $SubStrLen = 8;    // Number of external subscriber number digits (8 Bit)
              $digits = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));          
              $ret["StringPos"] += $SubStrLen;
              $SubStrLen = 4;
              for($i=0;$i<$digits;$i++) // für jede Ziffer je 4 Bit auslesen
              {
                  $digit = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
                  if($digit < 10) { $number .= $digit; }
                  $ret["StringPos"] += $SubStrLen;                
              }
              $ret["FWDadd"] = $number;
              if ($digits % 2 != 0) { $ret["StringPos"] += $SubStrLen; }    // Dummy digit (4 Bit)
              break;
        }
    }*/
    $ret .= make_MsgRef($PDUelements);
    $ret .= make_TrfSDU($PDUelements);
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.8 Message type (Table 29.20: Message type information element contents)
function make_TextPDU ($PDUelements)
{
    // Message type 4 Bit [0-15] 
    // 3-7 Reserved for additional message types
    // 8-15 Defined by application
    switch($PDUelements["MsgType"]) {
        case 0:            // SDS-TRANSFER
          $PDUelements["PDUbin"] .= "0000";
          $ret= make_SDSTransfer($PDUelements);
          break;
        #case 1:            // SDS-REPORT
          #$PDUelements["PDUbin"] .= "0001";
          #$ret= make_SDSReport($PDUelements);
          #break;
        #case 2:            // SDS-ACK  
          #$PDUelements["PDUbin"] .= "0010";
          #$ret= make_SDSAck($PDUelements);
          #break;
    }
    return $ret;
}
?>

