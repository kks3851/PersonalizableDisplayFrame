<?php

require_once("../inc/functions.inc");
$user = new User;
$user->logout();
die(header("Location: login.php"));

?>
