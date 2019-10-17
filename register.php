<?php
//START SESSION
session_start();
//ACCESS DATABASE
require 'database.php';
//SET VARIABLES

//filtering inputs
$first = (string) $_POST['first'];
$last = (string) $_POST['last'];
$email = (string) $_POST['email'];
$username = (string) $_POST['username'];
$password = (string) password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("SELECT username from users WHERE username=?");
// Bind parameter
$stmt->bind_param('s', $username);
$stmt->execute();
//Bind result
$stmt->bind_result($user);
$stmt->fetch();
if (!$user==null){
   echo '<h1>Username already in use, please choose another </h1>';
   printf ("<a href =login.php>Click here to return to registration page.</a>");
   exit();
}
//INSERT VARIABLES INTO A NEW ROW IN THE USERS TABLE
$stmt = $mysqli->prepare("insert into users (first_name, last_name, email_address, username, hashed_password) values (?,?,?,?,?)");
if (!$stmt) {
    printf("Query Prep Failed: %s \n", $mysqli->error);
    exit;
}
$stmt->bind_param('sssss', $first, $last, $email, $username, $password);
$stmt->execute();
$stmt->close();
?>
<?php
require 'database.php';
//HASHED PASSWORD FROM USERS TABLE   
$stmt = $mysqli->prepare("SELECT username, hashed_password from users WHERE username=?");
// Bind parameter
$user = (string) $_POST['username'];
$stmt->bind_param('s', $user);
$stmt->execute();
//Bind result
$stmt->bind_result($user, $hashed_password);
$stmt->fetch();
$password= (string) $_POST['password'];
//Check password with password in database
if (password_verify($password, $hashed_password)) {

    //Successful login
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['token'] = bin2hex(random_bytes(32));
    //REDIRECT TO USER HOMEPAGE
    header("Location: userhome.php");
    exit;} 
//login failed redirect to login page
else {
    header("Location: login.php");
    echo "<h1>Incorrect Password</h1>";
}
?>
