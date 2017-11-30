<?php

require_once('../../inc/functions.inc');

$id = $_GET['id'];
$email = $_GET['email'];

$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$sql = "SELECT * FROM Photos WHERE email='{$email}'";
$result = $link->query($sql);
$row = $result->fetch_assoc();
$image = "image".$id;
header("Content-type: image/jpeg");
echo $row[$image];

?>
