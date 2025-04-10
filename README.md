# TetraSDS
A collection of PHP functions for handling Tetra SDS PDUs.

## Usage example

SDSencode.php --ProtoIdent 10 --PduType 0 --TimeElapsed 0 --PosErr 7 --HorVeloc 127 --DirOfTravel 15 --TypeOfAddData 0 --ReasonForSending 20 --Longitude 13.000000 --Latitude 52.000000

SDSencode.php --ProtoIdent 130 --MsgType 0 --DelivRepReq 0 --ShrtFmRep 0 --StorFwd 0 --MsgRef 255 --TimStmpUsd 0 --TxtCodSch 1 --Text "Hallo Welt!"
