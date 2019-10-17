<!DOCTYPE html>
<html lang="en">
<!-- Create an article-->
<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="home.css" />
    <title>User Home</title>
</head>

<body>
    <!-- CREATE HEADER AND NAVIGATION BAR -->
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
        $username = $_SESSION['username'];
        //IF THE USER IS LOGGED IN DISPLAY A LINK TO THEIR HOMEPAGE AND A LOGOUT BUTTON
        if (isset($_SESSION['username'])) {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/logout.php'>Logout</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/userhome.php'>Create/Edit Articles</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/profilepage.php'>Your Account</a>");}
        //IF THE USER IS NOT LOGGED IN DISPLAY A LOGIN BUTTON
        else {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login</a>");
        } ?>
    </div>
        <!--   FORM THAT SENDS THE USER TO ARTICLE.PHP TO DISPLAY ONLY THE SELECTED ARTICLE AND COMMENTS    -->
        <form id="article" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/article.php' method='post'>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
        <!--   FORM THAT SENDS THE USER TO EDIT.PHP TO  EDIT THEIR ARTICLE   -->
        <form id="edit" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/edit.php' method='post'>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
        <!--  CREATE A FORM TO CREATE ARTICLES-->
        <h3>Create an Article:</h3>
        <form id="create_article" action="create_article.php" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
            <h3>Title:</h3>
            <input type="text" name="title"> 
            <h3>Subject:</h3>
            <input type="radio" name="subject" value="Computer Science">Computer Science
            <input type="radio" name="subject" value="Politics">Politics
            <input type="radio" name="subject" value="Other">Other 
            <h3>Link:</h3>
            <input type='text' name='link'>
        </form>
        <h3>Content:</h3>
        <textarea rows="8" cols="80" name="content" form="create_article">Enter text...</textarea><br>
        <input type="submit" name="submit" form="create_article">
    
    <h3>
        Your Articles
    </h3>
    <?php
    $username = $_SESSION['username'];

    require 'database.php';

    $stmt = $mysqli->prepare("select title_id, title, subject, content, posted, link from articles where author_username=? order by posted desc");
    //Bind Parameter
    $stmt->bind_param('s', $username);
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();

    $stmt->bind_result($title_id, $title, $subject, $content, $posted, $link);
    //initialize edit variable
    
    $summary_content="";
    while ($stmt->fetch()) {
        $edit="";
        //IF USER IS AUTHOR OF ARTICLE IN CREATE LINK TO EDIT/DELETE ARTICLES
        $summary_content = substr("$content",0,300)."...";
        if ($username == $_SESSION['username']) {
            $edit="<button name='title_id' value='$title_id' type='submit' form= 'edit'> Edit/Delete</button>";
        }
        //if link is given
        if (isset($link)) {
            printf(
                "\t<button name='title_id' value='$title_id' type='submit' form='article' > %s </button> %s <p>\n </p><a href='$link'> %s </a> \n <p> %s</p> \n <p> %s</p>\n",
                htmlspecialchars($title),
                $edit,
                htmlspecialchars($link),
                htmlspecialchars($summary_content),
                htmlspecialchars($posted)
            );

        //if no link is given
        } else {
            printf(
                "\t<button name='title_id' value='$title_id' type='submit' form='article' > %s </button> %s <p>\n</p>\n <p> %s</p> \n <p> %s</p>\n",
                htmlspecialchars($title),
                $edit,
                htmlspecialchars($summary_content),
                htmlspecialchars($posted)
            );
        }
    }
    $stmt->close();
    ?>

</body>

</html>