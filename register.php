<?php require_once("../inc/functions.inc"); ?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/register.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>Register</title>
</head>
<body>
    <header>
        <ul>
            <li><a href="/index.php">Home</a></li>
            <li style="float:right;"><a href="/login.php">Have an account? &nbsp Login</a></li>
        </ul>
    </header>
    <div class="content">
    <form id="userForm" method="POST" action="register-process.php">
    <div>
        <h1>Registration Info</h1>
        <fieldset>
        <div id="errorDiv">
<?php
    if (isset($_SESSION['error']) && isset($_SESSION['formAttempt'])) {
        unset($_SESSION['formAttempt']);
        print "Errors encountered <br />\n";
        foreach ($_SESSION['error'] as $error) {
            print $error ."<br />\n";
        } // end foreach
    } // end if
?>
        </div>
        <label for="fname">First Name:* </label>
        <input type="text" id="fname" name="fname">
        <span class="errorFeedback errorSpan" id="fnameError">First Name is required</span>
        <br />
        <label for="lname">Last Name:* </label>
        <input type="text" id="lname" name="lname">
        <span class="errorFeedback errorSpan" id="lnameError">Last Name is required</span>
        <br />
        <label for="email">Email:* </label>
        <input type="text" id="email" name="email">
        <span class="errorFeedback errorSpan" id="emailError">Email is required</span>
        <br />
        <label for="password1">Password:* </label>
        <input type="password" id="password1" name="password1">
        <span class="errorFeedback errorSpan" id=password1Error">Password required</span>
        <br />
        <label for="password2">Verify Password:* </label>
        <input type="password" id="password2" name="password2">
        <span class="errorFeedback errorSpan" id=password2Error">Passwords don't match</span>
        <br />
        <label for="u">User Type:</label>
        <input type="radio" name="perm" id="u" value="u">
        <label for="u">User</label>
        <input type="radio" name="perm" id="m" value="m">
        <label for="m">Manager</label>
        <span class="errorFeedback errorSpan permError" id="permError">Please choose an option</span>
        <br />
        <input type="submit" id="submit" name="submit">
        </fieldset>
    </div>
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
