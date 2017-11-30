<?php
   
require_once("../../inc/functions.inc");
$user = new User;
if(!$user->isLoggedIn) {
    die(header("Location: /login.php"));
}
// the upload function

function deleteAccount() {
    $msg = '';
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $user = new User;
    $safeUser = $user->email;
    $sql = "DELETE FROM Users WHERE email='{$safeUser}'";
    $result = $conn->query($sql);
    if ($result->num_rows > 1) {
        $msg .= "<p>Error: too many results</p>";
        while($row = $result->fetch_assoc()) {
            $msg .= "<p>email: " . $row["email"]. "<br></p>";
        }
    } else {
        if ($result->num_rows < 1) {
            $msg .= "<p>0 results</p>";
        } else {
            $row = $result->fetch_assoc();
            $msg .= "<p>First Name:" . $row["firstname"] . "</p>";
            $msg .= "<p>Last Name:" . $row["lastname"] . "</p>";
            $msg .= "<p>Email:" . $row["email"] . "</p>";
        }
    }
    $sql = "DELETE FROM Photos WHERE email='{$safeUser}'";
    $result = $conn->query($sql);
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
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>    
    <label for="frameid">New Frame ID</label>
    <input type="text" name="frameid" />
    <input type="submit" name="submit" value="Add Frame" />
    </fieldset>
    </form>
<?php
if(isset($_POST['submit'])) {
    $newFrameId=$_POST['frameid'];
    $user = new User;
    $sql = "INSERT INTO Managers (email, id) VALUES ('{$user->email}', '{$newFrameId}')";
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $result = $conn->query($sql);
    if ($result === true) {
        echo "<p>Added Frame ID = " .$newFrameId. "</p>";
    } else {
        echo "<p>Error adding Frame</p>";
    }
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

