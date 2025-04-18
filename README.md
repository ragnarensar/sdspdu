# TetraSDS

A collection of PHP functions for handling Tetra SDS PDUs.  

## Examples of use

### Creating a PDU for a location information SDS

#### Call with a list of seperate arguments
```
$> php SDSencode.php --ProtoIdent 10 --PduType 0 --TimeElapsed 0 --PosErr 7 --HorVeloc 127 --DirOfTravel 15 --TypeOfAddData 0 --ReasonForSending 20 --Longitude 13.000000 --Latitude 52.000000

0A0093E93A4FA4FFFFE14
```

#### Call with arguments in an escaped JSON string
```
$> php SDSencode.php --format json --input "{\"ProtoIdent\": 10, \"PduType\": 0, \"TimeElapsed\": 0, \"PosErr\": 7, \"HorVeloc\": 127, \"DirOfTravel\": 15, \"TypeOfAddData\": 0, \"ReasonForSending\": 20, \"Longitude\": 13.000000, \"Latitude\": 52.000000}"

0A0093E93A4FA4FFFFE14
```

### Creating a PDU for a text messaging SDS

#### Call with a list of seperate arguments
```
$> php SDSencode.php --ProtoIdent 130 --MsgType 0 --DelivRepReq 0 --ShrtFmRep 0 --StorFwd 0 --MsgRef 255 --TimStmpUsd 0 --TxtCodSch 1 --Text "What's up?"  

8200FF015768617427732075703F  
```
  
#### Call with arguments in an escaped JSON string
```
$> php SDSencode.php --format json --input "{\"ProtoIdent\": 130, \"MsgType\": 0, \"DelivRepReq\": 0, \"ShrtFmRep\": 0, \"StorFwd\": 0, \"MsgRef\": 255, \"TimStmpUsd\": 0, \"TxtCodSch\": 1, \"Text\": \"What's up?\"}"  

8200FF015768617427732075703F  
```

### Decoding a text messaging PDU

#### Call for an output to be read as an array in Bash
```
$> php SDSdecode.php --pdu 8200FF015768617427732075703F

PDUelements["StringPos"]="112"
PDUelements["ProtoIdent_value"]="130"
PDUelements["ProtoIdent_name"]="Text Messaging"
PDUelements["MsgType_value"]="0"
PDUelements["MsgType_name"]="SDS-TRANSFER"
PDUelements["DelivRepReq_value"]="0"
PDUelements["DelivRepReq_name"]="No delivery report requested"
PDUelements["ShrtFmRep_value"]="0"
PDUelements["ShrtFmRep_name"]="short form report"
PDUelements["StorFwd_value"]="0"
PDUelements["StorFwd_name"]="Storage/forward control information not available"
PDUelements["MsgRef"]="255"
PDUelements["TimStmpUsd_value"]="0"
PDUelements["TimStmpUsd_name"]="Timestamp not present"
PDUelements["TxtCodSch_value"]="1"
PDUelements["TxtCodSch_name"]="ISO-8859-1"
PDUelements["Text"]="What's up?"
```
#### Call for an output as a JSON string
```
$> php SDSdecode.php --pdu 8200FF015768617427732075703F --output json

{"StringPos":112,"ProtoIdent":{"value":130,"name":"Text Messaging"},"MsgType":{"value":0,"name":"SDS-TRANSFER"},"DelivRepReq":{"value":0,"name":"No delivery report requested"},"ShrtFmRep":{"value":0,"name":"short form report"},"StorFwd":{"value":0,"name":"Storage\/forward control information not available"},"MsgRef":255,"TimStmpUsd":{"value":0,"name":"Timestamp not present"},"TxtCodSch":{"value":1,"name":"ISO-8859-1"},"Text":"What's up?"}
```

### Decoding a location information PDU

#### Call for an output to be read as an array in Bash
```
$> php SDSdecode.php --pdu 0A0093E93A4FA4FFFFE14

PDUelements["StringPos"]="84"
PDUelements["ProtoIdent_value"]="10"
PDUelements["ProtoIdent_name"]="Location Information Protocol"
PDUelements["PduType_value"]="0"
PDUelements["PduType_name"]="Short location report"
PDUelements["TimeElapsed_value"]="0"
PDUelements["TimeElapsed_name"]="less than 5 s"
PDUelements["Longitude"]="12.999991"
PDUelements["Latitude"]="51.999997"
PDUelements["PosErr_value"]="7"
PDUelements["PosErr_name"]="Position error not known"
PDUelements["HorVeloc"]="not known"
PDUelements["DirOfTravel"]="337.5"
PDUelements["TypeOfAddData_value"]="0"
PDUelements["TypeOfAddData_name"]="Reason for sending"
PDUelements["ReasonForSending_value"]="20"
PDUelements["ReasonForSending_name"]="User application initiated"
```

#### Called for an output as a JSON string
```
$ php SDSdecode.php --pdu 0A0093E93A4FA4FFFFE14 --output json

{"StringPos":84,"ProtoIdent":{"value":10,"name":"Location Information Protocol"},"PduType":{"value":0,"name":"Short location report"},"TimeElapsed":{"value":0,"name":"less than 5 s"},"Longitude":12.999991,"Latitude":51.999997,"PosErr":{"value":7,"name":"Position error not known"},"HorVeloc":"not known","DirOfTravel":337.5,"TypeOfAddData":{"value":0,"name":"Reason for sending"},"ReasonForSending":{"value":20,"name":"User application initiated"}}
```
