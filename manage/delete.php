<?php
   
require_once("../../inc/functions.inc");
$user = new User;
if(!$user->isLoggedIn) {
    die(header("Location: /login.php"));
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/style.css?v=<?=time()?>">
    <title>Manage</title>
</head>
<body>
    <header>
    <ul>
        <li><a href="/index.php">Home</a></li>
        <li><a href="/photos/index.php">Photos</a></li>
        <li><a href="/calendar/index.php">Calendar</a></li>
        <li><a href="/account/index.php">Account</a></li>
<?php
    if($user->perm == "m") {
        echo "<li><a class='active' href='/manage/index.php'>Manage</a></li>";
    }
?>
        <li style="float:right"><a href="/logout.php">Logout</a></li>
    </ul>
    </header>
    <aside>
    <ul>
        <li><a href="/manage/index.php">Link Users</a></li>
        <li><a href="/manage/frames.php">Add Frames</a></li>
        <li><a href="/manage/delete.php">Delete Frames</a></li>
    </ul>
    </aside>
    <div class="content-small">
        <header>
<?php print "<h1>Welcome {$user->firstName}</h1>\n"; ?>
        </header>
    </div>
    <div class="content-small">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="deleteForm">
    <table>
    <tr><th>Your Frames</th><th>Delete?</th></tr>
<?php
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $user = new User;
    $sql = "SELECT * from Managers where email='{$user->email}'";
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            echo "<tr><td style='height:100%'>".$id."</td>";
            echo "<td><input type='checkbox' id='".$id."' name='deleteForm[]' value='".$id."'/><label for='".$id."'>Delete?</label></td></tr>";
        }
    } else {
        echo "<tr><td colspan=2>0 Results</td></tr>";
    }
    $conn->close();
?>  
    </form>
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

