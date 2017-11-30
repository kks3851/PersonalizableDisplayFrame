<?php

require_once('../inc/functions.inc');

$id = $_GET['id'];

if(!isset($_GET['next'])) {
    $next = "none";
} else {
    $next = $_GET['next'];
}

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $sql = "SELECT * FROM Frames WHERE id='{$id}'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $sql = "SELECT * FROM Photos WHERE email='{$email}'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $numPics = $row['numPics'];
    $counter = $row['counter'];

    if ($next == "left") {
        if ($counter == 0) {
            $counter = $numPics - 1;
            if ($counter <= 0) {
                $counter = 0;
            }
        } else {
            $counter -= 1;
        }
    } else if ($next == "right") {
        if ($counter >= $numPics - 1) {
            $counter = 0;
        } else {
            $counter += 1;
        }
    } else {
        $counter = $counter;
    }
    
    $sql = "UPDATE Photos set counter='{$counter}' where email='{$email}'";
    $result = $conn->query($sql);
    //echo "<p>numPics: ".$numPics." counter: ".$counter." email: ".$email."</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Slideshow</title>
    <script src="js/arrow-key.js"></script>
</head>
<body>
<?php
    echo "<img src='/photos/getImage.php?id=".$counter."&email=".$email."' height=100% width=100% style='position:fixed;top:0;left:0;'/>";
    echo "<a id='prevLink' href='slideshow.php?id=".$id."&next=left'></a>";
    echo "<a id='nextLink' href='slideshow.php?id=".$id."&next=right'></a>";
    echo "<a id='upLink' href='calendar.php?id=".$id."&next=up'></a>";
    echo "<a id='downLink' href='slideshow.php?id=".$id."&next=down'></a>";
    echo "<a id='enterLink' href='slideshow.php?id=".$id."&next=enter'></a>";
    echo "<a id='backLink' href='slideshow.php?id=".$id."&next=back'></a>";
?>
</body>
</html>
