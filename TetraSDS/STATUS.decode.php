<?php
function decode_STATUS ($STATUS)
{
$yml_content = yaml_parse_file('STATUS.declaration.yml');
echo "$yml_content\n";
}
?>
