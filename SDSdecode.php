#!/usr/bin/php
<?php

require_once('TetraSDS/PDU.decode.php');

foreach(decode_PDU($argv[1]) as $key => $value)
{
	if (!(is_array($value))) {
		echo "PDUelements[\"".$key."\"]=\"".$value."\"\n";
	}
	else
	{
		foreach($value as $key2 => $value2)
		{
			 echo "PDUelements[\"".$key."_".$key2."\"]=\"".$value2."\"\n";
		}
	}
}
?>
