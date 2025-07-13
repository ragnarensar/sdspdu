<?php
function decode_STATUS ($arg)
{
    $status_declaration = yaml_parse_file(dirname(__FILE__) . '/STATUS.declaration.yml');
    return $status_declaration['tetra'][array_search($arg, array_column($status_declaration['tetra'], 'status'))];
}
?>
