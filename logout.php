<?php
//START SESSION SO IT CAN BE DESTROYED
session_start();
session_destroy();
//REDIRECT TO LOGIN PAGE
header("Location: login.php");
?>
