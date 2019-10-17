<!DOCTYPE html>
<html lang="en">
<!-- START SESSION -->
<?php session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="home.css" />
    <title>Article</title>
</head>
<!-- CREATE HEADER AND NAVIGATION BAR-->
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
        $username = (string) $_SESSION['username'];
        //IF THE USER IS LOGGED IN DISPLAY A LINK TO THE HOME PAGE AND A LOGOUT BUTTON
        if (isset($_SESSION['username'])) {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/logout.php'>Logout</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/userhome.php'>Create/Edit Articles</a>
            <a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/profilepage.php'>Your Account</a>");}

        //IF THE USER IS NOT LOGGED IN DISPLAY A LOGIN BUTTON 
        else {
            printf("<a class='login' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login</a>");
        } ?>
    </div>
    <!--   FORM THAT SENDS THE USER TO EDIT.PHP TO  EDIT THEIR ARTICLE   -->
    <form id="edit" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/edit.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <form id="edit2" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/edit2.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <form id="rating" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/rating.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>

    <?php
    //ACCESS THE DATABASE
    require 'database.php';
    //SET CLICKED EQUAL TO THE ARTICLE ID THAT WAS CLICKED
    $clicked = (int) $_POST['title_id'];
    //SELECT ARTICLE INFORMATION FROM THE CLICKED ARTICLE
    $stmt = $mysqli->prepare("select title_id, title, subject, content, posted, author_username, link, rating from articles where title_id = '$clicked'");
    //Bind Parameter
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    //BIND THE DATA TO VARIABLES
    $stmt->bind_result($title_id, $title, $subject, $content, $posted, $author_username, $link, $rating);
    //DISPLAY ARTICLE
    
    while ($stmt->fetch()) {
        $edit="";
        $title = htmlentities($title);
        //IF USER IS AUTHOR OF ARTICLE IN CREATE LINK TO EDIT/DELETE ARTICLES
        if ($author_username == $_SESSION['username']) {
            $edit="<button class='edit' name='title_id' value='$title_id' type='submit' form= 'edit'> Edit/Delete</button>";
        }

        //buttons for rating
        printf(
            "<button name='rating' value='upvote' type='submit' form= 'rating'> Upvote</button>
            <button name='rating' value='downvote' type='submit' form= 'rating'> Downvote</button>
            <input type='hidden' name='title_id' form='rating' value='%s'>
            \n",
            htmlspecialchars($title_id)
        );


        //DISPLAY ARTICLE
        //if link is given
        if (isset($link)){
            printf(
                "<button class='title' name='title_id' value='$title_id' type='submit' form='article' > %s </button> 
                <p class='rating'>Rating: %s </p>
                %s 
                <p> %s</p> \n 
                <a href='$link'> %s </a> \n 
                <p> %s</p>\n",
                htmlspecialchars($title),
                htmlspecialchars($rating),
                $edit,
                htmlspecialchars($content),
                htmlspecialchars($link),
                htmlspecialchars($posted)
        );}

        // if no link is given
        else{
        printf(
            "\t <h2> %s </h2> %s <br>  <p>\n</p>\n <p> %s</p> \n <p> %s</p>\n",
            htmlspecialchars($title),
            htmlspecialchars($rating),
            $edit,
            htmlspecialchars($content),
            htmlspecialchars($posted));
            }
    }
    $stmt->close();
    ?>

    <!-- COMMENTS-->
    <?php
    //ACCESS THE DATABASE
    require 'database.php';
    //GET COMMENT DATA ASSOCIATED WITH THE ARTICLE ID
    $title_id = (int) $_POST['title_id'];
    $stmt = $mysqli->prepare("select username, article_id, comment, posted, post_id from comments where article_id = '$title_id' order by posted desc ");
    //Bind Parameters
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    //BIND DATA TO VARIABLES
    $stmt->bind_result($username, $article_id, $comment, $posted, $post_id);
    //DISPLAY COMMENTS
    echo "<ul>\n";
    while ($stmt->fetch()) {
        //edit or delete comment
        if($username == $_SESSION['username']){
            printf(
                "\t <h3>  %s 
                <button name='post_id' value='%s' form='edit2' type='submit'> Edit/Delete</button>
                </h3> <p> %s </p>\n <p> %s</p>
                <input name='title_id' value='%s' form='edit2' type='hidden'>",
                htmlspecialchars($username),
                $post_id,
                htmlspecialchars($comment),
                htmlspecialchars($posted),
                htmlspecialchars($title_id)
            );
        }

        else{
        printf(
            "\t <h3>  %s </h3> <p> %s </p>\n <p> %s</p>",
            htmlspecialchars($username),
            htmlspecialchars($comment),
            htmlspecialchars($posted)
        );}
    }
    echo "</ul>\n";
    $stmt->close();
    ?>

    <!--Create comment-->
    <div>
        <?php
        $username = (string) $_SESSION['username'];
        $token=$_SESSION['token'];
        
        //IF THE USER IS LOGGED IN ALLOW THEM TO CREATE A COMMENT
        if (isset($_SESSION['username'])) {
            printf("<h3>
                Create a Comment.
                <form id='create_comment' action='create_comment.php' method='post' >
                     <input type='hidden' name='title_id' value='$title_id'> <br>   
                     <input type='hidden' name='token' value='$token'/>
                </form>
                 Content:<br>
                 <textarea rows='8' cols='80' name='comment' form='create_comment'>Enter text...</textarea> <br>
                 <input type='submit' name='submit' form='create_comment'>
             </h3>"
            );
        } 
        //IF THE USER IS NOT LOGGED IN GIVE THEM A LINK TO LOGIN
        else {
            printf("<a class='logincom' href='http://ec2-18-234-109-238.compute-1.amazonaws.com/login.php'>Login to comment</a>");
        } ?>
    </div>
</body>
</html>