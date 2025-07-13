<?php
function decode_STATUS ($arg)
{
    $status_declaration = yaml_parse_file(dirname(__FILE__) . '/STATUS.declaration.yml');
    $key = array_search($arg, array_column($status_declaration['tetra'], 'status'));
    if ($key) {
        $ret = $status_declaration['tetra'][$key];
    } else {
        $ret = array();
        $ret['status'] = $arg;
        $ret['declaration'] = 'not declared';
    }
    return $ret;
}
?>
