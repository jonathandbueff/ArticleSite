<!DOCTYPE html>
<html lang="en">
<!-- START SESSION-->
<?php session_start();?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="home.css" />
    <title>Computer Science</title>
</head>
<body>
    <!-- CREATE HEADER AND HORIZONTAL NAVIGATION BAR  -->
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
        $username = $_SESSION['username'];
        //IF THE USER IS LOGGED IN DISPLAY A LINK TO THEIR HOMEPAGE AND A LOGOUT BUTTON
        if (isset($_SESSION['username'])) {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/logout.php'>Logout</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/userhome.php'>Create/Edit Articles</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/profilepage.php'>Your Account</a>");}

        //IF A USER IS NOT LOGGED IN DISPLAY A BUTTON TO LOGIN
        else {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login</a>");
        } ?>
    </div>
    <h3>
        Other Articles:
    </h3>
    <!-- CREATE A FORM THAT ALLOWS THE USER TO VIEW ONLY THE ARTICLE AND ITS COMMENTS-->
    <form id="article" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/article.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <!--   FORM THAT SENDS THE USER TO EDIT.PHP TO  EDIT THEIR ARTICLE   -->
    <form id="edit" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/edit.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <?php
    //ACCESS THE DATABASE
    require 'database.php';
    //SELECT THE ARTICLES DATA TO DISPLAY
    $stmt = $mysqli->prepare("select title_id, title, subject, content, posted, author_username, link, rating from articles where subject = 'Other' order by posted desc limit 10");
    $summary_content="";
    //Bind Parameters
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    //GET THE ARTICLE DATA, STORE IT IN VARIABLES AND DISPLAY IT
    $stmt->bind_result($title_id, $title, $subject, $content, $posted, $author_username, $link, $rating);
    while ($stmt->fetch()) {
        $edit="";
        $title = htmlentities($title);
        $summary_content = substr("$content",0,300)."...";
        //IF USER IS AUTHOR OF ARTICLE IN CREATE LINK TO EDIT/DELETE ARTICLES
        if ($author_username == $_SESSION['username']) {
            $edit="<button class='edit' name='title_id' value='$title_id' type='submit' form= 'edit'> Edit/Delete</button>";
        }
        //DISPLAY 10 MOST RECENTLY POSTED ARTICLES
        if (isset($link)){
            printf(
                "\t <button class='title' name='title_id' value='$title_id' type='submit' form='article' > %s </button> 
                <p class = 'rating'>Rating: %s </p>
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
        else{
            printf(
                "\t <button class='title' name='title_id' value='$title_id' type='submit' form='article' > %s </button> 
                <p class = 'rating'>Rating: %s </p>
                %s 
                <p> %s</p> \n 
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