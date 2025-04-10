<?php

// ETSI EN 300 392-2 V3.4.1
// 29.5.2.3 Simple text messaging (Table 29.27: Simple text messaging PDU contents)
function make_StextPDU ($PDUelements)
{
    $ret = $PDUelements["PDUbin"];
    // Reserved 1 Bit - value shall be set to "0"
    $ret .= "0";
    // Text coding scheme 7 Bit [0-127]
    $ret .= make_TxtCodSch($PDUelements);
    // Text variable - text shall be encoded as defined in the text coding scheme information element
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
        // String aus der Hex-Zeichenkette auf Basis der verwendeten Codepage erzeugen
        $ret .= hextobin(bin2hex(mb_convert_encoding($PDUelements["Text"], $TxtCodSch_name, mb_internal_encoding())));
    }
    return $ret;
}
?>

