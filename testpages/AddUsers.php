<?php include "../../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the Users table exists. */
  VerifyUsersTable($connection, DB_DATABASE); 

  /* If input fields are populated, add a row to the Users table. */
  $firstname = htmlentities($_POST['FirstName']);
  $lastname = htmlentities($_POST['LastName']);
  $email = htmlentities($_POST['Email']);
  $password = htmlentities($_POST['Password']);
  $permission = htmlentities($_POST['Permission']);

  if (strlen($firstname) || strlen($lastname) || strlen($email) || strlen($password) || strlen($permission)) {
    AddUser($connection, $firstname, $lastname, $email, $password, $permission);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>First Name</td>
      <td>Last Name</td>
      <td>Email</td>
      <td>Password</td>
      <td>Permission</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="FirstName" maxlength="30" size="32" />
      </td>
      <td>
        <input type="text" name="LastName" maxlength="30" size="32" />
      </td>
      <td>
        <input type="text" name="Email" maxlength="50" size="52" />
      </td>
      <td>
        <input type="password" name="Password" maxlength="20" size="22" />
      </td>
      <td>
        <input type="radio" name="Permission" value="u" checked> User<br>
	<input type="radio" name="Permission" value="m"> Manager
      </td>
      <td>
        <input type="submit" value="Add User" >
        <input type="reset">
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Email</td>
    <td>Password</td>
    <td>Permission</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM Users"); 

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>",
       "<td>",$query_data[6], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add a user to the table. */
function AddUser($connection, $firstname, $lastname, $email, $password, $permission) {
   $f = mysqli_real_escape_string($connection, $firstname);
   $l = mysqli_real_escape_string($connection, $lastname);
   $e = mysqli_real_escape_string($connection, $email);
   $id = mysqli_real_escape_string($connection, $password);
   $p = mysqli_real_escape_string($connection, $permission);

   $query = "INSERT INTO Users (firstname, lastname, email, password, permission) VALUES ('$f', '$l', '$e', '$id', '$p');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding user data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyUsersTable($connection, $dbName) {
  if(!TableExists("Users", $connection, $dbName)) 
  { 
     echo("<p>Error: No User table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection, 
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
