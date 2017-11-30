<?php

require_once('../inc/functions.inc');

$id = $_GET['id'];

if(!isset($_GET['next'])) {
    $next = "none";
} else {
    $next = $_GET['next'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Slideshow</title>
    <script src="js/arrow-key.js"></script>
</head>
<body>
    <div class="content">
        <header>
            <h1>CALENDAR PAGE</h1>
        </header>
<?php
    echo "<a id='prevLink' href='slideshow.php?id=".$id."&next=left'></a>";
    echo "<a id='nextLink' href='slideshow.php?id=".$id."&next=right'></a>";
    echo "<a id='upLink' href='calendar.php?id=".$id."&next=up'></a>";
    echo "<a id='downLink' href='slideshow.php?id=".$id."&next=down'></a>";
    echo "<a id='enterLink' href='slideshow.php?id=".$id."&next=enter'></a>";
    echo "<a id='backLink' href='slideshow.php?id=".$id."&next=back'></a>";
?>
</body>
</html>
