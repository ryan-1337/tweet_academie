<?php

class database
{
	private $connect_db;

	public function __construct()
	{
		$this->connect_db = new PDO('mysql:host=localhost;dbname=common-database','root','root');
	}

	public function registration()
	{
		$db = $this->connect_db->prepare('
		INSERT INTO 
		user (username, display_name, email, password, birth_date, city) 
		VALUES 
		(:username, :display_name, :email, :password, :birth_date, :city)');
		$password = hash('ripemd160', $_POST['password'] . 'vive le projet tweet_academy');
		$db->bindValue(':username', $_POST['username']);
		$db->bindValue(':display_name', $_POST['display_name']);
		$db->bindValue(':email', $_POST['email']);
		$db->bindValue(':password', $password);
		$db->bindValue(':birth_date', $_POST['birth_date']);
		$db->bindValue(':city', $_POST['city']);
		$db->execute();
	}

	public function connect()
	{
		$db = $this->connect_db->prepare('
		SELECT * 
		FROM user 
		WHERE email = :email AND password = :password');
		$password = hash('ripemd160', $_POST['password'] . 'vive le projet tweet_academy');
		$db->bindValue(':email', $_POST['email']);
		$db->bindValue(':password', $password);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function username_control()
	{
		$db = $this->connect_db->prepare('
		SELECT * 
		FROM user 
		WHERE username = :username');
		$db->bindValue(':username', $_POST['username']);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
	}

	public function send_to_db()
	{
		$db = $this->connect_db->prepare('
		INSERT INTO 
		chat_message (conversation_id, sender_id, content, submit_time) 
		VALUES 
		(:conversation_id, :sender_id, :content, :submit_time)');
		$submit_time = date('Y-m-d H:i:s');
		$db->bindValue(':conversation_id', $_POST['conv_id']);
		$db->bindValue(':sender_id', $_SESSION['id']);
		$db->bindValue(':content', $_POST['content']);
		$db->bindValue(':submit_time', $submit_time);
		$db->execute();
	}

	public function send_to_user()
	{
		$db = $this->connect_db->prepare('
		SELECT * 
		FROM chat_message 
		INNER JOIN user
		ON chat_message.sender_id = user.id 
		WHERE conversation_id = :conversation_id
		ORDER BY chat_message.submit_time
		');
		$db->bindValue(':conversation_id', $_POST['conv_id']);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
	}

	public function set_username()
	{
		$db = $this->connect_db->prepare('
			UPDATE user SET display_name = :display WHERE email = :email');
		$db->bindValue(':display', $_POST['set_display']);
		$db->bindValue(':email', $_SESSION['email']);
		$db->execute();
	} 
	public function set_email()
	{
		$db = $this->connect_db->prepare('
			UPDATE user SET email = :set_email WHERE email = :email');
		$db->bindValue(':set_email', $_POST['set_email']);
		$db->bindValue(':email', $_SESSION['email']);
		$db->execute();
	} 
	public function set_password()
	{
		$db = $this->connect_db->prepare('
			UPDATE user SET password = :password WHERE email = :email');
		$password = hash('ripemd160', $_POST['set_password'] . 'vive le projet tweet_academy');
		$db->bindValue(':password', $password);
		$db->bindValue(':email', $_SESSION['email']);
		$db->execute();
	}

	public function set_city()
	{
		$db = $this->connect_db->prepare('
			UPDATE user SET city = :city WHERE email = :email');
		$db->bindValue(':city', $_POST['set_city']);
		$db->bindValue(':email', $_SESSION['email']);
		$db->execute();
	}
	public function send_tweet()
    {
        $db = $this->connect_db->prepare('
        INSERT INTO
        post (sender_id, content, submit_time)
        VALUES
        (:sender_id, :content, :submit_time)');
        $db->bindValue(':sender_id', $_SESSION['id']);
        $db->bindValue(':content', $_POST['msg']);
        $db->bindValue(':submit_time', date('Y-m-d H:i:s'));
        $db->execute();
    }
    public function send_retweet()
    {
        $db = $this->connect_db->prepare('
        INSERT INTO
        post (sender_id, content, media_url, submit_time)
        VALUES
        (:sender_id, :content, :media, :submit_time)');
        $db->bindValue(':sender_id', $_SESSION['id']);
        $db->bindValue(':content', $_POST['msg']);
        $db->bindValue(':media', "R");
        $db->bindValue(':submit_time', date('Y-m-d H:i:s'));
        $db->execute();
    }
    public function read_tweet()
    {
    	session_start();
        $db = $this->connect_db->prepare('
        SELECT user.username, post.content , post.submit_time,
        post.media_url
        FROM user 
		INNER JOIN post ON user.id = post.sender_id 
		LEFT JOIN follower ON post.sender_id = follower.user_id
		WHERE follower.follower_id = :id
		OR post.sender_id = :my_id
        GROUP BY post.id
		ORDER BY submit_time DESC LIMIT 0, 50');
		$db->bindValue(':id', $_SESSION['id']);
		$db->bindValue(':my_id', $_SESSION['id']);
		$db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function control_conv_name()
    {
    	$db = $this->connect_db->prepare('
    	SELECT * 
		FROM chat_conversation
		WHERE name = :conv_name');
		$db->bindValue(':conv_name', $_POST['conv_name']);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function create_conversation()
    {
    	$db = $this->connect_db->prepare('
    	INSERT INTO 
    	chat_conversation(name) 
    	VALUES (:conv_name)');
    	$db->bindValue(':conv_name', $_POST['conv_name']);
    	$db->execute();
    	$db = $this->connect_db->prepare('
    	SELECT * 
		FROM chat_conversation
		WHERE name = :conv_name
		ORDER BY id DESC
		');
		$db->bindValue(':conv_name', $_POST['conv_name']);
		$db->execute();
		$conv = $db->fetchAll(PDO::FETCH_OBJ);
		$conv = $conv[0]->id;
		$db = $this->connect_db->prepare('
		INSERT INTO 
    	chat_participant(chat_conversation_id, user_id) 
    	VALUES (:chat_conversation_id, :user_id)');
    	$db->bindValue(':chat_conversation_id', $conv);
    	$db->bindValue(':user_id', $_SESSION['id']);
    	$db->execute();
    }

    public function search_follower()
    {
    	$db = $this->connect_db->prepare('
    	SELECT * 
		FROM follower
		RIGHT JOIN user
		ON follower.follower_id = user.id 
		WHERE user.username
		LIKE :search_follower"%"
		GROUP BY user.id
		');
		$db->bindValue(':search_follower', $_POST['search_follower']);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
	}
	
    public function search_hashtag()
    {
    	$db = $this->connect_db->prepare('
    	SELECT * 
		FROM hashtag
		WHERE name
		LIKE :search_hashtag"%"
		');
		$db->bindValue(':search_hashtag', $_POST['search_follower']);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function read_hashtag()
	{
		$db = $this->connect_db->prepare('
		SELECT 
		user.username, 
		post.content, 
		post.submit_time 
		FROM `user` 
		INNER JOIN post 
		ON user.id = post.sender_id 
		INNER JOIN post_hashtag 
		ON post.id = post_hashtag.post_id 
		INNER JOIN hashtag 
		ON post_hashtag.hashtag_id = hashtag.id 
		WHERE hashtag.name = :hashtag');
		$db->bindValue(':hashtag', $_POST['hashtag']);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
	}

    public function your_conversation()
    {
    	$db = $this->connect_db->prepare('
    	SELECT chat_conversation.id AS "conversation_id", chat_conversation.name 
		FROM chat_conversation 
		INNER JOIN chat_participant
		ON chat_conversation.id = chat_participant.chat_conversation_id 
		INNER JOIN user
		ON chat_participant.user_id = user.id
		WHERE chat_participant.user_id = :user_id');
		$db->bindValue(':user_id', $_SESSION['id']);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function your_conversation_user($conversation_id)
   	{
   		$db = $this->connect_db->prepare('
   		SELECT chat_conversation.name, user.username
		FROM chat_conversation 
		INNER JOIN chat_participant
		ON chat_conversation.id = chat_participant.chat_conversation_id 
		INNER JOIN user
		ON chat_participant.user_id = user.id
		WHERE chat_conversation.id = :conversation_id');
		$db->bindValue(':conversation_id', $conversation_id);
		$db->execute();
		return $db->fetchAll(PDO::FETCH_OBJ);
   	}

   	public function select_truitos_id()
   	{
   		$db = $this->connect_db->prepare('
   		SELECT id FROM user WHERE username = :new_truitos');
    	$db->bindValue(':new_truitos', $_POST['new_truitos']);
    	$db->execute();
    	return $db->fetchAll(PDO::FETCH_OBJ);
   	}
   	public function add_truitos_to_conv($truitos)
   	{
   		$db = $this->connect_db->prepare('
		INSERT INTO 
    	chat_participant(chat_conversation_id, user_id) 
    	VALUES (:chat_conversation_id, :user_id)');
    	$db->bindValue(':chat_conversation_id', $_POST['conv_id']);
    	$db->bindValue(':user_id', $truitos);
    	$db->execute();
   	}
   	public function delete_conv()
   	{
   		$db = $this->connect_db->prepare('
   		DELETE FROM chat_message
   		WHERE conversation_id = :id');
   		$db->bindValue(':id', $_POST['id']);
   		$db->execute();
   		$db = $this->connect_db->prepare('
   		DELETE FROM chat_participant
   		WHERE chat_conversation_id = :id');
   		$db->bindValue(':id', $_POST['id']);
   		$db->execute();
   		$db = $this->connect_db->prepare('
   		DELETE FROM chat_conversation
   		WHERE id = :id');
   		$db->bindValue(':id', $_POST['id']);
   		$db->execute();
   		var_dump($_POST['id']);
   	}
   	public function recovery_tweet($username)
    {
        $db = $this->connect_db->prepare('
        SELECT user.username, post.content , post.submit_time
        FROM user 
		INNER JOIN post ON user.id = post.sender_id 
		WHERE user.username = :username
		ORDER BY post.submit_time DESC');
		$db->bindValue(':username', $username);
   		$db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function show_my_tweet()
    {
        $db = $this->connect_db->prepare('
        SELECT user.username, post.content , post.submit_time
        FROM user 
		INNER JOIN post ON user.id = post.sender_id 
		WHERE user.username = :username
		ORDER BY post.submit_time DESC 
		LIMIT 10');
		$db->bindValue(':username', $_SESSION['username']);
   		$db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function hashtag($value)
	{
		$db = $this->connect_db->prepare('
		INSERT INTO hashtag (name, use_count)
		VALUES (:name, :use_count)
		');
		$db->bindValue(':name', $value);
		$db->bindValue(':use_count', 0);
		$db->execute();
	}

	public function post_hashtag_id($value)
	{
		$db = $this->connect_db->query('SELECT * FROM hashtag WHERE name = "' .$value. '" ORDER BY ID DESC LIMIT 1 ');
		$db = $db->fetchAll(PDO::FETCH_OBJ);
		return $db;
	}

	

	public function post_hashtag_post()
	{
		$db = $this->connect_db->query('SELECT * FROM post WHERE content = "' .$_POST['msg']. '" ORDER BY ID DESC LIMIT 1');
		$db = $db->fetchAll(PDO::FETCH_OBJ);
		return $db;
	}

	public function insert_post_hashtag_id($id_hashtag, $id_post)
	{
		$db = $this->connect_db->prepare('
		INSERT INTO post_hashtag (hashtag_id, post_id)
		VALUES (:hashtag_id, :post_id)
		');
		$db->bindValue(':hashtag_id', $id_hashtag);
		$db->bindValue(':post_id', $id_post);
		$db->execute();
	}

    public function delete_my_tweet()
    {
    	$db = $this->connect_db->prepare('
    	DELETE FROM post
		WHERE sender_id = :id 
		AND submit_time = :submit_time');
		$db->bindValue(':id', $_SESSION['id']);
    	$db->bindValue(':submit_time', $_POST['submit_time']);
    	echo $_POST['submit_time'];
    	$db->execute();
    }
    public function new_follow()
    {
    	$db = $this->connect_db->prepare('
    	INSERT INTO 
    	follower(user_id, follower_id, follow_date)
    	VALUES(:user_id, :follower_id, :follow_date)');
    	$date = date("Y-m-d H:i:s");
    	$db->bindValue(':user_id', $_POST['user_id']);
    	$db->bindValue(':follower_id', $_SESSION['id']);
    	$db->bindValue(':follow_date', $date);
    	$db->execute();
    }
    public function delete_follow()
    {
    	$db = $this->connect_db->prepare('
   		DELETE FROM follower
   		WHERE user_id = :user_id
    	AND follower_id = :follower_id');
    	$db->bindValue(':user_id', $_POST['user_id']);
    	$db->bindValue(':follower_id', $_SESSION['id']);
   		$db->execute();
    }
    public function check_follow($user_id)
    {
    	$db = $this->connect_db->prepare('
    	SELECT * FROM follower
    	WHERE user_id = :user_id
    	AND follower_id = :follower_id');
    	$db->bindValue(':user_id', $user_id);
    	$db->bindValue(':follower_id', $_SESSION['id']);
    	$db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function profil_not_follow($display, $username, $city,$id)
    {
    	echo "
		<br><div class='profil_result'>
		<table>
		<tr><td><img src='img/anonyme.png' class='image_search' alt='Responsive image'></td>
		<td><table class='profil'>
		<tr><th>Username : </th><td class='search_username' style='text-decoration: underline;'> " . $username ."</td></tr>
		<tr><th>Display name : </th><td> " . $display ."</td></tr>
		<tr><th>City : </th><td> " . $city ."</td></tr>
		</table></td></tr><tr><td><button class='follow_button' value=". $id .">
		<strong>FOLLOW</strong></button></td><tr></table></div><br>";
    }
    public function profil_follow($display, $username, $city,$id)
    {
    	echo "
		<br><div class='profil_result'>
		<table>
		<tr><td><img src='img/anonyme.png' class='image_search' alt='Responsive image'></td>
		<td><table class='profil'>
		<tr><th>Username : </th><td class='search_username' style='text-decoration: underline;'> " . $username ."</td></tr>
		<tr><th>Display name : </th><td> " . $display ."</td></tr>
		<tr><th>City : </th><td> " . $city ."</td></tr>
		</table></td></tr><tr><td><button class='unfollow_button' value=". $id ."><strong>
		UNFOLLOW</strong></button></td><tr></table></div><br>";
    }
    public function recovery_data_account($username)
    {
    	$db = $this->connect_db->prepare('
    	SELECT * FROM user
    	WHERE username = :username
    	');
    	$db->bindValue(':username', $username);
    	$db->execute();
    	return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function show_data_account($profil_data)
    {
    	echo "<section class='col-xs-12 col-sm-12 col-md-12 col-lg-3 row' id='account_info'>
    	<div class='col-xs-11 col-sm-11 col-md-12 col-lg-10' id='profil_data'>
    	<img src='img/anonyme.png' class='profil_image' alt='Responsive image'>
    	<strong><p>Display name: " . $profil_data->display_name . "</p>
    	<p id='profil_username'>Username: @" . $profil_data->username . "<p>
    	<p>Date de naissance: " . $profil_data->birth_date . "</p>
    	<p>Ville: " . $profil_data->city . "</p></strong>" ;
    	$pdo = new database();
    	$check_follow = $pdo->check_follow($profil_data->id);
    	$follow_ok = count($check_follow);
		if ($follow_ok == 0) {
			echo "<button class='follow_button' value=". $profil_data->id ."><strong>
			FOLLOW</strong></button>";
		}
		else {
			echo "<button class='unfollow_button' value=". $profil_data->id ."><strong>
			UNFOLLOW</strong></button>";
		}
		echo "</div></section>";
    }
    public function show_tweet($post)
    {
    	$nb = count($post);
    	echo "<div class='col-xs-12 col-sm-12 col-md-5 col-lg-5' id='tweet_content'>
    	<h3>" . $nb . " Truites</h3><br><br>";
    	foreach ($post as $value) {
    		echo "<div class='content'>" . $value->content . "</div>
    		<aside class='tweet_date'>" . $value->submit_time . "</aside><br><br>";
    	}
    	echo "</div>";
    }
    public function show_my_post($post)
    {
    	$nb = count($post);
    	echo "<div class='col-xs-12 col-sm-12 col-md-5 col-lg-5' id='tweet_content'>
    	<h3>" . $nb . " Truites</h3><br><br>";
    	foreach ($post as $value) {
    		echo "<div><div class='content'>" . $value->content . "</div>
    		<aside class='tweet_date'>" . $value->submit_time . "</aside>
    		<button class='my_tweet_delete'>Delete</button></div><br><br>";
    	}
    	echo "</div>";
    }
    public function recovery_data_follower($id)
    {
    	$db = $this->connect_db->prepare('
    	SELECT user.username
    	FROM user
    	INNER JOIN follower
    	ON user.id = follower.follower_id
    	WHERE follower.user_id = :id');
    	$db->bindValue(':id', $id);
    	$db->execute();
    	return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function show_follower($follower)
    {
    	$nb = count($follower);
    	echo "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3 ' id='follower_content'>
    	<h3>" . $nb . " Followers</h3><br><br>";
    	foreach ($follower as $value) {
    		echo "<div class='username_content'>@" . $value->username . "</div><br>";
    	}
    	echo "</div>";
    }
    public function recovery_data_following($id)
    {
    	$db = $this->connect_db->prepare('
    	SELECT user.username
    	FROM user
    	INNER JOIN follower
    	ON user.id = follower.user_id
    	WHERE follower.follower_id = :id');
    	$db->bindValue(':id', $id);
    	$db->execute();
    	return $db->fetchAll(PDO::FETCH_OBJ);	
    }
    public function show_following($following)
    {
    	$nb = count($following);
    	echo "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3 ' id='following_content'>
    	<h3>" . $nb . " Following</h3><br><br>";
    	foreach ($following as $value) {
    		echo "<div class='username_content'>@" . $value->username . "</div><br>";
    	}
    	echo "</div>";
    }
    public function information_account()
	{
		
		echo "
		<section class='col-xs-12 col-sm-12 col-md-12 col-lg-3 row' id='account_info'>
		<div class='col-xs-11 col-sm-11 col-md-10 col-lg-10' id='profil_data'>
		<h3>MON COMPTE</h3>
		<img src='img/anonyme.png' class='profil_image' alt='Responsive image'><br><br>
		<p><strong>username: @" . $_SESSION['username'] . "</strong></p>
		<h4>Modifier:</h4>
		<table id='mon_compte'>
		<tr><th>Display_name : " .$_SESSION['display_name'] ."</th></tr>
		<tr><td><input type='text' id='set_display'/></td></tr>
		<tr><td><button class='modifier' id='button_display'>Modifier</button></td></tr>
		<tr><th>Email : " .$_SESSION['email'] ."</th></tr>
		<tr><td><input type='text' id='set_email'/></td></tr>
		<tr><td><button class='modifier' id='button_email'>Modifier</button></td></tr>
		<tr><th>Mot de passe : ######</th></tr>
		<tr><td><input type='text' id='set_password'/></td></tr>
		<tr><td><button class='modifier' id='button_password'>Modifier</button></td></tr>
		<tr><th>City : " .$_SESSION['city'] ."</th></tr>
		<tr><td><input type='text' id='set_city'/></td></tr>
		<tr><td><button class='modifier' id='button_city'>Modifier</button></td></tr>
		</table></div></section>";
	}
}
