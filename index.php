<?php
   
require_once("../inc/functions.inc");
$user = new User;
if(!$user->isLoggedIn) {
    die(header("Location: /login.php"));
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css?v=<?=time();?>">
    <title>Home</title>
</head>
<body>
    <header>
        <ul>
            <li><a class="active" href="index.php">Home</a></li>
            <li><a href="photos/index.php">Photos</a></li>
            <li><a href="calendar/index.php">Calendar</a></li>
            <li><a href="account/index.php">Account</a></li>
<?php
    if($user->perm == "m") {
        echo "<li><a href='manage/index.php'>Manage</a></li>";
    }
?>
            <li style="float:right"><a href="/logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="content">
        <header>
<?php 
    print "<h1>Welcome {$user->firstName}</h1>\n"; 
?>
        </header>
    </div>
    <footer>
        <ul>
            <li><a href="/faq.php">FAQ</a></li>
            <li><a href="/about.php">About</a></li>
            <li><a href="/contact.php">Contact</a></li>
        </ul>
    </footer>
</body>
</html>
