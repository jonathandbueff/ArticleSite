<?php
//START A SESSION AND GET USERNAME
session_start();
$username = (string) $_SESSION['username'];
$title_id = (int) $_POST['title_id'];
//ACCESS DATABASE
require 'database.php';
//GET THE DATA SUBMITTED FROM USERHOME.PHP
$rating = (string) $_POST['rating'];


//determine if rating was upvote or downvote and assign 1 and -1 to the variable that we will later add
$rating_conversion = 0;
if ($rating == "upvote") {
    $rating_conversion = 1;
} else {
    $rating_conversion = -1;
}
//select rating of article with the same title_id
$stmt = $mysqli->prepare("select rating from articles where title_id=$title_id");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
//bind result to variable
$stmt->bind_result($rating_before);

//find new running rate after adding the conversion
while ($stmt->fetch()) {
    $runningRating = (int) $rating_before + $rating_conversion;
}
$stmt->close();

//UPDATE RATING FOR THE ARTICLE THAT CORRESPONDS TO THE TITLE ID
$stmt = $mysqli->prepare("update articles set rating=? where title_id=$title_id");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('s', $runningRating);
$stmt->execute();
$stmt->close();


// REDIRECT TO ARTICLE
printf("
  <form id='article' action='http://ec2-18-234-109-238.compute-1.amazonaws.com/article.php' method='post'>
      <input type='hidden' name='title_id' value='$title_id'>
  </form>");
echo "<script type=\"text/javascript\"> document.getElementById('article').submit() </script>";
exit;
