<?php
//contoh membaca isi data pada databases
header("Content-Type: application/json");
require_once $_SERVER['DOCUMENT_ROOT']."/1db/class.php";
$db=new WanDB("dbA");
echo(json_encode($db->getContent()));
?>