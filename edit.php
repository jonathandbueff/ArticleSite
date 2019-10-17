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
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/userhome.php'>Create/Edit Articles/a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/profilepage.php'>Your Account</a>");}

        //IF THE USER IS NOT LOGGED IN DISPLAY A LINK TO THE LOGIN PAGE
        else {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login</a>");
        } ?>
    </div>
<!-- EDIT ARTICLE -->
    <h3>
        Edit your Article:
    </h3>
    <!--CREATE A FORM TO SEND THE NEW DATA TO EDIT_ARTICLE.PHP WHERE IT WILL UPDATE THE DATABASE-->
    <form id="edit_article" action="edit_article.php" method="post">
        <!--CREATE A DELETE ARTICLE BUTTON-->
        <input type='checkbox' name='delete' value='yes'>Delete Article
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <?php
    //GET USERNAME AND ARTICLE ID
    $username = (string) $_SESSION['username'];
    $title_id = (int) $_POST['title_id'];
    
        //check token
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }
    //ACCESS DATABASE
    require 'database.php';
    //GET THE CURRENT ARTICLE DATA
    $stmt = $mysqli->prepare("select title_id, title, subject, content, posted, link from articles where title_id='$title_id' order by posted desc");
    //Bind Parameter
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    //BIND THE ARTICLE DATA TO VARIABLES BELOW
    $stmt->bind_result($title_id, $title, $subject, $content, $posted, $link);
    $stmt->fetch();
    //INITIALIZE SUBJECT VARIABLES
    $compsci = "";
    $politics = "";
    $other = "";
    //CHECK THE SUBJECT OF THE ARTICLE
    if ($subject == 'Computer Science') {
        $compsci = "checked";
    } elseif ($subject == 'Politics') {
        $politics = "checked";
    } elseif ($subject == 'Other') {
        $other = "checked";
    }
    //CREATE TEXT BOXES THAT DISPLAY THE CURRENT ARTICLE
    //LINK THESE TO THE EDIT_ARTICLE FORM ABOVE SO DATA CAN BE SENT TO EDIT_ARTICLE
    printf(
            "<textarea rows='1' cols='100' name='title' form='edit_article'>%s</textarea> \n 
             <textarea rows='1' cols='100' name='link' form='edit_article'>%s</textarea> \n
             <input type='radio' name='subject' form='edit_article' value='Computer Science' $compsci>Computer Science
            <input type='radio' name='subject' form='edit_article' value='Politics' $politics>Politics
            <input type='radio' name='subject' form='edit_article' value='Other' $other>Other <br> \n
             <textarea rows='50' cols='100' name='content' form='edit_article'>%s</textarea> \n
             <input type='hidden' name='title_id' form='edit_article' value='%s'>
             \n",
        htmlspecialchars($title),
        htmlspecialchars($link),
        htmlspecialchars($content),
        htmlspecialchars($title_id)
    );
    $stmt->close();
    ?>
    <!--SUBMIT THE EDIT_ARTICLE DATA TO EDIT_ARTICLE.PHP-->
    <input type="submit" name="submit" form="edit_article">
</body>
</html>