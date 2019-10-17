<!DOCTYPE html>

<html lang="en">
<?php
//START A SESSION
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="home.css" />
    <title>Edit Article</title>
</head>
<!-- CREATE THE HEADER AND NAVIGATION BAR -->

<body>
    <h1 class="header">
        <a href="http://ec2-18-234-109-238.compute-1.amazonaws.com/home.php">
            <img src="fakenews.jpg" alt="Fake News Logo">
            <!--from http://www.pngmart.com/image/tag/fake -->
        </a>
        Welcome to Fake News </h1>
    <div class="ulnav">
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/home.php">Home</a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/computer_science.php">Computer Science </a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/politics.php">Politics</a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/other.php">Other</a>
        <?php
        //GET USERNAME
        $username = (string) $_SESSION['username'];
        //IF THE USER IS LOGGED IN THEN DISPLAY A LINK TO THEIR HOMEPAGE AND A LOGOUT BUTTON
        if (isset($_SESSION['username'])) {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/logout.php'>Logout</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/userhome.php'>Welcome $username</a>");} 
        //IF THE USER IS NOT LOGGED IN DISPLAY A LINK TO THE LOGIN PAGE
        else {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login</a>");
        } ?>
    </div>
<!-- EDIT ARTICLE -->
    <h3>
        Edit your Comment:
    </h3>
    <!--CREATE A FORM TO SEND THE NEW DATA TO EDIT_COMMENT.PHP WHERE IT WILL UPDATE THE DATABASE-->
    <form id='edit_comment' action='edit_comment.php' method='post'>
        <!--CREATE A DELETE ARTICLE BUTTON-->
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <input type='checkbox' name='delete' value='yes'>Delete Comment
    </form>
    <?php 
    //GET USERNAME AND POST ID
    $username =  $_SESSION['username'];
    $post_id =  $_POST['post_id'];
    $title_id=  $_POST['title_id'];
    require 'database.php';
    //GET THE CURRENT COMMENT DATA
    $stmt = $mysqli->prepare("select article_id, comment, posted from comments where post_id='$post_id' order by posted desc");
    //Bind Parameter
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    //BIND THE ARTICLE DATA TO VARIABLES BELOW
    $stmt->bind_result($article_id, $comment, $posted);
    $stmt->fetch();
    //CREATE TEXT BOXES THAT DISPLAY THE CURRENT COMMENT
    //LINK THESE TO THE EDIT_COMMENT FORM ABOVE SO DATA CAN BE SENT TO EDIT_ARTICLE
    printf(
            "<textarea rows='10' cols='80' name='comment' form='edit_comment' >%s</textarea> \n
             <input type='hidden' name='post_id' form='edit_comment' value='%s'>
             \n",
        htmlspecialchars($comment),
        htmlspecialchars($post_id)
    );
    $stmt->close();
    ?>
    <input type='hidden' name='title_id' value=$title_id form='edit_comment'>
    <!--SUBMIT THE EDIT_COMMENT DATA TO EDIT_COMMENT.PHP-->
    <input type='submit' name='submit' form='edit_comment'>
    
</body>
</html>