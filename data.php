<?php
	
session_start();

if($_SESSION['SEDit_con_user'] == 'SEDit_con_user_true'){	


	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'SEDit';
	if(isset($_SESSION['SEDit_con_user_email'])){
		$userlogged = $_SESSION['SEDit_con_user_email'];
	}
	

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die('Connection failed: ' . $conn->connect_error);
	}

	$SEDit_users = "SELECT username, fname, lname, dob, email FROM users WHERE email='$userlogged'";
	$SEDit_users_results = $conn->query($SEDit_users);


	if ($SEDit_users_results->num_rows > 0) {
		    // output data of each row
		while($SEDit_users_row = $SEDit_users_results->fetch_assoc()) {
			$SEDit_users_username = $SEDit_users_row['username'];
		    $SEDit_users_email = $SEDit_users_row['email'];
		    $SEDit_users_fname = $SEDit_users_row['fname'];
		    $SEDit_users_lname = $SEDit_users_row['lname'];
		    $SEDit_users_dob = $SEDit_users_row['dob'];
		    //echo $SEDit_users_username;
		}
	}


	if($_POST){


				$SEDit_comment_liked = $_POST['likesin'];

				$SEDit_users_posts = "SELECT email, likes FROM user_post_data WHERE ID='$SEDit_comment_liked'";
				$SEDit_users_posts_results = $conn->query($SEDit_users_posts);

				

				$SEDit_users_liked = "SELECT postLiked FROM user_liked WHERE email='$SEDit_users_email'";
				$SEDit_users_posts_liked = $conn->query($SEDit_users_liked);


				if ($SEDit_users_posts_results->num_rows > 0) {
							    // output data of each row
					while($SEDit_users_posts_row = $SEDit_users_posts_results->fetch_assoc()) {

						$SEDit_users_posts_likes = $SEDit_users_posts_row['likes'];
						$SEDit_users_posts_email = $SEDit_users_posts_row['email'];

						//echo $SEDit_users_posts_ID;
					}
				}

				if ($SEDit_users_posts_liked->num_rows > 0) {
							    // output data of each row
					while($SEDit_users_posts_liked_row = $SEDit_users_posts_liked->fetch_assoc()) {

						$SEDit_post_liked = $SEDit_users_posts_liked_row['postLiked'];

						//echo $SEDit_users_posts_ID;

						if($_POST['likesin'] == $SEDit_post_liked){
							$post_liked_before = true;
						}
					}
				}

				$SEDit_likes_gathered = "SELECT likes FROM gathered WHERE email='$SEDit_users_posts_email'";
				$SEDit_likes_gathered_results = $conn->query($SEDit_likes_gathered);

				if ($SEDit_likes_gathered_results->num_rows > 0) {
							    // output data of each row
					while($SEDit_likes_gathered_results_row = $SEDit_likes_gathered_results->fetch_assoc()) {

						$SEDit_likes_gathered_likes = $SEDit_likes_gathered_results_row['likes'];

						//echo $SEDit_users_posts_ID;

					}
				}

				$SEDit_updated_likes = $SEDit_users_posts_likes + 1;
				$SEDit_updated_gathered_likes = $SEDit_likes_gathered_likes + 1;
				

				if($post_liked_before === true){

					header('Location: index.php');

				}else{

			
					$SEDit_users_likes = "UPDATE user_post_data SET likes = '$SEDit_updated_likes' WHERE ID = '$SEDit_comment_liked'";

					$SEDit_likes_gathered_quer = "UPDATE gathered SET likes = '$SEDit_updated_gathered_likes' WHERE email = '$SEDit_users_posts_email'";

					$SEDit_user_comment_liked = "INSERT INTO user_liked (email, postLiked) VALUES ('$SEDit_users_email', '$SEDit_comment_liked')";

					if ($conn->query($SEDit_users_likes) === TRUE & $conn->query($SEDit_user_comment_liked) === TRUE & $conn->query($SEDit_likes_gathered_quer) === TRUE) {
					 	//echo "New record created successfully";
						header('Location: index.php');
					} else {
					 	//echo "Error: " . $SEDit_users_insert . "<br>" . $conn->error;
						
					//header('Location: index.php');
					}
				}


	}

	
	

}else{

	header('Location: login.php');
}

?>
