<?php

include 'controleur_tweet_academie.php';
session_start();
$username = $_POST['username'];
$control = new control();
$control->data_recovery($username);

?>