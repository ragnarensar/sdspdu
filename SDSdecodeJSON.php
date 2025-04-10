#!/usr/bin/php
<?php

require_once('TetraSDS/PDU.decode.php');

$JSON_PDU = json_encode(decode_PDU($argv[1]),JSON_UNESCAPED_UNICODE);

echo $JSON_PDU;
?>
