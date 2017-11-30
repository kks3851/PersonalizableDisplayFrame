<?php

class User{
    
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $perm;
    public $isLoggedIn = false;

    function __construct() {
        if(session_id() == "") {
            session_start();
        }
        if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true) {
            $this->_initUser();
        }
    } // end function __construct

    public function authenticate($user, $pass) {
        if (session_id() == "") {
            session_start();
        }
        $_SESSION['isLoggedIn'] = false;
        $this->isLoggedIn = false;
        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($mysqli->connect_errno) {
            error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
            return false;
        }
        $safeUser = $mysqli->real_escape_string($user);
        $incomingPassword = $mysqli->real_escape_string($pass);
        $query = "SELECT * from Users WHERE email = '{$safeUser}'";
        if (!$result = $mysqli->query($query)) {
            error_log("Cannot retrieve account for {$user}");
            return false;
        }
        // Will only be one row, so no while() loop needed
        $row = $result->fetch_assoc();
        $dbPassword = $row['password'];
        $decryptedPassword = crypt($incomingPassword, $dbPassword);
        
        if(!hash_equals($dbPassword, crypt($incomingPassword, $dbPassword))) {
            error_log("Passwords for {$user} don't match");
            error_log("incomingPassword: {$incomingPassword}");
            error_log("decryptedPassword: {$decryptedPassword}");
            error_log("dbPassword: {$dbPassword}");
            return false;
        }

        $this->id = $row['id'];
        $this->firstName = $row['firstname'];
        $this->lastName = $row['lastname'];
        $this->email = $row['email'];
        $this->perm = $row['permission'];
        $this->isLoggedIn = true;

        $this->_setSession();

        return true;
    } // end function authenticate
    
    private function _setSession() {
        if (session_id() == '') {
            session_start();
        }
        
        $_SESSION['id'] = $this->id;
        $_SESSION['firstName'] = $this->firstName;
        $_SESSION['lastName'] = $this->lastName;
        $_SESSION['email'] = $this->email;
        $_SESSION['perm'] = $this->perm;
        $_SESSION['isLoggedIn'] = $this->isLoggedIn;

    } // end function _setSession

    private function _initUser() {
        if (session_id() == '') {
            session_start();
        }

        $this->id = $_SESSION['id'];
        $this->firstName = $_SESSION['firstName'];
        $this->lastName = $_SESSION['lastName'];
        $this->email = $_SESSION['email'];
        $this->perm = $_SESSION['perm'];
        $this->isLoggedIn = $_SESSION['isLoggedIn'];
    } // end function _initUser

    public function logout() {
        $this->isLoggedIn = false;

        if(session_id() == '') {
            session_start();
        }

        $_SESSION['isLoggedIn'] = false;
        foreach ($_SESSION as $key => $value) {
            $_SESSION[$key] = "";
            unset($_SESSION[$key]);
        }

        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $cookieParameters = session_get_cookie_params();
            setcookie(session_name(), '', time() - 28800, $cookieParameters['path'], $cookieParameters['domain'], $cookieParameters['secure'], $cookieParameters['httponly']);
        }

        session_destroy();

    } // end function logout
} // end class User
