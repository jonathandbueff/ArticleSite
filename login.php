<!DOCTYPE html>
<html lang="en">
<!--START SESSION -->
<?php session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="home.css" />
    <title>Login</title>
</head>
<!-- CREATE HEADER AND NAVIGATIONK BAR -->
<body>
    <h1 class="header">
        <a href="http://ec2-18-234-109-238.compute-1.amazonaws.com/home.php">
            <img src="fakenews.jpg" alt="Fake News Logo">
            <!--from http://www.pngmart.com/image/tag/fake -->
        </a>
        Welcome to Fake News</h1>
    <div class="ulnav">
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/home.php">Home</a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/computer_science.php">Computer Science </a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/politics.php">Politics</a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/other.php">Other</a>
        <?php
        //GET USERNAME
        $username = (string) $_SESSION['username'];
        //IF THE USER IS LOGGED IN DISPLAY A LINK TO THEIR HOMEPAGE AND A LOGOUT BUTTON
        if (isset($_SESSION['username'])) {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/logout.php'>Logout</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/userhome.php'>Create/Edit Articles</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/profilepage.php'>Your Account</a>");}

            //IF THE USER IS NOT LOGGED IN DISPALY A LOGIN BUTTON    
            else {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login</a>");} 
            ?>
    </div>
    <div class="loginfields">
<!-- LOGIN -->        
        <h3>Login </h3>
        <!-- CREATE A FORM TO SEND THE INPUTTED LOGIN INFORMATION TO LOGIN_REQUEST.PHP -->
        <form action="login_request.php" method="post">
            <!--CREATE A USERNAME FIELD -->
            Username:<br>
            <input type="text" name="username"><br>
            <!--CREATE A PASSWORD FIELD -->
            Password:<br>
            <input type="password" name="password"><br>
            <input type="submit" value="Login">
        </form>
    </div>
    <br>
    <!--REGISTER -->
    <div class="loginfields">
        <h3>Register</h3>
        <!-- CREATE A FORM TO SEND REGISTRATION DATA TO REGISTER.PHP -->
        <form action="register.php" method="post">
            <!--CREATE FIELD FOR FIRST NAME, LAST NAME, EMAIL, USERNAME AND PASSWORD-->
            First Name: <br>
            <input type="text" name="first"><br>
            Last Name: <br>
            <input type="text" name="last"><br>
            Email Address: <br>
            <input type="text" name="email"><br>
            Username: <br>
            <input type="text" name="username"><br>
            Password: <br>
            <input type="password" name="password"><br>
            <input type="submit" value ="Register">
        </form>
    </div>
</body>
</html>