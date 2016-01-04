#!/usr/bin/php

<?php
$file = json_decode(file_get_contents('guidelines.json'), true);
foreach ($file as $k => $v) {
    foreach ($v as $v1) {
        $value = json_encode($v1);
        $value = str_replace("'", "''", $value);
        $value = str_replace('\\', '\\\\', $value);
        echo "INSERT INTO `guidelines`(`state`,`data`)VALUES('$k','$value');\n";
    }
}

?>
