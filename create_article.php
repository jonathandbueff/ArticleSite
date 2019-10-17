<?php
//START A SESSION AND GET USERNAME
session_start();
$username = (string) $_SESSION['username'];
//ACCESS DATABASE
require 'database.php';
//GET THE DATA SUBMITTED FROM USERHOME.PHP
$title = (string) $_POST['title'];
$subject = (string) $_POST['subject'];
$posted = (string) date("Y-m-d H:i:s");
$content = (string) $_POST['content'];
$link = (string) $_POST['link'];

//check token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
//CREATE A NEW ARTICLE WITH THE DATA ABOVE
$stmt = $mysqli->prepare("insert into articles (title, author_username, subject, content, posted, link) values (?,?,?,?,?,?)");
//Bind Parameter
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ssssss', $title, $username, $subject, $content, $posted, $link);
$stmt->execute();
$stmt->close();
//REDIRECT TO USERHOME
header("Location: userhome.php");
exit;
?>
