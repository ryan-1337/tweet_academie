<?php

session_start();
$username = substr($_POST['follower_find_username'], 1);
$_SESSION['follower_find_username'] = $username;

?>