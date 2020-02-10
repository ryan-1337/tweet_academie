<?php

include '../View/tweet_accueil_account.php';

session_start();
$connect = new accueil;
$connect->information();

?>