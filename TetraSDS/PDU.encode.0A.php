<?php

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.78 Time elapsed (Table 6.112: Time elapsed information element contents)
function make_TimeElapsed ($PDUelements)
{
    // Time elapsed 2 Bit [0-3]
    switch($PDUelements["TimeElapsed"]) {
        case 0: // 0 - less than 5 s
          $ret = "00";
          break;
        case 1: // 1 - less than 5 min
          $ret = "01";
          break;
        case 2: // 2 - less than 30 min
          $ret = "10";
          break;
        case 3: // 3 - Time elapsed not known or not applicable
          $ret = "11";
          break;
    }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.79  Time of position (Table 6.113: Time of position information element contents)
/*function get_TimeofPosition ($PDUbin, $StringPos)
{
    // Time of position [Day Hour Minute Second]
    $ret=array();
    $SubStrLen=5;
    $Day = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    $SubStrLen=5;
    $Hour = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    $SubStrLen=6;
    $Minute = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    $SubStrLen=6;
    $Second = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] = $StringPos + $SubStrLen;
    $ret["TimeofPosition"] = $Day." ".$Hour.":".$Minute.":".$Second;
    return $ret;
}*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.81 Time type (Table 6.115: Time type information element contents)
/*function get_TimeData ($PDUbin, $StringPos)
{
    // Time Type 2 Bit [0-3]
    $ret=array();
    $SubStrLen=2;
    $ret["TimeType"] = array();
    $ret["TimeType"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    switch($ret["TimeType"]["value"]) {
        case 0:    // None
          $ret["TimeType"]["name"] = "None";
          $ret["StringPos"] = $StringPos;
          break;
        case 1:    // Time elapsed
          $ret["TimeType"]["name"] = "Time elapsed";
          foreach(get_TimeElapsed($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
          break;
        case 2:    // Time of position
          $ret["TimeType"]["name"] = "Time of position";
          foreach(get_TimeofPosition($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
          break;
        case 3:    // Reserved 
          $ret["TimeType"]["name"] = "Reserved";
          $ret["StringPos"] = $StringPos;
          break;
    }
    return $ret;
}*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.45 Location point (Table 6.75: Location point information element contents)
function make_LocationPoint ($PDUelements)
{
    // Longitude: 25 bits
    $lonbin=hextobin(dechex($PDUelements["Longitude"] * pow(2,25) / 360));
    $j = 0;    
    $q = 25-strlen($lonbin);
    if ($q > 0) { while($j<$q) { $lonbin="0".$lonbin; $j+=1; } }
    $ret=$lonbin;

    // Latidude: 24 bits
    $latbin=hextobin(dechex($PDUelements["Latitude"] * pow(2,24) / 180));
    $ret.=$latbin;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.36 Location circle (Table 6.66: Location circle information element contents)
/*function get_LocationCircle ($PDUbin, $StringPos)
{
    // Longitude 25 Bit umrechnen in "deg" und auf 6 Nachkommastellen runden
    $ret=array();
    $SubStrLen=25;
    $ret["Longitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 360 / pow(2,25),6);
    $StringPos += $SubStrLen;
    // Latidude 24 Bit umrechnen in "deg" und auf 6 Nachkommastellen runden
    $SubStrLen=24;
    $ret["Latitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 180 / pow(2,24),6);
    $StringPos += $SubStrLen;
    // Horizontal position uncertainty 6 Bit [0-63] umrechnen in Meter und runden
    $SubStrLen=6;
    $ret["HorPosAccuracy"] = round((2 * pow(1.2,bindec(substr("$PDUbin",$StringPos,$SubStrLen)) + 5)) -4);
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.49 Location shape (Table 6.79: Location shape information element contents)
/*function get_LocationData ($PDUbin, $StringPos)
{
    // Location shape 4 Bit [0-15]
    $ret=array();
    $SubStrLen=4;
    $ret["LocationShape"] = array();
    $ret["LocationShape"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    switch($ret["LocationShape"]["value"]) {
        case 0:        // No shape
          $ret["LocationShape"]["name"] = "No shape";
          $ret["StringPos"] = $StringPos;
          break;
        case 1:        // Location point
          $ret["LocationShape"]["name"] = "Location point";
          foreach(get_LocationPoint($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
          break;
        case 2:        // Location circle
          $ret["LocationShape"]["name"] = "Location circle";
          foreach(get_LocationCircle($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
          break;
        case 3:        // Location ellipse
          $ret["LocationShape"]["name"] = "Location ellipse";
          $ret["StringPos"] = $StringPos;
          break;
        case 4:        // Location point with altitude
          $ret["LocationShape"]["name"] = "Location point with altitude";
          $ret["StringPos"] = $StringPos;
          break;
        case 5:        // Location circle with altitude
          $ret["LocationShape"]["name"] = "Location circle with altitude";
          $ret["StringPos"] = $StringPos;
          break;
        case 6:        // Location ellipse with altitude
          $ret["LocationShape"]["name"] = "Location ellipse with altitude";
          $ret["StringPos"] = $StringPos;
          break;
        case 7:        // Location circle with altitude and altitude uncertainty
          $ret["LocationShape"]["name"] = "Location circle with altitude and altitude uncertainty";
          $ret["StringPos"] = $StringPos;
          break;
        case 8:        // Location ellipse with altitude and altitude uncertainty
          $ret["LocationShape"]["name"] = "Location ellipse with altitude and altitude uncertainty";
          $ret["StringPos"] = $StringPos;
          break;
        case 9:        // Location arc
          $ret["LocationShape"]["name"] = "Location arc";
          $ret["StringPos"] = $StringPos;
          break;
        case 10:    // Location point and position error
          $ret["LocationShape"]["name"] = "Location point and position error";
          $ret["StringPos"] = $StringPos;
          break;
        case 15:    // Location shape extension
          $ret["LocationShape"]["name"] = "Location shape extension";
          $ret["StringPos"] = $StringPos;
          break;
        default:
          $ret["LocationShape"]["name"] = "Reserved (value=".$ret["LocationShape"]["value"].")";
          $ret["StringPos"] = $StringPos;

    }
    return $ret;
}*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.90 Velocity type (Table 6.124: Velocity type information element contents)
/*function get_VelocityData ($PDUbin, $StringPos)
{
    // Velocity Type 3 Bit [0-7]
    $ret=array();
    $SubStrLen=3;
    $ret["VelocityType"] = array();
    $ret["VelocityType"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    switch($ret["VelocityType"]["value"]) {
        case 0:        // No velocity information
          $ret["VelocityType"]["name"] = "No velocity information";
          $ret["StringPos"] = $StringPos;
          break;
        case 1:        // Horizontal velocity
          $ret["VelocityType"]["name"] = "Horizontal velocity";
          foreach(get_HorVeloc($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
          break;
        case 2:        // Horizontal velocity with uncertainty
          $ret["VelocityType"]["name"] = "Horizontal velocity with uncertainty";
          $ret["StringPos"] = $StringPos;
          break;
        case 3:        // Horizontal velocity and vertical velocity
          $ret["VelocityType"]["name"] = "Horizontal velocity and vertical velocity";
          $ret["StringPos"] = $StringPos;
          break;
        case 4:        // Horizontal velocity and vertical velocity with uncertainty
          $ret["VelocityType"]["name"] = "Horizontal velocity and vertical velocity with uncertainty";
          $ret["StringPos"] = $StringPos;
          break;
        case 5:        // Horizontal velocity with direction of travel extended
          $ret["VelocityType"]["name"] = "Horizontal velocity with direction of travel extended";
          $ret["StringPos"] = $StringPos;
          break;
        case 6:        // Horizontal velocity with direction of travel extended and uncertainty
          $ret["VelocityType"]["name"] = "Horizontal velocity with direction of travel extended and uncertainty";
          $ret["StringPos"] = $StringPos;
          break;
        case 7:        // Horizontal velocity and vertical velocity with direction of travel extended and uncertainty
          $ret["VelocityType"]["name"] = "Horizontal velocity and vertical velocity with direction of travel extended and uncertainty";
          $ret["StringPos"] = $StringPos;
          break;
    }
    return $ret;
}*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.1 Acknowledgement request (Table 6.31: Acknowledgement request information element contents)
/*function get_AckReq ($PDUbin, $StringPos)
{
    // Acknowledgement request 1 Bit [0-1]
    $ret=array();
    $SubStrLen=1;
    $ret["AckReq"] = array();
    $ret["AckReq"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["AckReq"]["value"]) {
        case 0:        // No acknowledgement requested
          $ret["AckReq"]["name"] = "No acknowledgement requested";
          break;
        case 1:        // Acknowledgement requested
          $ret["AckReq"]["name"] = "Acknowledgement requested";
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;;
    return $ret;
}*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.87  Type of additional data (Table 6.121: Type of additional data information element contents)
function make_TypeOfAddData ($PDUelements)
{
    // Type of additional data 1 Bit [0-1]
    switch($PDUelements["TypeOfAddData"]) {
          case 0:
        $ret = "0";
        // Reason for sending 8 Bit [0-255]
        // ETSI TS 100 392-18-1 V1.6.1
        // 6.3.64 Reason for sending (Table 6.94: Reason for sending information element contents)
        switch($PDUelements["ReasonForSending"]) {
              case 0:    //"Subscriber unit is powered ON"
                $ret .= "00000000";
                break;
              case 1:    //"Subscriber unit is powered OFF"
                $ret .= "00000001";
                break;
              case 2:    //"Emergency condition is detected"
                $ret .= "00000010";
                break;
              case 3:    //"Push-to-talk condition is detected"
                $ret .= "00000011";
                break;
              case 4:    //"Status"
                $ret .= "00000100";
                break;
              case 5:    //"Transmit inhibit mode ON"
                $ret .= "00000101";
                break;
              case 6:    //"Transmit inhibit mode OFF"
                $ret .= "00000110";
                break;
              case 7:    //"TMO ON"
                $ret .= "00000111";
                break;
              case 8:    //"DMO ON"
                $ret .= "00001000";
                break;
              case 9:    //"Enter service"
                $ret .= "00001001";
                break;
              case 10:    //"Service loss"
                $ret .= "00001010";
                break;
              case 11:    //"Cell reselection or change of serving cell"
                $ret .= "00001011";
                break;
              case 12:    //"Low battery"
                $ret .= "00001100";
                break;
              case 13:    //"Subscriber unit is connected to a car kit"
                $ret .= "00001101";
                break;
              case 14:    //"Subscriber unit is disconnected from a car kit"
                $ret .= "00001110";
                break;
              case 15:    //"Subscriber unit asks for transfer initialization configuration"
                $ret .= "00001111";
                break;
              case 16:    //"Arrival at destination"
                $ret .= "00010000";
                break;
              case 17:    //"Arrival at a defined location"
                $ret .= "00010001";
                break;
              case 18:    //"Approaching a defined location"
                $ret .= "00010010";
                break;
              case 19:    //"SDS type-1 entered"
                $ret .= "00010011";
                break;
              case 20:    //"User application initiated"
                $ret .= "00010100";
                break;
              case 21:    //"Lost ability to determine location"
                $ret .= "00010101";
                break;
              case 22:    //"Regained ability to determine location"
                $ret .= "00010110";
                break;
              case 23:    //"Leaving point"
                $ret .= "00010111";
                break;
              case 24:    //"Ambience Listening call is detected"
                $ret .= "00011000";
                break;
              case 25:    //"Start of temporary reporting"
                $ret .= "00011001";
                break;
              case 26:    //"Return to normal reporting"
                $ret .= "00011010";
                break;
              case 32:    //"Response to an immediate location request"
                $ret .= "00100000";
                break;
              case 129:    //"Maximum reporting interval exceeded"
                $ret .= "10000001";
                break;
              case 130:    //"Maximum reporting distance limit travelled"
                $ret .= "10000010";
                break;
          }
          break;
          case 1:
            $ret = "1";
            // User defined data 8 Bit [0-255]
            break;
    }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.63 Position error (Table 6.93: Position error information element contents)
function make_PosErr ($PDUelements)
{
    // Position error 3 Bit [0-7]
    switch($PDUelements["PosErr"]) {
        case 0:        // less than 2 m
          $ret = "000";
          break;
        case 1:        // less than 20 m
          $ret = "001";
          break;
        case 2:        // less than 200 m
          $ret = "010";
          break;
        case 3:        // less than 2 km
          $ret = "011";
          break;
        case 4:        // less than 20 km
          $ret = "100";
          break;
        case 5:        // less than or equal to 200 km
          $ret = "101";
          break;
        case 6:        // more than 200 km
          $ret = "110";
          break;
        case 7:        // Position error not known
          $ret = "111";
          break;
        }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.17 Horizontal velocity (Table 6.51: Examples of horizontal velocity information element contents)
function make_HorVeloc ($PDUelements)
{
    // Horizontal velocity 7 Bit [0-127]
    $ret = decbin($PDUelements["HorVeloc"]);
    $j = 0;    
    $q = 7-strlen($ret);
    if ($q > 0) { while($j<$q) { $ret="0".$ret; $j+=1; } }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.5 Direction of travel (Table 6.45: Direction of travel information element contents)
function make_DirOfTravel ($PDUelements)
{
    // Direction of travel 4 Bit [0-15]
    $ret = decbin($PDUelements["DirOfTravel"]);
    $j = 0;    
    $q = 4-strlen($ret);
    if ($q > 0) { while($j<$q) { $ret="0".$ret; $j+=1; } }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.44  Location message reference (Table 6.74: Location message reference information element contents)
/*
function get_LocMesRef ($PDUbin, $StringPos)
{
    // Location message reference 8 Bit [0-255]
    $ret=array();
    $SubStrLen=8;
    $ret["LocMesRef"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}
*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.68 Result code (Table 6.98: Result codes and their meaning)
/*
function get_ResCode ($PDUbin, $StringPos)
{
    // Result code 8 Bit [0-255]
    $ret=array();
    $SubStrLen=8;
    $ret["ResCode"]=array();
    $ret["ResCode"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["ResCode"]["name"] = "Result code: ".$ret["ResCode"]["value"];
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}
*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.69  SDS type-1 value (Table 6.99: SSI information element contents)
/*
function get_SDStyp1Val ($PDUbin, $StringPos)
{
    //SDS type-1 value 16 Bit
    $ret=array();
    $SubStrLen=16;
    $ret["SDStyp1Val"]=array();
    $ret["SDStyp1Val"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["SDStyp1Val"]["name"] = "SDS type-1 value: ".$ret["SDStyp1Val"]["value"];
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}
*/

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.69  SDS type-1 value (Table 6.99: SSI information element contents)
/*
function get_StatusVal ($PDUbin, $StringPos)
{
    //Status value 16 Bit
    $ret=array();
    $SubStrLen=16;
    $ret["StatusVal"]=array();
    $ret["StatusVal"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StatusVal"]["name"] = "Status value: ".$ret["StatusVal"]["value"];
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}
*/

// ETSI TS 100 392-18-1 V1.6.1
function make_ReqResp ($PDUelements)
{
    // Request/Response 1 Bit [0-1]
    switch($PDUelements["ReqResp"]) {
        case 0:        // Request
          $ret = "0";
          break;
        case 1:        // Response
          $ret = "1";
          break;
        }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.65 Report type (Table 6.95: Report type information element contents)
function make_RepType ($PDUelements)
{
    // Report type 2 Bit [0-3]
    switch($PDUelements["RepType"]) {
        case 0:        // Long location report preferred with no time information
          $ret = "00";
          break;
        case 1:        // Long location report preferred with time type "Time elapsed"
          $ret = "01";
          break;
        case 2:        // Long location report preferred with time type "Time of position"
          $ret = "10";
          break;
        case 3:        // Short location report preferred
          $ret = "11";
          break;
        }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.43 Location information destination (Table 6.73: Location information destination information element contents)
function make_LocInfoDest ($PDUelements)
{
    // 6.3.2 Address or identification type (Table 6.32: Address or identification type information element contents)
    // Address or identification type 4 Bit [0-15]
    switch($PDUelements["AddrType"]) {
        case 0:        // No terminal or location identification available
          $ret = "0000";
          break;
        case 1:        // SSI
          $ret = "0001";
          $ret .= substr(hextobin(dechex($PDUelements["addr"])),0,24);
          break;
        case 2:        // SSI and MNI
          $ret = "0010";
          break;
        case 3:        // IP address (Version 4) RFC 791 [3]
          $ret = "0011";
          break;
        case 4:        // IP address (Version 6) RFC 3513 [4] 
          $ret = "0100";
          break;
        case 5:        // Reserved
          $ret = "0101";
          break;
        case 6:        // Reserved
          $ret = "0110";
          break;
        case 7:        // Reserved
          $ret = "0111";
          break;
        case 8:        // External subscriber number 
          $ret = "1000";
          break;
        case 9:        // SSI and External subscriber number
          $ret = "1001";
          break;
        case 10:    // SSI and MNI and External subscriber number
          $ret = "1010";
          break;
        case 11:    // Name server type name
          $ret = "1011";
          break;
        case 12:    // Name, free format
          $ret = "1100";
          break;
        case 13:    // Reserved
          $ret = "1101";
          break;
        case 14:    // Reserved
          $ret = "1110";
          break;
        case 15:    // Reserved
          $ret = "1111";
          break;
        }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.2.16 IMMEDIATE LOCATION REPORT REQUEST PDU (Table 6.16: IMMEDIATE LOCATION REPORT REQUEST PDU contents)
function make_ImmLocReq ($PDUelements)
{
    $ret = $PDUelements["PDUbin"];
    $ret .= make_ReqResp($PDUelements);
    $ret .= make_RepType($PDUelements);        
    $ret .= make_LocInfoDest($PDUelements);
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.2.1  SHORT LOCATION REPORT PDU (Table 6.1: SHORT LOCATION REPORT PDU contents)
function make_ShortLocRep ($PDUelements)
{
    $ret = $PDUelements["PDUbin"];
    $ret .= make_TimeElapsed($PDUelements);
    $ret .= make_LocationPoint($PDUelements);
    $ret .= make_PosErr($PDUelements);
    $ret .= make_HorVeloc($PDUelements);
    $ret .= make_DirOfTravel($PDUelements);
    $ret .= make_TypeOfAddData($PDUelements);
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.2.2  LONG LOCATION REPORT PDU (Table 6.2: LONG LOCATION REPORT PDU contents)
function make_LongLocRep ($PDUelements)
{
    $ret = $PDUelements["PDUbin"];
    $ret .= make_TimeData($PDUelements);
    $ret .= make_LocationData($PDUelements);
    $ret .= make_VelocityData($PDUelements);
    $ret .= make_AckReq($PDUelements);
    $ret .= make_TypeOfAddData($PDUelements);
    //$PDUelements["PDUbin"] = $PDUelements["PDUbin"].make_LocMesRef($PDUelements);
    //$PDUelements["PDUbin"] = $PDUelements["PDUbin"].make_ResCode($PDUelements);    
    //$PDUelements["PDUbin"] = $PDUelements["PDUbin"].make_SDStyp1Val($PDUelements);
    //$PDUelements["PDUbin"] = $PDUelements["PDUbin"].make_StatusVal($PDUelements);
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.61 PDU type (Table 6.91: PDU type information element contents)
function make_LipPDU ($PDUelements)
{
    // PDU type 2 Bit [0-3]
    switch($PDUelements["PduType"]) {
        case 0:            // Short location report
          $PDUelements["PDUbin"] .= "00";
          $ret= make_ShortLocRep($PDUelements);
          break;
        case 1:            // Location protocol PDU with extension
          $PDUelements["PDUbin"] .= "01";          
          // PDU type extension 4 Bit [0-15]
          // ETSI TS 100 392-18-1 V1.6.1
          // 6.3.62  PDU type extension (Table 6.92: PDU type extension information element contents)
          switch($PDUelements["PduTypeExt"]) {
                  case 1:    // Immediate location report request
                  $PDUelements["PDUbin"] .= "0001";
                  $ret= make_ImmLocReq($PDUelements);
                  break;
                  case 3:    // Long location report
                  $PDUelements["PDUbin"] .= "0011";    
                  $ret= make_LongLocRep($PDUelements);                
                  break;
              //case 4:    // Location report acknowledgement
                //break;
              //case 5:    // Basic location parameters request/response
                //break;
              //case 6:    // Add/modify trigger request/response
                //break;
              //case 7:    // Remove trigger request/response
                //break;
              //case 8:    // Report trigger request/response
                //break;
              //case 9:    // Report basic location parameters request/response
                //break;
              //case 10:    // Location reporting enable/disable request/response
                //break;
              //case 11:    // Location reporting temporary control request/response
                //break;
              //case 12:    // Backlog request/response
                //break;
          }
          break;
      }
      return $ret;
}
?>
