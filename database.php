<!-- CONNECTING TO DATABASE -->
<?php 
    session_start();
    $mysqli = new mysqli('localhost', 'news', 'wustl', 'news');
    if($mysqli->connect_errno){
        printf("Connection Failed: %s\n", $mysqli->connect_error);
        exit;
    }
?>