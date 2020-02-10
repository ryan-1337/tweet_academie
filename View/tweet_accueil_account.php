<?php

class accueil
{
	public function information()
	{
		
		echo "<h4>Mon compte</h4>
		<img src='img/anonyme.png' class='img' alt='Responsive image'>
		<table id='mon_compte'>
		<tr><th>Display name : </th><td> " . $_SESSION['display_name'] ."</td></tr>
		<tr><th>Username : </th><td id='username_click' style='text-decoration: underline;'> " .$_SESSION['username'] ."</td></tr>
		<tr><th>City : </th><td> " .$_SESSION['city'] ."</td></tr>
		</table>";


	}
}
?>