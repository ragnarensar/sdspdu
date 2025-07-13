<?php
function decode_STATUS ($STATUS)
{
    $yml_content = yaml_parse_file('TetraSDS/STATUS.declaration.yml');
    print_r($yml_content);
    $key = array_search($STATUS, array_column($yml_content['tetra'], 'status'));
    $ret = $yml_content['tetra'][$key];
    return $ret;
}
?>
