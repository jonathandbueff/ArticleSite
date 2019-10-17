<?php
//START SESSION AND GET USERNAME
session_start();
$username = (string)$_SESSION['username'];
//ACCESS THE DATABASE
require 'database.php';
//GET THE DATA SUBMITTED FROM THE FORM IN ARTICLE.PHP
$title_id = (int) $_POST['title_id'];
$comment = (string) $_POST['comment'];
$posted = (string) date("Y-m-d H:i:s");
//check token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
  die("Request forgery detected");
}
//CREATE A NEW COMMENT IN THE COMMENTS TABLE
$stmt = $mysqli->prepare("insert into comments (username, article_id, comment, posted) values (?,?,?,?)");
//Bind Parameter
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ssss', $username, $title_id, $comment, $posted);
$stmt->execute();
$stmt->close();
//RETURN TO ARTICLE.PHP AND DISPLAY THE ARTICLE THAT WAS COMMENTED ON
printf("
  <form id='article' action='http://ec2-18-234-109-238.compute-1.amazonaws.com/article.php' method='post'>
      <input type='hidden' name='title_id' value='$title_id'>
  </form>");
echo "<script type=\"text/javascript\"> document.getElementById('article').submit() </script>";
exit;
?>