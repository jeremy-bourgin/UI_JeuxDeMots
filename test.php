<?php
error_reporting(-1);

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

function test(): object
{
    $obj = new stdClass();
    $obj->a = "ok";

    $json = json_encode($obj);
    $result = json_decode($json);

    return $result;
}

$o = test();
echo $o->a;
