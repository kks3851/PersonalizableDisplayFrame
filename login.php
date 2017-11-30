<?php 

require_once("../inc/functions.inc");
$user = new User;
$user->logout();

?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>Login</title>
</head>
<body>
    <header>
        <ul>
            <li><a href="/index.php">Home</a></li>
            <li style="float:right"><a href="/register.php">No account? &nbsp Register</a></li>
        </ul>
    </header>
    <div class="content">
    <h1>Login</h1>
    <form id="loginForm" method="POST" action="login-process.php">
        <div>
            <fieldset>
            <div id="errorDiv">
<?php
    if (isset($_SESSION['error']) && isset($_SESSION['formAttempt'])) {
        unset($_SESSION['formAttempt']);
        print "Errors encountered<br />\n";
        foreach ($_SESSION['error'] as $error) {
            print $error . "<br />\n";
        } // end foreach
    } // end if
?>
            </div>
            <label for="email">Email Address:* </label>
            <input type="text" id="email" name="email">
            <span class="errorFeedback errorSpan" id="emailError">Email is required</span>
            <br />
            <label for="password">Password:* </label>
            <input type="password" id="password" name="password">
            <span class="errorFeedback errorSpan" id="passwordError">Password required</span>
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
