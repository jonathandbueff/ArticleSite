<?php
//START SESSION
session_start();
//GET USERNAME
$username = (string) $_SESSION['username'];
//ACCESS DATABASE
require 'database.php';
//GET DATA SUBMITTED FROM EDIT.PHP
$post_id= (int) $_POST['post_id'];
$title_id = (int) $_POST['title_id'];
$comment= (string) $_POST['comment'];
//check token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
//UPDATE INFORMATION FOR THE COMMENT THAT CORRESPONDS TO THE POST ID
$stmt = $mysqli->prepare("update comments set comment=? where post_id='$post_id'");
//Bind Parameter
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('s', $comment);
$stmt->execute();
$stmt->close();
$stmt = $mysqli->prepare("select article_id from comments where post_id=?");
//Bind Parameter
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('s', $post_id);
$stmt->execute();
$stmt->bind_result($title_id);
$stmt->fetch();
$stmt->close();

//IF THE DELETE BUTTON WAS CHECKED THEN DELETE COMMENTS CORRESPONDING TO THE ARTICLE
if ($_POST['delete']=='yes'){
    $stmt = $mysqli->prepare("delete from comments where post_id=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $post_id);
    $stmt->execute();
    $stmt->close();
}
//redirect to article.php file addressed to that title
printf("
  <form id='article' action='http://ec2-18-234-109-238.compute-1.amazonaws.com/article.php' method='post'>
      <input type='hidden' name='title_id' value='$title_id'>
  </form>");
echo "<script type=\"text/javascript\"> document.getElementById('article').submit() </script>";
exit;
