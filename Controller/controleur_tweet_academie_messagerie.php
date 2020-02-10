<?php

include '../Model/modele_tweet_academie.php';

class messaging
{
	public function message()
	{
		session_start();
		$pdo = new database();
		if (!empty($_POST['content'])) {
			$pdo->send_to_db();
		}
		$content = $pdo->send_to_user();
		foreach ($content as $value) {
				if ($value->sender_id == $_SESSION['id']) {
					echo "<div id='message_user'><p id='text_user'>" . $value->content . "</p><aside>" . $value->submit_time . "</aside></div><br><br>";
				}
				else
				{

					echo "<div id='message_interlocutor'><aside class='pseudo'>@" . $value->username . "</aside><p id='text_interlocutor'>" . $value->content . "</p><aside>" . $value->submit_time . "</aside></div><br>";
				}
			}
			exit();
	}

	public function create_conv()
	{
		session_start();
		$pdo = new database();
		if (empty($_POST['conv_name'])) {
			$_POST['conv_name'] = "Conversation";
		}
		$exist = count($pdo->control_conv_name());
		$pdo->create_conversation();
	}

	public function your_conversation_list()
	{
		session_start();
		$pdo = new database();
		$conversation = $pdo->your_conversation();
		echo "<h2>Mes conversations:</h2>";
		foreach ($conversation as $value) {
			$conv = $pdo->your_conversation_user($value->conversation_id);
			if ($_POST['value'] == $value->conversation_id OR $_POST['value'] == "x") {
				echo "<div><input type='radio' class='input_conv' name='conversation' value= " . $value->conversation_id . " checked>
			<a><strong>" . $value->name . "</strong></a><br>";
			}else{
				echo "<div><input type='radio' class='input_conv' name='conversation' value= " . $value->conversation_id . ">
				<a><strong>" . $value->name . "</strong></a><br>";
			}
			foreach ($conv as $value1) {
				echo "<a>{@" . $value1->username . "} </a>";
			}
			echo "</div><br>";
		}
	}

	public function add_truitos()
	{
		if (!empty($_POST['new_truitos'])) 
		{
			$pdo = new database();
			$truitos = $pdo->select_truitos_id();
			$truitos = $truitos[0]->id;
			$pdo->add_truitos_to_conv($truitos);
		}
	}

	public function search_follow()
	{
		if (!empty($_POST['search_follower'])) {
			if (substr($_POST['search_follower'], 0,1) == "@") {
				$_POST['search_follower'] = substr($_POST['search_follower'], 1);
			}
			$pdo = new database();
			$result = $pdo->search_follower();
			foreach ($result as $value) {
				echo "<li class='li_result'><strong>@</strong>" . $value->username . "</li><br>";
			}
		}
	}

	public function delete_controle()
	{
		$pdo = new database();
		$pdo->delete_conv();
	}
}