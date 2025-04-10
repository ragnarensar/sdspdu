<?php
require_once('PDU.commonfunc.php');
require_once('PDU.encode.02.php'); // "Simple Text Messaging"
require_once('PDU.encode.0A.php'); // "Location Information Protocol"
require_once('PDU.encode.82.php'); // "Text Messaging"

// ETSI EN 300 392-2 V3.4.1
// 29.4.1  PDU general structure (Table 29.10: PDU layout)
// 29.4.3.9 Protocol identifier 8 Bit (Table 29.21: Protocol identifier information element contents)
function PDU_encode ($PDUelements)
{
    switch($PDUelements["ProtoIdent"])
    {
        //case 1:        // OTAK - Over The Air re-Keying for end to end encryption        
          //break;
        case 2:        // Simple Text Messaging    
          $PDUelements["PDUbin"] = "00000010";
          $ret= make_StextPDU($PDUelements);    
          break;
        //case 3:        // Simple location system        
          //break;
        //case 4:        // Wireless Datagram Protocol WAP
          //break;
        //case 5:        // Wireless Control Message Protocol WCMP
          //break;
        //case 6:        // Managed DMO
          //break;
        //case 7:        // PIN authentication
          //break;
        //case 8:        // End-to-end encrypted message
          //break;
        //case 9:        // Simple immediate text messaging
          //break;
        case 10:    // Location information protocol        
          $PDUelements["PDUbin"] = "00001010";
          $ret= make_LipPDU($PDUelements);
          break;
        //case 11:    // Net Assist Protocol (NAP)
          //break;
        //case 12:    // Concatenated SDS message
          //break;
        //case 13:    // DOTAM
          //break;
        //case 122:    // Home Mode Display
          //break;
        case 130:    // Text Messaging
          $PDUelements["PDUbin"] = "10000010";
          $ret= make_TextPDU($PDUelements);
          break;
        //case 131:    // Location system
          //break;
        //case 132:    // Wireless Datagram Protocol WAP
          //break;
        //case 133:    // Wireless Control Message Protocol WCMP
          //break;
        //case 134:    // Managed DMO
          //break;
        //case 136:    // End-to-end encrypted message
          //break;
        case 137:    // Immediate text messaging
          $PDUelements["PDUbin"] = "10001001";
          $ret= make_TextPDU($PDUelements);
          break;
        //case 138:    // Message with User Data Header
          //break;
        //case 140:    // Concatenated SDS message
          //break;
        case 204:    // Home Mode Display
          $PDUelements["PDUbin"] = "11001100";
          $ret= make_TextPDU($PDUelements);
          break;
        //case 220:    // Home Mode Display
          //break;
    }
    return strtoupper(bintohex($ret));
}
?>
