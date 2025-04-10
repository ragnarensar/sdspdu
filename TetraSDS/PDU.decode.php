<?php
require_once('PDU.commonfunc.php');
require_once('PDU.decode.0A.php'); // Location Information Protocol
require_once('PDU.decode.82.php'); // Text Messaging
require_once('PDU.decode.C3.php'); // Call-Out

// ETSI EN 300 392-2 V3.4.1
// 29.4.1  PDU general structure (Table 29.10: PDU layout)
// 29.4.3.9 Protocol identifier (Table 29.21: Protocol identifier information element contents)
function decode_PDU ($PDU)
{
    $ret=array();
    $PDUbin=hextobin($PDU);
    // Protocol Identifyer 8 Bit [0-255]
    $ret["StringPos"]=0;
    $SubStrLen=8;
    $ret["ProtoIdent"] = array();
    $ret["ProtoIdent"]["value"] = bindec(substr("$PDUbin",$ret["StringPos"],$SubStrLen));
    $ret["StringPos"]+=$SubStrLen;
    switch($ret["ProtoIdent"]["value"])
    {
        case 1:        // Over The Air re-Keying for end to end encryption        
          $ret["ProtoIdent"]["name"] = "OTAK";
          break;
        case 2:        // Simple Text Messaging        
          $ret["ProtoIdent"]["name"] = "Simple Text Messaging";
          break;
        case 3:        // Simple location system        
          $ret["ProtoIdent"]["name"] = "Simple location system";
          break;
        case 4:        // Wireless Datagram Protocol WAP
          $ret["ProtoIdent"]["name"] = "Wireless Datagram Protocol WAP";
          break;
        case 5:        // Wireless Control Message Protocol WCMP
          $ret["ProtoIdent"]["name"] = "Wireless Control Message Protocol WCMP";
          break;
        case 6:        // Managed DMO
          $ret["ProtoIdent"]["name"] = "Managed DMO";
          break;
        case 7:        // PIN authentication
          $ret["ProtoIdent"]["name"] = "PIN authentication";
          break;
        case 8:        // End-to-end encrypted message
          $ret["ProtoIdent"]["name"] = "End-to-end encrypted message";
          break;
        case 9:        // Simple immediate text messaging
          $ret["ProtoIdent"]["name"] = "Simple immediate text messaging";
          break;
        case 10:    // Location information protocol        
          $ret["ProtoIdent"]["name"] = "Location Information Protocol";
          foreach(get_LipPDU($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        case 11:    // Net Assist Protocol (NAP)
          $ret["ProtoIdent"]["name"] = "Net Assist Protocol (NAP)";
          break;
        case 12:    // Concatenated SDS message
          $ret["ProtoIdent"]["name"] = "Concatenated SDS message";
          break;
        case 13:    // DOTAM
          $ret["ProtoIdent"]["name"] = "DOTAM";
          break;
        case 130:    // Text Messaging
          $ret["ProtoIdent"]["name"] = "Text Messaging";
          foreach(get_TextPDU($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        case 131:    // Location system
          $ret["ProtoIdent"]["name"] = "Location system";
          break;
        case 132:    // Wireless Datagram Protocol WAP
          $ret["ProtoIdent"]["name"] = "Wireless Datagram Protocol WAP";
          break;
        case 133:    // Wireless Control Message Protocol WCMP
          $ret["ProtoIdent"]["name"] = "Wireless Control Message Protocol WCMP";
          break;
        case 134:    // Managed DMO
          $ret["ProtoIdent"]["name"] = "Managed DMO";
          break;
        case 136:    // End-to-end encrypted message
          $ret["ProtoIdent"]["name"] = "End-to-end encrypted message";
          break;
        case 137:    // Immediate text messaging
          $ret["ProtoIdent"]["name"] = "Immediate text messaging";
          break;
        case 138:    // Message with User Data Header
          $ret["ProtoIdent"]["name"] = "Message with User Data Header";
          break;
        case 140:    // Concatenated SDS message
          $ret["ProtoIdent"]["name"] = "Concatenated SDS message";
          break;
        case 195:    // Call-Out
          $ret["ProtoIdent"]["name"] = "Call-Out";
          foreach(get_CallOutPDU($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        case 204:    // Home Mode Display
          $ret["ProtoIdent"]["name"] = "Home Mode Display";
          foreach(get_TextPDU($PDUbin,$ret["StringPos"]) as $key => $value) { $ret[$key] = $value; }
          break;
        default:
            if((($ret["ProtoIdent"]["value"] > 63) && ($ret["ProtoIdent"]["value"] < 127)) || (($ret["ProtoIdent"]["value"] > 191) && ($ret["ProtoIdent"]["value"] < 255)))
            {
                $ret["ProtoIdent"]["name"] = "user application defined (value=".$ret["ProtoIdent"]["value"].")";
            }
            else
            {
                $ret["ProtoIdent"]["name"] = "Reserved (value=".$ret["ProtoIdent"]["value"].")";
            }
    }
    return $ret;
}
?>
