<?php
   
require_once("../../inc/functions.inc");
$user = new User;
if(!$user->isLoggedIn) {
    die(header("Location: /login.php"));
}
// the upload function

function linkFrame() {
    $msg = '';
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $user = new User;
    $safeUser = $user->email;
    $info = $_POST['linkFrame'];
    if(isset($info[0]) && isset($info[1])) {
        $sql = "SELECT * FROM Frames where id='".$info[1]."';";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $msg .= "<p>Frame found!</p>";
            $sql = "UPDATE Frames SET email='".$info[0]."' WHERE id='".$info[1]."';";
            $result = $conn->query($sql);
            if ($result === true) {
                $msg .= "<p>Frames updated</p>";
            } else {
                $msg .= "<p>Error Updating frames</p>";
            }
        } else {
            $msg .= "<p>No Frame found</p>";
            $sql = "INSERT INTO Frames (email, id) VALUES ('".$info[0]."', '".$info[1]."');";
            $result = $conn->query($sql);
            if ($result === true) {
                $msg .= "<p>Frames added</p>";
            } else {
                $msg .= "<p>Error Adding Frames</p>";
            }
        }
    } 
    return $msg;
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
    <table>
        <tr><th>Update Settings</th></tr>
        <tr><td><form name="linkFrame" method="post" action="<?php echo  $_SERVER['PHP_SELF']; ?>">
            <p>Link Frame</p>
            <div><fieldset>
                <label for="email">Email: </label>
                <input type="text" id="email" name="linkFrame[]">
                <br />
                <label for="frameId">Frame ID: </label>
                <input type="text" id="frameId" name="linkFrame[]">
                <br />
                <input type="submit">
            </fieldset></div>
        </form></td></tr>
    </table>
    </div>
    <div class="content-small">
<?php
if(!isset($_POST['linkFrame'])) {
    echo "<p>No change info</p>";
} else {
    $msg = linkFrame();
    echo $msg;
}
?>
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

