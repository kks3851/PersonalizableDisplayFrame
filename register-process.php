<?php

require_once('../inc/functions.inc');

// prevent access if they haven't submitted the form.
if (!isset($_POST['submit'])) {
    die(header("Location: register.php"));
}

$_SESSION['formAttempt'] = true;

if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
$_SESSION['error'] = array();

$required = array("fname", "lname", "email", "password1", "password2", "perm");

// Check required fields
foreach ($required as $requiredField) {
    if(!isset($_POST[$requiredField]) || $_POST[$requiredField] == "") {
        $_SESSION['error'][] = $requiredField . " is required.";
    }
}

if (!preg_match('/^[\w .]+$/',$_POST['fname'])) {
    $_SESSION['error'][] = "First Name must be letters and numbers only.";
}
if (!preg_match('/^[\w .]+$/',$_POST['lname'])) {
    $_SESSION['error'][] = "Last Name must be letters and numbers only.";
}
if ($_POST['password1'] != $_POST['password2']) {
    $_SESSION['error'][] = "Passwords don't match";
}

// final disposition
if (count ($_SESSION['error']) > 0) {
    die(header("Location: register.php"));
} else {
    if(registerUser($_POST)) {
        unset($_SESSION['formatAttempt']);
        die(header("Location: success.php"));
    } else {
        error_log("Problem registering user: {$_POST['email']}");
        $_SESSION['error'][] = "Problem registering account";
        die(header("Location: register.php"));
    }
}

function registerUser($userData) {
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($mysqli->connect_errno) {
        error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
        return false;
    }
    $email = $mysqli->real_escape_string($_POST['email']);

    // check for an existing user
    $findUser = "SELECT id from Users where email = '{$email}'";
    $findResult = $mysqli->query($findUser);
    $findRow = $findResult->fetch_assoc();
    if (isset($findRow['id']) && $findRow['id'] != "") {
        $_SESSION['error'][] = "A user with that email address already exists";
        return false;
    }
    $firstName = $mysqli->real_escape_string($_POST['fname']);
    $lastName = $mysqli->real_escape_string($_POST['lname']);

    $cryptedPassword = crypt($_POST['password1']);
    $password = $mysqli->real_escape_string($cryptedPassword);

    if (isset($_POST['perm'])) {
        $perm = $mysqli->real_escape_string($_POST['perm']);
    } else {
        $perm = "";
    }
    $query = "INSERT INTO Users (firstname, lastname, email, password, permission) " .
        " VALUES ('{$firstName}', '{$lastName}', '{$email}', '{$password}', '{$perm}')";
    if ($mysqli->query($query)) {
        $id = $mysqli->insert_id;
        error_log("Inserted {$email} as ID {$id}");
        return true;
    } else {
        error_log("Problem inserting {$query}");
        return false;
    }
} // end function registerUser

?>
