<!DOCTYPE html>
<html lang="en">
<!-- START SESSION-->
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='stylesheet' type='text/css' href='home.css'/>
    <title>News Site Home</title>
</head>

<body>
    <!--    Creating header and logo     -->
    <h1 class="header">
        <a href="http://ec2-18-234-109-238.compute-1.amazonaws.com/home.php">
            <img src="fakenews.jpg" alt="Fake News Logo">
            <!--from http://www.pngmart.com/image/tag/fake -->
        </a>
        Welcome to Fake News</h1>
    <!--       Horizontal navigation bar         -->
    <div class="ulnav">
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/home.php">Home</a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/computer_science.php">Computer Science </a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/politics.php">Politics</a>
        <a class="linav" href="http://ec2-18-234-109-238.compute-1.amazonaws.com/other.php">Other</a>
        <!--   IF A USER IS LOGGED IN CREATE A LINK TO THE USERS HOMEPAGE AND A LOGOUT BUTTON    -->
        <?php
        $username = (string) $_SESSION['username'];
        if (isset($_SESSION['username'])) {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/logout.php'>Logout</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/userhome.php'>Create/Edit Articles</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/profilepage.php'>Your Account</a>");
            // IF THE USER IS NOT LOGGED IN DISPLAY A LOGIN BUTTON
        } else {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login</a>");
        } ?>
    </div>
    <h3 class='page_title'>
        Top Ranked Articles:
    </h3>
    <!--   FORM THAT SENDS THE USER TO ARTICLE.PHP TO DISPLAY ONLY THE SELECTED ARTICLE AND COMMENTS    -->
    <form id="article" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/article.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" /></form>
    <!--   FORM THAT SENDS THE USER TO EDIT.PHP TO  EDIT THEIR ARTICLE   -->
    <form id="edit" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/edit.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" /></form>
    <?php
    // ACCESS THE DATABASE
    require 'database.php';
    //GET THE 10 MOST RECENTLY POSTED ARTICLES
    $stmt = $mysqli->prepare("select rating, title_id, title, subject, content, posted, author_username, link from articles order by rating desc limit 10");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($rating, $title_id, $title, $subject, $content, $posted, $author_username, $link);
    $summary_content="";
    
    //GET DATA FROM THE ARTICLES AND DISPLAY IT
    while ($stmt->fetch()) {
        $edit="";
        $title = htmlentities($title);
        $summary_content = (string) substr("$content",0,300)."...";
        //IF USER IS AUTHOR OF ARTICLE IN CREATE LINK TO EDIT/DELETE ARTICLES
        if ($author_username == $_SESSION['username']) {
            $edit="<button class='edit' name='title_id' value='$title_id' type='submit' form= 'edit'> Edit/Delete</button>";
        }
        //DISPLAY 10 MOST RECENTLY POSTED ARTICLES
        //if link is given
        if (isset($link)){
            printf(
                "<button class='title' name='title_id' value='$title_id' type='submit' form='article' > %s </button> 
                <p class='rating'>Rating: %s </p>
                %s 
                <br> <a href='$link'> %s </a> \n 
                <p> %s</p> \n 
                <p> %s</p>\n",
                htmlspecialchars($title),
                htmlspecialchars($rating),
                $edit,
                htmlspecialchars($link),
                htmlspecialchars($summary_content),
                htmlspecialchars($posted)
        );}
        //if link is not given
        else{
            printf(
                "<button class='title' name='title_id' value='$title_id' type='submit' form='article' > %s </button> 
                <p class='rating'>Rating: %s </p>
                %s 
                <br> <a href='$link'> %s </a> \n 
                <p> %s</p>\n",
                htmlspecialchars($title),
                htmlspecialchars($rating),
                $edit,
                htmlspecialchars($summary_content),
                htmlspecialchars($posted));
                }
    }
    $stmt->close();
    ?>
</body>

</html>
