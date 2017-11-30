<?php
   
require_once("../../inc/functions.inc");
$user = new User;
if(!$user->isLoggedIn) {
    die(header("Location: /login.php"));
}

// the delete_photos function
function delete_photos($numPics) {
    $msg = '';
    $pics = $_POST['deleteForm'];
    $N = count($pics);
    $msg.= "<p> You selected " .$N. " pictures</p>";
    $newNumPics = $numPics;
    if ($newNumPics < 0) {
        $newNumPics = 0;
    }
    $msg .= "<p>New Num Pics: ".$newNumPics."</p>";
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user = new User;
    $safeUser = $user->email;
    for ($x=0; $x<$N; $x++) {
        $imageName = "image".$pics[$x];
        $sql = "UPDATE Photos SET {$imageName}=' ' WHERE email='{$safeUser}'";
        $result = $conn->query($sql);
        if($result === true) {
            $msg .= "<p>image".$pics[$x]." deleted</p>";
            $newNumPics = $newNumPics - 1;
            $sql = "UPDATE Photos SET numPics='{$newNumPics}'";
            $result = $conn->query($sql);
            if ($result === true) {
                $msg .= "<p>numPics updated to ".$newNumPics."</p>";
            } else {
                $msg .= "<p>ERROR updating numPics</p>";
            }
        } else {
            $msg .= "<p>ERROR deleting image".$pics[$x]."</p>";
        }
    }
    $sql = "UPDATE Photos SET numPics='{$newNumPics}'";
    $result = $conn->query($sql);
    if ($result === true) {
        $msg .= "<p>numPics updated to ".$newNumPics."</p>";
    } else {
        $msg .= "<p>ERROR updating numPics</p>";
    }
    return $msg;
}


// the upload function

function upload () {
    $maxsize = 10000000; //set to approx 10 MB
    //check associated error code
    if($_FILES['userfile']['error']==UPLOAD_ERR_OK) {
        //check whether file is uploaded with HTTP POST
        if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            //checks size of uploaded image on server side
            if( $_FILES['userfile']['size'] < $maxsize) {
               //checks whether uploaded file is of image type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if(strpos(finfo_file($finfo, $_FILES['userfile']['tmp_name']),"image")===0) {
                    // prepare the image for insertion
                    $imgData = addslashes(file_get_contents($_FILES['userfile']['tmp_name']));
                    // put the image in the db...
                    // database connection
                    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
                    // check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    
                    $user = new User;
                    $safeUser = $user->email;
                    $sql = "SELECT * FROM Photos WHERE email='{$safeUser}'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 1) {
                        echo "<p>Error: too many results</p>";
                        while($row = $result->fetch_assoc()) {
                            echo "<p>email: " . $row["email"]. "<br></p>";
                        }
                    } else {
                        if ($result->num_rows < 1) {
                            echo "<p>0 results</p>";
                            $sql = "INSERT INTO Photos (email) VALUES ('{$user->email}');";
                            $result = $conn->query($sql);
                            if ($result === TRUE) {
                                echo "<br> <p>Added photo record </p> <br>";
                            } else {
                                echo "<br> <p>Could not add user</p> <br>";
                            }
                        } else {
                            $row = $result->fetch_assoc();
                            echo "<p>email:" . $row["email"] . "</p>";
                            echo "<p>numPics:" . $row["numPics"] . "</p>";
                            echo "<p>counter:" . $row["counter"] . "</p>";
                        }

                        // Decide where to save photo
                        $sql = "SELECT * FROM Photos WHERE email='{$safeUser}'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $numPics = $row["numPics"];
                        if ($numPics < 0) {
                            $numPics = 0;
                        }
                        switch($numPics) {
                            case 0:
                                $msg = "<p>ADDED PHOTO 1</p>";
                                $sql = "UPDATE Photos SET image0='{$imgData}', numPics='1', counter='0' WHERE email='{$safeUser}';";
                                break;
                            case 1: 
                                $msg = "<p>ADDED PHOTO 2</p>";
                                $sql = "UPDATE Photos SET image1='{$imgData}', numPics='2', counter='0' WHERE email='{$safeUser}';"; 
                                break;
                            case 2: 
                                $msg = "<p>ADDED PHOTO 3</p>";
                                $sql = "UPDATE Photos SET image2='{$imgData}', numPics='3', counter='0' WHERE email='{$safeUser}';";
                                break;
                            case 3:
                                $msg = "<p>ADDED PHOTO 4</p>";
                                $sql = "UPDATE Photos SET image3='{$imgData}', numPics='4', counter='0' WHERE email='{$safeUser}';";
                                break;
                            case 4:
                                $msg = "<p>ADDED PHOTO 5</p>";
                                $sql = "UPDATE Photos SET image4='{$imgData}', numPics='5', counter='0' WHERE email='{$safeUser}';";
                                break;
                            case 5:
                                $msg = "<p>ERROR: Already have 5 Pics</p>";
                                $sql = "";
                                break;
                            default:
                                echo "<p>ERROR: Unexpected numPics</p>";
                        }
                        $result = $conn->query($sql);
                        if ($result === true) {
                            echo "<p>Added photo to Database</p>";
                        } else {
                            echo "<p>Error adding photo to Database</p>";
                        }
                        $conn->close();
                    }
                }
                else
                    $msg="<p>Uploaded file is not an image.</p>";
            }
             else {
                // if the file is not less than the maximum allowed, print an error
                $msg='<div>File exceeds the Maximum File limit</div>
                <div>Maximum File limit is '.$maxsize.' bytes</div>
                <div>File '.$_FILES['userfile']['name'].' is '.$_FILES['userfile']['size'].
                ' bytes</div><hr />';
                }
        }
        else
            $msg="File not uploaded successfully.";

    }
    else {
        $msg= file_upload_error_message($_FILES['userfile']['error']);
    }
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
    <title>Delete Photos</title>
</head>
<body>
    <header>
    <ul>
        <li><a href="/index.php">Home</a></li>
        <li><a class="active" href="/photos/index.php">Photos</a></li>
        <li><a href="/calendar/index.php">Calendar</a></li>
        <li><a href="/account/index.php">Account</a></li>
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
        <li><a href="/photos/index.php">Upload</a>
            <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
                <input name="userfile" id="userfile" type="file" style="display: none;"/>
                <input name="showfile" id="showfile" placeholder="0 files selected" onclick="document.getElementById('userfile').click();" />                
                <script type="text/javascript">
document.getElementById("userfile").onchange = function() {
    document.getElementById("showfile").value = this.value;
};
                </script>
                <input type="submit" value="Submit"/>
            </form>
        </li>
        <li><a href="select.php">Select</a></li>
        <li><a href="delete.php">Delete</a></li>
    </ul>
    </aside>
    <div class="content-small">
        <header>
<?php print "<h1>Welcome {$user->firstName}</h1>\n"; ?>
        </header>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<?php 
    $link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    } else {
        echo "<p> Connected to Database</p>";
        $user = new User;
        $safeUser = $user->email;
        $sql = "SELECT * FROM Photos WHERE email='{$safeUser}'";
        $result = $link->query($sql);

        if ($result->num_rows > 1) {
            echo "<p>Too many users found</p>";
        } else {
            if ($result->num_rows < 0) {
                echo "<p>No photos found for user</p>";
            } else {
                $row = $result->fetch_assoc();
                echo "<p>Email: " . $row['email'] . "</p>";
                echo "<p>numPics: " . $row['numPics'] . "</p>";
                echo "<p>counter: " . $row['counter'] . "</p>";
                $numPics = $row['numPics'];
                echo "<table>\n<tr style='height:25px;'>\n<th>Selected Images</th><th>Delete?</th>\n</tr>";
                for ($x=0; $x<$numPics; $x++) {
                    echo "<tr><td><img src='getImage.php?id=".$x."&email=".$safeUser."' /></td>";
                    echo "<td style='height:100%;'><input type='checkbox' id='".$x."' name='deleteForm[]' value='".$x."'><label for='".$x."'>Delete</label></td></tr>";
                }
                echo "<tr style='height:50px'><td colspan='2'><input type='submit'></td></tr>";
                echo "</table>";
            }
        }
    }

    if(!isset($_FILES['userfile'])) {
        echo '';
    } else {
        try {
            $msg = upload();
            echo $msg;
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
        die(header("Location: /photos/delete.php"));
    }

    if(!isset($_POST['deleteForm'])) {
        echo "<p>Nothing selected to be deleted</p>";
    } else {
        try {
            $msg = delete_photos($numPics);
            echo $msg;
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
        die(header("Location: /photos/delete.php"));
    }
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

