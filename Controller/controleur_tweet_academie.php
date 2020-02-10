<?php

include '../Model/modele_tweet_academie.php';
include '../View/tweet_accueil_account.php';

class control
{
	public function control_connection()
	{
		if( !empty($_POST['email']) && !empty($_POST['password']) ){
	    	$pdo = new database();
	    	$data = $pdo->connect();
			$count = count($data);
			if ($count >= 1) {
				$naissance = strtotime($data[0]->birth_date);
				$age = intval((time() - $naissance)/ 3600 / 24 / 365.242);
				session_start();
				$_SESSION['id'] = $data[0]->id;
				$_SESSION['username'] = $data[0]->username;
				$_SESSION['display_name'] = $data[0]->display_name;
				$_SESSION['email'] = $data[0]->email;
				$_SESSION['password'] = $data[0]->password;
				$_SESSION['birth_date'] = $data[0]->birth_date;
				$_SESSION['city'] = $data[0]->city;
				echo "OK";
				exit();
			}
			else {
				echo "BAD";
				exit();
			}
		}
		else {
			echo "EMPTY";
			exit();
		}
	}

	public function control_registration() {
		$mail_format = "#^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$#";
		$pdo = new database();
		$data = $pdo->connect();
		$count = count($data);
		foreach ($_POST as $value) {
			if (empty($value)) {
				echo "EMPTY" . $value;
				return;
			}
		}
		$data = $pdo->username_control();
		$count = count($data);
		if ($count >= 1) {
			echo"USERNAME";
			return;
		}
		elseif(!preg_match($mail_format, $_POST['email'])) {
			echo"MAIL";
			return;
		}elseif ($count > 0) {
 			echo "DOUBLON";
	        return;
 		}
	    $data = $pdo->registration();
	}

public function information_set()
	{
		session_start();
		if(!empty($_POST['set_display']))
		{
			$pdo = new database();
			$pdo->set_username();
			$_SESSION['display_name'] = $_POST['set_display'];
			$info = new accueil();
			$info->information_account();
		}

		elseif (!empty($_POST['set_email'])) {
			$pdo = new database();
			$pdo->set_email();
			$_SESSION['email'] = $_POST['set_email'];
			$info = new accueil();
			$info->information_account();
		}
		elseif (!empty($_POST['set_password'])) {
			$pdo = new database();
			$pdo->set_password();
			$_SESSION['password'] = $_POST['set_password'];
			$info = new accueil();
			$info->information_account();

		}
		elseif (!empty($_POST['set_city'])) {
			echo "string";
			$pdo = new database();
			$pdo->set_city();
			$_SESSION['city'] = $_POST['set_city'];
			$info = new accueil();
			$info->information_account();
		}
	}

	 public function control_send_tweet()
    {
        if (!empty($_POST['msg'])) 
        {
            $pdo = new database;
            session_start();
			$_SESSION['id'];
			$final_arr = array();
            $htag = "#";
            $arr = explode(" ", $_POST['msg']);
            foreach ($arr as $value) {
                if (substr($value, 0, 1) === $htag) {
                	$array = $pdo->post_hashtag_id($value);
                	$count = count($array);
                	if ($count == 0) {
                	 	$pdo->hashtag($value);
                	} 
					$hashtag = $pdo->post_hashtag_id($value);
					$id_hashtag = $hashtag[0]->id;
                    $value = "<a href='#'>".$value."</a>";
                }
                array_push($final_arr, $value);
            }
			$_POST['msg'] = implode(" ", $final_arr);
            $pdo->send_tweet();
			$hashtag_post = $pdo->post_hashtag_post();
			$id_post = $hashtag_post[0]->id;
			$pdo->insert_post_hashtag_id($id_hashtag, $id_post);
			echo "TWEET";
			return;
        }
        else 
        {
            echo "VIDE";
            return;
        }
    }

     public function control_send_retweet()
    {
    		session_start();
            $pdo = new database;
            $pdo->send_retweet();
            var_dump($_POST['msg']);
    }

    public function control_read_tweet()
    {
        $pdo = new database;
        $donnees = $pdo->read_tweet();
        foreach ($donnees as $value) 
        {
        	if ($value->media_url == "R") {
	            echo "<div><h4 class='pseudo'>@" . $value->username . "</h4><div id='t_content'><p>" .  $value->content . "</p></div><h6 class='t_date'>" . $value->submit_time . "</h6></div><br>";
	        }
	        else {
            	echo "<div><h4 class='pseudo'>@" . $value->username . "</h4><div id='t_content'><p>" .  $value->content . "</p></div><h6 class='t_date'>" . $value->submit_time . "</h6><button class='retweet'>Retruite</button></div><br>";
            }
        }
	}

    public function control_read_hashtag()
    {
        $pdo = new database;
        $donnees = $pdo->read_hashtag();
        foreach ($donnees as $value) 
        {
            echo "<aside class='pseudo'>@" . $value->username . "</aside><div id='t_content'><p>" .  $value->content . "</p></div><aside class='t_date'>" . $value->submit_time . "</aside><br><br>";
        }
	}

    public function home_search()
	{
		session_start();
		if (!empty($_POST['search_follower'])) {
			if (substr($_POST['search_follower'], 0,1) == "@") {
				$_POST['search_follower'] = substr($_POST['search_follower'], 1);
				$pdo = new database();
				$result = $pdo->search_follower();
				echo "<div id='tweet_user_result'>";
				foreach ($result as $value) {
					$check_follow = $pdo->check_follow($value->id);
					$follow_ok = count($check_follow);
					if ($value->id == $_SESSION['id']){
						continue;
					}
					elseif ($follow_ok == 0) {
						$pdo->profil_not_follow($value->display_name, $value->username, $value->city,$value->id);
					}
					else {
						$pdo->profil_follow($value->display_name,$value->username, $value->city,$value->id);
					}
					
				}
				echo "</div>";
			}
			elseif (substr($_POST['search_follower'], 0,1) == "#") {
				$pdo = new database();
				$hashtag = $pdo->search_hashtag();
				echo "<div id='tweet_user_result'>";
				foreach ($hashtag as $value) {
					echo "<div class='profil_result'><a class='hashtag_clique' href='#'>".$value->name."</a></div><br>";
				}
				echo "</div>";
			}
			else
			{
				echo "Commencez votre recherche par @ ou #";
			}
		}
	}

	public function user_post()
	{
		$pdo = new database();
		$donnees = $pdo->show_tweet();
		echo $_POST['profil'];
		echo "<br><br><h3>Activité récente:<br><br>";
        foreach ($donnees as $value) 
        {
            echo "<aside id='pseudo'>@" . $value->username . "</aside><div id='tweet_content'><p>" .  $value->content . "</p></div><aside id='tweet_date'>" . $value->submit_time . "</aside><br><br>";
        }
	}

	public function my_post()
	{
		session_start();
		$pdo = new database();
		$donnees = $pdo->show_my_tweet();
		echo "<h3>Mes Truites :<br><br>";
        foreach ($donnees as $value) 
        {
            echo "<div><aside id='pseudo'>@" . $value->username . "</aside><div id='tweet_content'><p>" .  $value->content . "</p></div><aside id='tweet_date'>" . $value->submit_time . "</aside><button class='my_tweet_delete'>Delete</button></div><br><br>";
        }
	}

	public function delete_tweet()
	{
		session_start();
		$pdo = new database();
		$pdo->delete_my_tweet();
	}

	public function follow()
	{
		session_start();
		$pdo = new database();
		$pdo->new_follow();
	}

	public function unfollow()
	{
		session_start();
		$pdo = new database();
		$pdo->delete_follow();
	}
	public function data_recovery($username)
	{
		if ($username == $_SESSION['username']) {
			$control = new control();
			$control->data_my_recovery($username);
		}else
		{
			$pdo = new database();
			$account_data = $pdo->recovery_data_account($username);
			$profil_data = $account_data[0];
			$pdo->show_data_account($profil_data);
			$post = $pdo->recovery_tweet($profil_data->username);
			echo "<section class='col-xs-12 col-sm-12 col-md-12 col-lg-9 row' id='all_info'>";
			$pdo->show_tweet($post);
			$follower = $pdo->recovery_data_follower($profil_data->id);
			$pdo->show_follower($follower);
			$following = $pdo->recovery_data_following($profil_data->id);
			$pdo->show_following($following);
			echo "</section>";
		}
	}
	public function data_my_recovery($username)
	{
		$pdo = new database();
		$account_data = $pdo->recovery_data_account($username);
		$profil_data = $account_data[0];
		$pdo->information_account($profil_data);
		$post = $pdo->recovery_tweet($profil_data->username);
		echo "<section class='col-xs-12 col-sm-12 col-md-12 col-lg-9 row' id='all_info'>";
		$pdo->show_my_post($post);
		$follower = $pdo->recovery_data_follower($profil_data->id);
		$pdo->show_follower($follower);
		$following = $pdo->recovery_data_following($profil_data->id);
		$pdo->show_following($following);
		echo "</section>";
	}

}
?>
