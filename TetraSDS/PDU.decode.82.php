<?php

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.3  Delivery report request (Table 29.17: Delivery report request information element contents)
function get_DelivRepReq ($PDUbin, $StringPos)
{
    // Delivery report request 2 Bit [0-3]
    $ret=array();
    $SubStrLen=2;
    $ret["DelivRepReq"] = array();
    $ret["DelivRepReq"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["DelivRepReq"]["value"]) {
        case 0:        // No delivery report requested
          $ret["DelivRepReq"]["name"] = "No delivery report requested";
          break;
        case 1:        // Message received report requested
          $ret["DelivRepReq"]["name"] = "Message received report requested";
          break;
        case 2:        // Message consumed report requested
          $ret["DelivRepReq"]["name"] = "Message consumed report requested";
          break;
        case 3:        // Message received and consumed report requested 
          $ret["DelivRepReq"]["name"] = "Message received and consumed report requested ";
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.10  Service selection/short form report (Table 29.22: Service selection/ short form report information element contents)
function get_ShrtFmRep ($PDUbin, $StringPos)
{
    // Service selection/short form report 1 Bit [0-1]
    $ret=array();
    $SubStrLen=1;
    $ret["ShrtFmRep"] = array();
    $ret["ShrtFmRep"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["ShrtFmRep"]["value"]) {
        case 0:        // Use short form report
          $ret["ShrtFmRep"]["name"] = "short form report";
          break;
        case 1:        // Only standard report
          $ret["ShrtFmRep"]["name"] = "standard report";
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.12 Storage/forward control (Table 29.24: Storage/forward control information element contents)
function get_StorFwd ($PDUbin, $StringPos)
{
    // Storage/forward control  1 Bit [0-1]
    $ret=array();
    $SubStrLen=1;
    $ret["StorFwd"] = array();
    $ret["StorFwd"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["StorFwd"]["value"]) {
        case 0:        // Storage/forward control information not available 
          $ret["StorFwd"]["name"] = "Storage/forward control information not available";
          break;
        case 1:        // Storage/forward control information is available 
          $ret["StorFwd"]["name"] = "Storage/forward control information is available";
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.7 Message reference (Table 29.19: Message reference information elements contents)
function get_MsgRef ($PDUbin, $StringPos)
{
    // Message reference 8 Bit [0-255]
    $ret=array();
    $SubStrLen=8;
    $ret["MsgRef"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.3.3  Text message transfer SDU (Table 29.28: Text message transfer SDU contents)
function get_TrfSDU ($PDUbin, $StringPos)
{
    // User data - Bit Length variable
    $ret=array();
    foreach(get_TimStmpUsd($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_TxtCodSch($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    if($ret["TimStmpUsd"]["value"] == 1)
    {
        foreach(get_TimStmp($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    }
    $SubStrLen=(strlen($PDUbin) - $ret["StringPos"]); // determine the length of the text string in bits
    $TextHex = bintohex(substr("$PDUbin",$ret["StringPos"],$SubStrLen)); // determine hexadecimal coded text string
    $ret["StringPos"] += $SubStrLen;
    if((isset($ret["TxtCodSch"]["name"])) && ($ret["TxtCodSch"]["value"] <= 26))
    {
        // generate string from the hex character string based on the code page used
        $ret["Text"] = mb_convert_encoding(pack('H*', $TextHex), mb_internal_encoding(), $ret["TxtCodSch"]["name"]);
    }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.4.5 Timestamp used  (Table 29.41: Timestamp used information element contents)
function get_TimStmpUsd ($PDUbin, $StringPos)
{
    // Timestamp used 1 Bit [0-1]
    $ret=array();
    $SubStrLen=1;
    $ret["TimStmpUsd"] = array();
    $ret["TimStmpUsd"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["TimStmpUsd"]["value"]) {
        case 0:        // Timestamp not present 
          $ret["TimStmpUsd"]["name"] = "Timestamp not present";
          break;
        case 1:        // Timestamp present 
          $ret["TimStmpUsd"]["name"] = "Timestamp present";
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.4.1  Text coding scheme (Table 29.29: Text coding scheme information element contents)
function get_TxtCodSch ($PDUbin, $StringPos)
{
      // Text coding scheme 7 Bit [0-127]
      $ret=array();
      $SubStrLen=7;
      if(($StringPos + $SubStrLen) <= (strlen($PDUbin)))
      {
          $ret["StringPos"] = $StringPos + $SubStrLen;
          $ret["TxtCodSch"] = array();
          $ret["TxtCodSch"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
          switch($ret["TxtCodSch"]["value"]) {
              case 0:        // 7-bit alphabet
                $ret["TxtCodSch"]["name"] = "7bit";
                break;
              case 1:        // ISO/IEC 8859-1 [40] Latin 1 (8-bit) alphabet
                $ret["TxtCodSch"]["name"] = "ISO-8859-1";
                break;
              case 2:        // ISO/IEC 8859-2 [40] Latin 2 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-2";
                break;
              case 3:        // ISO/IEC 8859-3 [40] Latin 3 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-3";
                break;
              case 4:        // ISO/IEC 8859-4 [40] Latin 4 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-4";
                break;
              case 5:        // ISO/IEC 8859-5 [40] Latin/Cyrillic (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-5";
                break;
              case 6:        // ISO/IEC 8859-6 [40] Latin/Arabic (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-6";
                break;
              case 7:        // ISO/IEC 8859-7 [40] Latin/Greek (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-7";
                break;
              case 8:        // ISO/IEC 8859-8 [40] Latin/Hebrew (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-8";
                break;
              case 9:        // ISO/IEC 8859-9 [40] Latin 5 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-9";
                break;
              case 10:    // ISO/IEC 8859-10 [40] Latin 6 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-10";
                break;
              case 11:    // ISO/IEC 8859-13 [40] Latin 7 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-13";
                break;
              case 12:    // ISO/IEC 8859-14 [40] Latin 8 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-14";
                break;
              case 13:    // ISO/IEC 8859-15 [40] Latin 9 (8-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "ISO-8859-15";
                break;
              case 26:    // ISO/IEC 10646-1 [22] UCS-2/UTF-16BE (16-bit) alphabet 
                $ret["TxtCodSch"]["name"] = "UCS-2";
                break;
              default:
                $ret["TxtCodSch"]["name"] = "unhandled (value=".$ret["TxtCodSch"]["value"].")";
          }
      }
      return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.5.4.4 Time stamp (Table 29.40: Time stamp information element contents)
function get_TimStmp ($PDUbin, $StringPos)
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
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.14 Validity period (Table 29.25: Validity period information element contents)
function get_ValidityPeriod ($PDUbin, $StringPos)
{
    $SubStrLen=5; // Validity period (VP) 5 Bit [0-31]
    $ret=array();
    $ret["ValidityPeriod"] = array();
    $ret["ValidityPeriod"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.5  Forward address type (Table 29.18: Forward address type information element contents)
function get_FWDaddTyp ($PDUbin, $StringPos)
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
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.2.1 SDS-ACK (Table 29.11: SDS-ACK PDU contents)
function get_SDSAck ($PDUbin, $StringPos)
{
    $ret=array();
    // to do
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.2.2 SDS-REPORT (Table 29.12: SDS-REPORT PDU contents)
function get_SDSReport ($PDUbin, $StringPos)
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
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.2.4 SDS-TRANSFER (Table 29.14: SDS-TRANSFER PDU contents)
function get_SDSTransfer ($PDUbin, $StringPos)
{
    $ret=array();
    foreach(get_DelivRepReq($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_ShrtFmRep($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_StorFwd($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    if($ret["StorFwd"]["value"] == 1)
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
    }
    foreach(get_MsgRef($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_TrfSDU($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

// ETSI EN 300 392-2 V3.4.1
// 29.4.3.8 Message type (Table 29.20: Message type information element contents)
function get_TextPDU ($PDUbin, $StringPos)
{
    // Message type 4 Bit [0-15] 
    // 3-7 Reserved for additional message types
    // 8-15 Defined by application
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=4;
    $ret["MsgType"] = array();
           $ret["MsgType"]["value"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"] += $SubStrLen;
    if(($ret["MsgType"]["value"] > 2) && ($ret["MsgType"]["value"] < 8)) { $ret["MsgType"]["name"] = "Reserved for additional message types"; }
    elseif($ret["MsgType"]["value"] > 7) { $ret["MsgType"]["name"] = "Defined by application"; }
    else
    {
        switch($ret["MsgType"]["value"]) {
        case 0:            // SDS-TRANSFER
          $ret["MsgType"]["name"] = "SDS-TRANSFER";
          foreach(get_SDSTransfer($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        case 1:            // SDS-REPORT 
          $ret["MsgType"]["name"] = "SDS-REPORT";
          foreach(get_SDSReport($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        case 2:            // SDS-ACK  
          $ret["MsgType"]["name"] = "SDS-ACK ";
          foreach(get_SDSAck($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        }
    }
    return $ret;
}
?>

