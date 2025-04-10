#!/usr/bin/php
<?php

require_once('TetraSDS/PDU.encode.php');

$longopts  = array(
    "ProtoIdent:",		// Required value
    "PduType:",    		// LIP -> PDU type 2 Bit [0-3]
    "PduTypeExt:",		// LIP -> PDU type extension 4 Bit [0-15]
    "TimeElapsed:",		// LIP -> Time elapsed 2 Bit [0-3]
    "Longitude:",		// LIP -> Longitude 25 Bit
    "Latitude:",		// LIP -> Latidude 24 Bit
    "PosErr:",			// LIP -> Position error 3 Bit [0-7]
    "HorVeloc:",		// LIP -> Horizontal velocity 7 Bit [0-127]
    "DirOfTravel:",		// LIP -> Direction of travel 4 Bit [0-15]
    "TypeOfAddData:",		// LIP -> Type of additional data 1 Bit [0-1]
    "ReasonForSending:",	// LIP -> Reason for sending 8 Bit [0-255]
    "ReqResp:",			// LIP -> Request/Response 1 Bit [0-1]
    "RepType:",			// LIP -> Report type 2 Bit [0-3]
    "AddrType:",		// LIP -> Address or identification type 4 Bit [0-15]
    "addr:",			// LIP -> Address or identification (length variable)
    "MsgType:",			// TXT -> Message type 4 Bit [0-15]
    "DelivRepReq:",		// TXT -> Delivery report request 2 Bit [0-3]
    "ShrtFmRep:",		// TXT -> short form report 1 Bit [0-1]
    "StorFwd:",			// TXT -> Storage/forward control 1 Bit [0-1]
    "MsgRef:",			// TXT -> Message reference 8 Bit [0-255]
    "TimStmpUsd:",		// TXT -> Timestamp used 1 Bit [0-1]
    "TxtCodSch:",		// TXT -> Text coding scheme 7 Bit [0-127]
    "Text:",			// TXT -> User data - Bit Length variable
);

$PDUelements=array();

foreach(getopt("", $longopts) as $key => $value) $PDUelements[$key] = $value;

echo PDU_encode($PDUelements);

?>
