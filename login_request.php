<!-- LOGIN REQUEST -->
<?php
// connect to database
require 'database.php';

$stmt = $mysqli->prepare("SELECT username, hashed_password from users WHERE username=?");

// Bind parameter
$user = (string) $_POST['username'];
$stmt->bind_param('s', $user);
$stmt->execute();

//Bind result
$stmt->bind_result($username, $hashed_password);
$stmt->fetch();

$pwd_guess = (string) $_POST['password'];
//Compare guess to actual password hash
if (password_verify($pwd_guess, $hashed_password)) {
    //Successful login
    session_start();
    $_SESSION['username'] = $user;

    //create randomly generated string once user successfully authenticates
    $_SESSION['token'] = bin2hex(random_bytes(32));
    header("Location: userhome.php");
    exit;
    //redirect now!
} 
else {
    //login failed redirect to login page
    header("Location: login.php");
    echo "<h1>Incorrect Password</h1>";
    exit;
}


?>