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

// file_upload_error_message
function file_upload_error_message($error_code) {
    switch($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/style.css?v=<?=time()?>">
    <title>Account</title>
</head>
<body>
    <header>
    <ul>
        <li><a href="/index.php">Home</a></li>
        <li><a href="/photos/index.php">Photos</a></li>
        <li><a href="/calendar/index.php">Calendar</a></li>
        <li><a class="active" href="/account/index.php">Account</a></li>
<?php
    if($user->perm == "m") {
        echo "<li><a href='/manage/index.php'>Manage</a></li>";
    }
?>
        <li style="float:right"><a href="/logout.php">Logout</a></li>
    </ul>
    </header>
    <aside>
    <ul>
        <li><a href="/account/index.php">Edit</a></li>
        <li><a href="/account/delete.php">Delete</a></li>
    </ul>
    </aside>
    <div class="content-small">
        <header>
<?php print "<h1>Welcome {$user->firstName}</h1>\n"; ?>
        </header>
    </div>
    <div class="content-small">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return confirm('Are you sure you want to do that?');">
    <input type="submit" name="submit" value="DELETE" />
    </form>
<?php
if(isset($_POST['submit'])) {
    $msg = deleteAccount();
    echo $msg;
    die(header("Location: /login.php"));
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

