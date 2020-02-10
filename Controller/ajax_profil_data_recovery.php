<?php

include 'controleur_tweet_academie.php';
session_start();
$username = $_SESSION['follower_find_username'];
$control = new control();
if ($username == $_SESSION['username']) {
	$control->data_my_recovery($username);
}else
{
	$control->data_recovery($username);
}
?>