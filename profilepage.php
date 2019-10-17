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
    <title>Profile Page</title>
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
        $username = (string) $_SESSION['username'];
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
        <form id="edit" action='http://ec2-18-234-109-238.compute-1.amazonaws.com/edit.php' method='post'>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>

    <!-- Display User Information -->
    <h3>
        Your Profile Information
    </h3>
    <?php
    $username = (string) $_SESSION['username'];

    require 'database.php';
    $stmt = $mysqli->prepare("select first_name, last_name, email_address, username from users where username=?");
    //Bind Parameter
    $stmt->bind_param('s', $username);
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();

    ///bind result
    $stmt->bind_result($first_name, $last_name, $email_address, $username);


    //display user information
    while ($stmt->fetch()) {
        $edit="";
            printf("<p>First Name: %s <br>
                    Last Name: %s <br>
                    Email Address: %s <br>
                    Username: %s <br></p>",
                htmlspecialchars($first_name),
                htmlspecialchars($last_name),
                htmlspecialchars($email_address),
                htmlspecialchars($username)
            );
        }
    $stmt->close();
    ?>

    <!-- Display User's Articles -->
    <h3>
        Your Articles
    </h3>
    <?php
    $username = (string) $_SESSION['username'];

    require 'database.php';

    $stmt = $mysqli->prepare("select title_id, title, subject, content, posted, link, rating from articles where author_username=? order by posted desc");
    //Bind Parameter
    $stmt->bind_param('s', $username);
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();

    $stmt->bind_result($title_id, $title, $subject, $content, $posted, $link, $rating);
    echo "<ul>\n";
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
                "<button class='title' name='title_id' value='$title_id' type='submit' form='article' > %s </button> 
                <p class='rating'>Rating: %s </p>
                %s 
                <p> %s</p> \n 
                <a href='$link'> %s </a> \n 
                <p> %s</p>\n",
                htmlspecialchars($title),
                htmlspecialchars($rating),
                $edit,
                htmlspecialchars($summary_content),
                htmlspecialchars($link),
                htmlspecialchars($posted)
        );
        //if no link is given
        } else {
            printf(
                "<button class='title' name='title_id' value='$title_id' type='submit' form='article' > %s </button> 
                <p class='rating'>Rating: %s </p>
                %s 
                <p> %s</p>
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
