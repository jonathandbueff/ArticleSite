<?php
//START SESSION
session_start();
//GET USERNAME
$username = (string) $_SESSION['username'];
//ACCESS DATABASE
require 'database.php';
//GET DATA SUBMITTED FROM EDIT.PHP
$title_id=(int) $_POST['title_id'];
$title = (string) $_POST['title'];
$subject = (string) $_POST['subject'];
$posted = (string) date("Y-m-d H:i:s");
$content = (string) $_POST['content'];
$link = (string) $_POST['link'];

//check token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
//UPDATE INFORMATION FOR THE ARTICLE THAT CORRESPONDS TO THE TITLE ID
$stmt = $mysqli->prepare("update articles set title=?, subject=?, content=?, link=? where title_id=$title_id");
//Bind Parameter
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ssss', $title, $subject, $content, $link);
$stmt->execute();
$stmt->close();
//IF THE DELETE BUTTON WAS CHECKED THEN DELETE COMMENTS CORRESPONDING TO THE ARTICLE
if ($_POST['delete']=='yes'){
    $stmt = $mysqli->prepare("delete from comments where article_id=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $title_id);
    $stmt->execute();
    $stmt->close();
//DELETE THE ARTICLE AFTER THE COMMENTS HAVE BEEN DELETED
    $stmt = $mysqli->prepare("delete from articles where title_id=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $title_id);
    $stmt->execute();
    $stmt->close();
}
//REDIRECT TO THE USER HOMEPAGE
header("Location: userhome.php");
exit;
