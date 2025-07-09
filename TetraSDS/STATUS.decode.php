<?php
function decode_STATUS ($STATUS)
{
$yml_content = yaml_parse_file('TetraSDS/STATUS.declaration.yml');
print_r($yml_content);
}
?>
