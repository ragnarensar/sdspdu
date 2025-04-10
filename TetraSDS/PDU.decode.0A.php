<?php

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.78 Time elapsed (Table 6.112: Time elapsed information element contents)
function get_TimeElapsed ($PDUbin, $StringPos)
{
    // Time elapsed 2 Bit [0-3]
    $ret=array();
    $SubStrLen = 2;
    $ret["TimeElapsed"] = array();
    $ret["TimeElapsed"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["TimeElapsed"]["value"]) {
        case 0: // 0 - less than 5 s
          $ret["TimeElapsed"]["name"] = "less than 5 s";
          break;
        case 1: // 1 - less than 5 min
          $ret["TimeElapsed"]["name"] = "less than 5 min";
          break;
        case 2: // 2 - less than 30 min
          $ret["TimeElapsed"]["name"] = "less than 30 min";
          break;
        case 3: // 3 - Time elapsed not known or not applicable
          $ret["TimeElapsed"]["name"] = "not known or not applicable";
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.79  Time of position (Table 6.113: Time of position information element contents)
function get_TimeofPosition ($PDUbin, $StringPos)
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
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.81 Time type (Table 6.115: Time type information element contents)
function get_TimeData ($PDUbin, $StringPos)
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
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.31 Location altitude (Table 6.62: Location altitude information element contents)
function get_LocationAltitude ($PDUbin, $StringPos)
{
    // Location altitude type 1 Bit [0-1]
    $ret=array();
    $SubStrLen=1;
    $ret["AltitudeType"] = array();
    $ret["AltitudeType"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    switch($ret["AltitudeType"]["value"]) {
        case 0:    // WGS84 ellipsoid
          $ret["AltitudeType"]["name"] = "WGS84 ellipsoid";
          break;
        case 1:    // User defined
          $ret["AltitudeType"]["name"] = "User defined";
          break;
    }
    // Altitude 11 Bit [0-2047]
    $SubStrLen=11;
    $ret["Altitude"] = array();
    $ret["Altitude"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    if($ret["Altitude"]["value"] == 0)
    {
        $ret["Altitude"]["name"] = "Reserved";
    }
    elseif(($ret["Altitude"]["value"] > 0) && ($ret["Altitude"]["value"] < 1202))
    // 1 - 1201 = -200m - 1000m (step 1m) result=value-201m
    {
        $ret["Altitude"]["name"] = $ret["Altitude"]["value"] - 201 . "m";
    }
    elseif(($ret["Altitude"]["value"] > 1201) && ($ret["Altitude"]["value"] < 1927))
    // 1202 - 1926 = 1002m - 2450m (step 2m) result=value*2-1402
    {
        $ret["Altitude"]["name"] = $ret["Altitude"]["value"] * 2 - 1402 . "m";
    }
    else
    // 1927 - 2047 = 2525m - 11525m or more (step 75m) result=value*75-142000
    {
        $ret["Altitude"]["name"] = $ret["Altitude"]["value"] * 75 - 142000 . "m or more";
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.45 Location point (Table 6.75: Location point information element contents)
function get_LocationPoint ($PDUbin, $StringPos)
{
    // Longitude: Convert 25 bits and round to 6 decimal places
    $ret=array();
    $SubStrLen=25;
    $ret["Longitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 360 / pow(2,25),6);
    $StringPos += $SubStrLen;
    // Latidude: convert 24 bits and round to 6 decimal places
    $SubStrLen=24;
    $ret["Latitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 180 / pow(2,24),6);
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.36 Location circle (Table 6.66: Location circle information element contents)
function get_LocationCircle ($PDUbin, $StringPos)
{
    // Longitude: convert 25 bits to “deg” and round to 6 decimal places
    $ret=array();
    $SubStrLen=25;
    $ret["Longitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 360 / pow(2,25),6);
    $StringPos += $SubStrLen;
    // Latidude: convert 24 bits to “deg” and round to 6 decimal places
    $SubStrLen=24;
    $ret["Latitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 180 / pow(2,24),6);
    $StringPos += $SubStrLen;
    // Horizontal position uncertainty 6 Bit [0-63] convert to meters and round
    $SubStrLen=6;
    $ret["HorPosAccuracy"] = round((2 * pow(1.2,bindec(substr("$PDUbin",$StringPos,$SubStrLen)) + 5)) -4);
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.47 Location point with altitude (Table 6.77: Location point with altitude information element contents)
function get_LocationPointWithAltitude ($PDUbin, $StringPos)
{
    // Longitude: convert 25 bits to “deg” and round to 6 decimal places
    $ret=array();
    $SubStrLen=25;
    $ret["Longitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 360 / pow(2,25),6);
    $StringPos += $SubStrLen;
    // Latidude: convert 24 bits to “deg” and round to 6 decimal places
    $SubStrLen=24;
    $ret["Latitude"] = round(bindec(substr("$PDUbin",$StringPos,$SubStrLen)) * 180 / pow(2,24),6);
    $StringPos += $SubStrLen;
    // Location altitude
    foreach(get_LocationAltitude($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.49 Location shape (Table 6.79: Location shape information element contents)
function get_LocationData ($PDUbin, $StringPos)
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
          foreach(get_LocationPointWithAltitude($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
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
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.90 Velocity type (Table 6.124: Velocity type information element contents)
function get_VelocityData ($PDUbin, $StringPos)
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
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.1 Acknowledgement request (Table 6.31: Acknowledgement request information element contents)
function get_AckReq ($PDUbin, $StringPos)
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
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.87  Type of additional data (Table 6.121: Type of additional data information element contents)
function get_TypeOfAddData ($PDUbin, $StringPos)
{
    // Type of additional data 1 Bit [0-1]
    $ret=array();
    $SubStrLen=1;
    $ret["TypeOfAddData"] = array();
    $ret["TypeOfAddData"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    switch($ret["TypeOfAddData"]["value"]) {
        case 0:
        // Reason for sending 8 Bit [0-255]
        // ETSI TS 100 392-18-1 V1.6.1
        // 6.3.64 Reason for sending (Table 6.94: Reason for sending information element contents)
          $ret["TypeOfAddData"]["name"] = "Reason for sending";        
          $SubStrLen=8;
          $ret["ReasonForSending"] = array();
          $ret["ReasonForSending"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
          switch($ret["ReasonForSending"]["value"]) {
              case 0:
                $ret["ReasonForSending"]["name"] = "Subscriber unit is powered ON";
                break;
              case 1:
                $ret["ReasonForSending"]["name"] = "Subscriber unit is powered OFF";
                break;
              case 2:
                $ret["ReasonForSending"]["name"] = "Emergency condition is detected";
                break;
              case 3:
                $ret["ReasonForSending"]["name"] = "Push-to-talk condition is detected";
                break;
              case 4:
                $ret["ReasonForSending"]["name"] = "Status";
                break;
              case 5:
                $ret["ReasonForSending"]["name"] = "Transmit inhibit mode ON";
                break;
              case 6:
                $ret["ReasonForSending"]["name"] = "Transmit inhibit mode OFF";
                break;
              case 7:
                $ret["ReasonForSending"]["name"] = "TMO ON";
                break;
              case 8:
                $ret["ReasonForSending"]["name"] = "DMO ON";
                break;
              case 9:
                $ret["ReasonForSending"]["name"] = "Enter service";
                break;
              case 10:
                $ret["ReasonForSending"]["name"] = "Service loss";
                break;
              case 11:
                $ret["ReasonForSending"]["name"] = "Cell reselection or change of serving cell";
                break;
              case 12:
                $ret["ReasonForSending"]["name"] = "Low battery";
                break;
              case 13:
                $ret["ReasonForSending"]["name"] = "Subscriber unit is connected to a car kit";
                break;
              case 14:
                $ret["ReasonForSending"]["name"] = "Subscriber unit is disconnected from a car kit";
                break;
              case 15:
                $ret["ReasonForSending"]["name"] = "Subscriber unit asks for transfer initialization configuration";
                break;
              case 16:
                $ret["ReasonForSending"]["name"] = "Arrival at destination";
                break;
              case 17:
                $ret["ReasonForSending"]["name"] = "Arrival at a defined location";
                break;
              case 18:
                $ret["ReasonForSending"]["name"] = "Approaching a defined location";
                break;
              case 19:
                $ret["ReasonForSending"]["name"] = "SDS type-1 entered";
                break;
              case 20:
                $ret["ReasonForSending"]["name"] = "User application initiated";
                break;
              case 21:
                $ret["ReasonForSending"]["name"] = "Lost ability to determine location";
                break;
              case 22:
                $ret["ReasonForSending"]["name"] = "Regained ability to determine location";
                break;
              case 23:
                $ret["ReasonForSending"]["name"] = "Leaving point";
                break;
              case 24:
                $ret["ReasonForSending"]["name"] = "Ambience Listening call is detected";
                break;
              case 25:
                $ret["ReasonForSending"]["name"] = "Start of temporary reporting";
                break;
              case 26:
                $ret["ReasonForSending"]["name"] = "Return to normal reporting";
                break;
              case 32:
                $ret["ReasonForSending"]["name"] = "Response to an immediate location request";
                break;
              case 129:
                $ret["ReasonForSending"]["name"] = "Maximum reporting interval exceeded";
                break;
              case 130:
                $ret["ReasonForSending"]["name"] = "Maximum reporting distance limit travelled";
                break;
              default:
                $ret["ReasonForSending"]["name"] = "Reserved (value=".$ret["ReasonForSending"]["value"].")";
          }
          break;
        case 1:
          // User defined data 8 Bit [0-255]
          $ret["TypeOfAddData"]["name"] = "User defined data";  
          $SubStrLen=8;
          $ret["UserDefinedData"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
          break;
    }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.63 Position error (Table 6.93: Position error information element contents)
function get_PosErr ($PDUbin, $StringPos)
{
    // Position error 3 Bit [0-7]
    $ret=array();
    $SubStrLen=3;
    $ret["PosErr"] = array();
    $ret["PosErr"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["PosErr"]["value"]) {
        case 0:        // less than 2 m
          $ret["PosErr"]["name"] = "less than 2 m";
          break;
        case 1:        // less than 20 m
          $ret["PosErr"]["name"] = "less than 20 m";
          break;
        case 2:        // less than 200 m
          $ret["PosErr"]["name"] = "less than 200 m";
          break;
        case 3:        // less than 2 km
          $ret["PosErr"]["name"] = "less than 2 km";
          break;
        case 4:        // less than 20 km
          $ret["PosErr"]["name"] = "less than 20 km";
          break;
        case 5:        // less than or equal to 200 km
          $ret["PosErr"]["name"] = "less than or equal to 200 km";
          break;
        case 6:        // more than 200 km
          $ret["PosErr"]["name"] = "more than 200 km";
          break;
        case 7:        // Position error not known
          $ret["PosErr"]["name"] = "Position error not known";
          break;
        }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.17 Horizontal velocity (Table 6.51: Examples of horizontal velocity information element contents)
function get_HorVeloc ($PDUbin, $StringPos)
{
    // Horizontal velocity 7 Bit [0-127]
    $ret=array();
    $SubStrLen=7;
    $ret["HorVeloc"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    if (($ret["HorVeloc"] > 28) && ($ret["HorVeloc"] < 125)) { $ret["HorVeloc"] = round(16 * pow(1.038,$ret["HorVeloc"] - 13)); }
    if ($ret["HorVeloc"] == 126) { $ret["HorVeloc"] = "More than 1 043 km/h"; }
    if ($ret["HorVeloc"] == 127) { $ret["HorVeloc"] = "not known"; }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.5 Direction of travel (Table 6.45: Direction of travel information element contents)
function get_DirOfTravel ($PDUbin, $StringPos)
{
    // Direction of travel 4 Bit [0-15]
    $ret=array();
    $SubStrLen=4;
    $ret["DirOfTravel"] = 22.5 * bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $ret["StringPos"] = $StringPos + $SubStrLen;
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
function get_ReqResp ($PDUbin, $StringPos)
{
    // Request/Response 1 Bit [0-1]
    $ret=array();
    $SubStrLen=1;
    $ret["ReqResp"]=array();
    $ret["ReqResp"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["ReqResp"]["value"]) {
        case 0:        // Request
          $ret["ReqResp"]["name"] = "Request";
          break;
        case 1:        // Response
          $ret["ReqResp"]["name"] = "Response";
          break;
      }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.65 Report type (Table 6.95: Report type information element contents)
function get_RepType ($PDUbin, $StringPos)
{
    // Report type 2 Bit [0-3]
    $ret=array();
    $SubStrLen=2;
    $ret["RepType"]=array();
    $ret["RepType"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    switch($ret["RepType"]["value"]) {
        case 0:        // Long location report preferred with no time information
          $ret["RepType"]["name"] = "Long location report preferred with no time information";
          break;
        case 1:        // Long location report preferred with time type "Time elapsed"
          $ret["RepType"]["name"] = "Long location report preferred with time elapsed";
          break;
        case 2:        // Long location report preferred with time type "Time of position"
          $ret["RepType"]["name"] = "Long location report preferred with time of position";
          break;
        case 3:        // Short location report preferred
          $ret["RepType"]["name"] = "Short location report preferred";
          break;
      }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.43 Location information destination (Table 6.73: Location information destination information element contents)
function get_LocInfoDest ($PDUbin, $StringPos)
{
    // 6.3.2 Address or identification type (Table 6.32: Address or identification type information element contents)
    // Address or identification type 4 Bit [0-15]
    $ret=array();
    $SubStrLen=4;
    $ret["LocInfoDest"]=array();
    $ret["LocInfoDest"]["value"] = bindec(substr("$PDUbin",$StringPos,$SubStrLen));
    $StringPos+=$SubStrLen;
    switch($ret["LocInfoDest"]["value"]) {
        case 0:        // No terminal or location identification available
          $ret["LocInfoDest"]["name"] = "No terminal or location identification available";
          break;
        case 1:        // SSI
          $SubStrLen=24;
          $ret["LocInfoDest"]["name"] = "SSI";
          $ret["LocInfoDest"]["addr"] = bindec(substr($PDUbin,$StringPos,$SubStrLen));
          break;
        case 2:        // SSI and MNI
          $ret["LocInfoDest"]["name"] = "SSI and MNI";
          break;
        case 3:        // IP address (Version 4) RFC 791 [3]
          $ret["LocInfoDest"]["name"] = "IP address (Version 4) RFC 791 [3]";
          break;
        case 4:        // IP address (Version 6) RFC 3513 [4]
          $ret["LocInfoDest"]["name"] = "IP address (Version 6) RFC 3513 [4]";
          break;
        case 5:        // Reserved
          $ret["LocInfoDest"]["name"] = "Reserved";
          break;
        case 6:        // Reserved
          $ret["LocInfoDest"]["name"] = "Reserved";
          break;
        case 7:        // Reserved
          $ret["LocInfoDest"]["name"] = "Reserved";
          break;
        case 8:        // External subscriber number 
          $ret["LocInfoDest"]["name"] = "External subscriber number";
          break;
        case 9:        // SSI and External subscriber number
          $ret["LocInfoDest"]["name"] = "SSI and External subscriber number";
          break;
        case 10:    // SSI and MNI and External subscriber number
          $ret["LocInfoDest"]["name"] = "SSI and MNI and External subscriber number";
          break;
        case 11:    // Name server type name
          $ret["LocInfoDest"]["name"] = "Name server type name";
          break;
        case 12:    // Name, free format
          $ret["LocInfoDest"]["name"] = "Name, free format";
          break;
        case 13:    // Reserved
          $ret["LocInfoDest"]["name"] = "Reserved";
          break;
        case 14:    // Reserved
          $ret["LocInfoDest"]["name"] = "Reserved";
          break;
        case 15:    // Reserved
          $ret["LocInfoDest"]["name"] = "Reserved";
          break;
      }
    $ret["StringPos"] = $StringPos + $SubStrLen;
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.2.1  SHORT LOCATION REPORT PDU (Table 6.1: SHORT LOCATION REPORT PDU contents)
function get_ShortLocRep ($PDUbin, $StringPos)
{
    $ret=array();
    foreach(get_TimeElapsed($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_LocationPoint($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_PosErr($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_HorVeloc($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_DirOfTravel($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_TypeOfAddData($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.2.16 IMMEDIATE LOCATION REPORT REQUEST PDU (Table 6.16: IMMEDIATE LOCATION REPORT REQUEST PDU contents)
function get_ImmLocReq ($PDUbin, $StringPos)
{
    $ret=array();
    foreach(get_ReqResp($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_RepType($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_LocInfoDest($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.2.2  LONG LOCATION REPORT PDU (Table 6.2: LONG LOCATION REPORT PDU contents)
function get_LongLocRep ($PDUbin, $StringPos)
{
    $ret=array();
    foreach(get_TimeData($PDUbin,$StringPos) as $key => $value) { $ret[$key] = $value; }
    foreach(get_LocationData($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_VelocityData($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_AckReq($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    foreach(get_TypeOfAddData($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    //foreach(get_LocMesRef($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    //foreach(get_ResCode($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }    
    //foreach(get_SDStyp1Val($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    //foreach(get_StatusVal($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
    return $ret;
}

// ETSI TS 100 392-18-1 V1.6.1
// 6.3.61 PDU type (Table 6.91: PDU type information element contents)
function get_LipPDU ($PDUbin, $StringPos)
{
    // PDU type 2 Bit [0-3]
    $ret=array();
    $ret["StringPos"] = $StringPos;
    $SubStrLen=2;
    $ret["PduType"] = array();
           $ret["PduType"]["value"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"]+=$SubStrLen;
    switch($ret["PduType"]["value"]) {
        case 0:            // Short location report
          $ret["PduType"]["name"] = "Short location report";
          foreach(get_ShortLocRep($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        case 1:            // Location protocol PDU with extension
          $ret["PduType"]["name"] = "Location protocol PDU with extension";
          // PDU type extension 4 Bit [0-15]
          // ETSI TS 100 392-18-1 V1.6.1
          // 6.3.62  PDU type extension (Table 6.92: PDU type extension information element contents)
          $SubStrLen=4;
          $ret["PduTypeExt"] = array();
          $ret["PduTypeExt"]["value"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
          $ret["StringPos"]+=$SubStrLen;
          switch($ret["PduTypeExt"]["value"]) {
              case 1:    // Immediate location report request
                $ret["PduTypeExt"]["name"] = "Immediate location report request";
                foreach(get_ImmLocReq($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
                break;
              case 3:    // Long location report
                $ret["PduTypeExt"]["name"] = "Long location report";
                foreach(get_LongLocRep($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }                
                break;
              case 4:    // Location report acknowledgement
                $ret["PduTypeExt"]["name"] = "Location report acknowledgement";
                break;
              case 5:    // Basic location parameters request/response
                $ret["PduTypeExt"]["name"] = "Basic location parameters request/response";
                break;
              case 6:    // Add/modify trigger request/response
                $ret["PduTypeExt"]["name"] = "Add/modify trigger request/response";
                break;
              case 7:    // Remove trigger request/response
                $ret["PduTypeExt"]["name"] = "Remove trigger request/response";
                break;
              case 8:    // Report trigger request/response
                $ret["PduTypeExt"]["name"] = "Report trigger request/response";
                break;
              case 9:    // Report basic location parameters request/response
                $ret["PduTypeExt"]["name"] = "Report basic location parameters request/response";
                break;
              case 10:    // Location reporting enable/disable request/response
                $ret["PduTypeExt"]["name"] = "Location reporting enable/disable request/response";
                break;
              case 11:    // Location reporting temporary control request/response
                $ret["PduTypeExt"]["name"] = "Location reporting temporary control request/response";
                break;
              case 12:    // Backlog request/response
                $ret["PduTypeExt"]["name"] = "Backlog request/response";
                break;
              default:
                $ret["PduTypeExt"]["name"] = "Reserved (value=".$ret["PduTypeExt"]["value"].")";
          }
          break;
        default:        // Reserved for further extension, will not be used in phase 1.
          $ret["PduType"]["name"] = "Reserved for further extension (value=".$ret["PduType"]["value"].")";
      }
      return $ret;
}
?>
