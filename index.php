<?php
	
session_start();

if($_SESSION['SEDit_con_user'] == 'SEDit_con_user_true'){

	if($_POST){

		if(isset($_POST['logoutbtn'])){
			session_unset();
			session_destroy();
			header('Location: login.php');
		}
	}	


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

	$SEDit_users = "SELECT username, fname, lname, dob, sex, email, private FROM users WHERE email='$userlogged'";
	$SEDit_users_results = $conn->query($SEDit_users);


	if ($SEDit_users_results->num_rows > 0) {
		    // output data of each row
		while($SEDit_users_row = $SEDit_users_results->fetch_assoc()) {
			$SEDit_users_username = $SEDit_users_row['username'];
		    $SEDit_users_email = $SEDit_users_row['email'];
		    $SEDit_users_fname = $SEDit_users_row['fname'];
		    $SEDit_users_lname = $SEDit_users_row['lname'];
		    $SEDit_users_dob = $SEDit_users_row['dob'];
		    $SEDit_users_sex = $SEDit_users_row['sex'];
		    $SEDit_users_private = $SEDit_users_row['private'];
		    //echo $usernameGet;
		}
	}

	$SEDit_likes_gathered = "SELECT likes, posts, following, followers FROM gathered WHERE email='$SEDit_users_email'";
	$SEDit_likes_gathered_results = $conn->query($SEDit_likes_gathered);

	if ($SEDit_likes_gathered_results->num_rows > 0) {
							    // output data of each row
		while($SEDit_likes_gathered_results_row = $SEDit_likes_gathered_results->fetch_assoc()) {

			$SEDit_likes_gathered_likes = $SEDit_likes_gathered_results_row['likes'];

			$SEDit_posts_gathered_posts = $SEDit_likes_gathered_results_row['posts'];

			$SEDit_posts_gathered_following = $SEDit_likes_gathered_results_row['following'];

			$SEDit_posts_gathered_followers = $SEDit_likes_gathered_results_row['followers'];

						//echo $SEDit_users_posts_ID;

		}
	}




	if($_POST){

		if(isset($_POST['followbtn'])){
			//echo $_POST['followbtn'];

			$SEDit_f_username = $SEDit_users_username;
			$SEDit_f_user = $_POST['followbtn'];
			$SEDit_f_gathered_following = $SEDit_posts_gathered_following + 1;

			// OTHER USER
			$SEDit_get_followed = "SELECT user FROM user_following WHERE username = '$SEDit_f_username'";
			$SEDit_get_followed_results = $conn->query($SEDit_get_followed);

			if ($SEDit_get_followed_results->num_rows > 0) {
									    // output data of each row
				while($SEDit_get_followed_results_row = $SEDit_get_followed_results->fetch_assoc()) {

					$SEDit_get_followed_user = $SEDit_get_followed_results_row['user'];

					//echo $SEDit_followers_gathered_followers_2;

					if($SEDit_f_user == $SEDit_get_followed_user){

						$user_followed = true;
					}else{

						$user_followed = false;
					}
				}
				
			}

			// OTHER USER
			$SEDit_followers_gathered_2 = "SELECT followers FROM gathered WHERE username = '$SEDit_f_user'";
			$SEDit_followers_gathered_results_2 = $conn->query($SEDit_followers_gathered_2);

			if ($SEDit_followers_gathered_results_2->num_rows > 0) {
									    // output data of each row
				while($SEDit_followers_gathered_results_row_2 = $SEDit_followers_gathered_results_2->fetch_assoc()) {

					$SEDit_followers_gathered_followers_2 = $SEDit_followers_gathered_results_row_2['followers'];

					//echo $SEDit_followers_gathered_followers_2;

				}
				$SEDit_f_gathered_followers = $SEDit_followers_gathered_followers_2 + 1;
			}
			

			
			//echo $SEDit_f_gathered_followers;
			if($user_followed == true){

			}elseif($user_followed == false){

				$SEDit_following_update = "UPDATE gathered SET following = '$SEDit_f_gathered_following' WHERE email = '$SEDit_users_email'";

				$SEDit_following_update_other = "UPDATE gathered SET followers = '$SEDit_f_gathered_followers' WHERE username = '$SEDit_f_user'";

				$SEDit_following_insert = "INSERT INTO user_following (username, user) VALUES ('$SEDit_f_username', '$SEDit_f_user')";

				if ($conn->query($SEDit_following_insert) === TRUE & $conn->query($SEDit_following_update_other) === TRUE & $conn->query($SEDit_following_update) === TRUE) {
					//echo "New record created successfully";
				} else {
					//echo "Error: " . $SEDit_users_insert . "<br>" . $conn->error;
				}
			}

		}

		if(isset($_POST['postbtn'])){


			if(isset($_POST['postin'])){

				$SEDit_email = $SEDit_users_email;
				$SEDit_username = $SEDit_users_username;
				$SEDit_sex = $SEDit_users_sex;
				$SEDit_fname = $SEDit_users_fname;
				$SEDit_lname = $SEDit_users_lname;
				$SEDit_content = $_POST['postin'];
				$SEDit_likes = 0;
				$SEDit_shares = 0;
				$SEDit_replies = 0;
				$SEDit_private = $SEDit_users_private;

				$SEDit_updated_gathered_posts = $SEDit_posts_gathered_posts + 1;

				$SEDit_posts_gathered_quer = "UPDATE gathered SET posts = '$SEDit_updated_gathered_posts' WHERE email = '$SEDit_users_email'";

				$SEDit_users_insert = "INSERT INTO user_post_data (email, username, sex, fname, lname, content, likes, shares, replies, private) VALUES ('$SEDit_email', '$SEDit_username', '$SEDit_sex', '$SEDit_fname', '$SEDit_lname', '$SEDit_content', '$SEDit_likes', '$SEDit_shares', '$SEDit_replies', '$SEDit_private')";

				if ($conn->query($SEDit_users_insert) === TRUE & $conn->query($SEDit_posts_gathered_quer) === TRUE) {
					//echo "New record created successfully";
				} else {
					//echo "Error: " . $SEDit_users_insert . "<br>" . $conn->error;
				}

				header('Location: index.php');
			}
		}


	}

	

}else{

	header('Location: login.php');
}

?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>SEDit</title>
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.1/css/bulma.min.css">
		<link rel="stylesheet" href="CSS\index.css">
  	</head>
	<body>
		<div class="section">


			<section class="hero is-primary">
			  <div class="hero-body">
			    <div class="container">
			      <h1 class="title">
			        SEDit
			      </h1>
			      <h2 class="subtitle">
			        Social network simplified.
			      </h2>

			    <div style="float: right;">
				    <form method="post">
						<div class="field">
							<p class="control">
								<button class="button is-danger" type="submit" name="logoutbtn">
									Logout
								</button>
							</p>
						</div>
					</form>
				</div>

			    </div>
			  </div>
			</section>

				<div class="box noradius" id="grey">
				    <nav class="level is-mobile" id="white">
				  <div class="level-item has-text-centered">
				    <div>
				      <p class="heading">Seds</p>
				      <p class="title"><?php echo $SEDit_posts_gathered_posts; ?></p>
				    </div>
				  </div>
				  <div class="level-item has-text-centered">
				    <div>
				      <p class="heading">Following</p>
				      <p class="title"><?php echo $SEDit_posts_gathered_following; ?></p>
				    </div>
				  </div>
				  <div class="level-item has-text-centered">
				    <div>
				      <p class="heading">Followers</p>
				      <p class="title"><?php echo $SEDit_posts_gathered_followers; ?></p>
				    </div>
				  </div>
				  <div class="level-item has-text-centered">
				    <div>
				      <p class="heading">Likes</p>
				      <p class="title"><?php echo $SEDit_likes_gathered_likes; ?></p>
				    </div>
				  </div>
				</nav>
			</div>

			<br/><br/>

			<div class="tile is-ancestor">
				<div class="tile is-3">

				</div>
				<div class="tile is-6 is-vertical is-parent">

					<div class="tile is-child">
						
						<div class="" style="">

							<form method="post">
								<div class="field is-horizontal">
								<div class="field" style="width: 100%;">
									 <p class="control has-icons-left">
									    <input class="input" type="text" name="postin" placeholder="What's on your mind?" required>
									    <span class="icon is-small is-left">
									      <i class="fa fa-user-circle"></i>
									    </span>
									  </p>
								</div>

							
								<div class="field" style="">
									<p class="control">
										<button class="button" type="submit" name="postbtn">
											<span class="icon is-small is-right">
										      <i class="fa fa-paper-plane"></i>
										    </span>
										</button>
									</p>
								</div>
							</div>
							</form>

						</div>

					</div>

					<?php

						if($SEDit_users_private == 1){

							$SEDit_users_posts = "SELECT ID, username, sex, fname, lname, content, likes, shares, replies FROM user_post_data WHERE private = '0' ORDER BY ID DESC";
							$SEDit_users_posts_results = $conn->query($SEDit_users_posts);

						}else{

							$SEDit_users_posts = "SELECT ID, username, sex, fname, lname, content, likes, shares, replies FROM user_post_data WHERE private = '0' ORDER BY ID DESC";
							$SEDit_users_posts_results = $conn->query($SEDit_users_posts);

						}


						if ($SEDit_users_posts_results->num_rows > 0) {
							    // output data of each row
							while($SEDit_users_posts_row = $SEDit_users_posts_results->fetch_assoc()) {
								$SEDit_users_posts_ID = $SEDit_users_posts_row['ID'];
								$SEDit_users_posts_username = $SEDit_users_posts_row['username'];
								$SEDit_users_posts_sex = $SEDit_users_posts_row['sex'];
							    $SEDit_users_posts_fname = $SEDit_users_posts_row['fname'];
							    $SEDit_users_posts_lname = $SEDit_users_posts_row['lname'];
							    $SEDit_users_posts_content = $SEDit_users_posts_row['content'];
							    $SEDit_users_posts_likes = $SEDit_users_posts_row['likes'];
							    $SEDit_users_posts_shares = $SEDit_users_posts_row['shares'];
							    $SEDit_users_posts_replies = $SEDit_users_posts_row['replies'];
							    //echo $SEDit_users_posts_ID;


							    

					?>
					<div class="tile is-child">
				    	<div class="box">
							<article class="media">
							    <div class="media-left">
							      <figure class="image is-64x64">
							        <img src="<?php if($SEDit_users_posts_sex == 'male'){ echo 'm'; }elseif($SEDit_users_posts_sex == 'female'){ echo 'f'; }?>profile.png" alt="Image">
							      </figure>
							    </div>
							    <div class="media-content">
							      <div class="content">
							        <p>
							            <p class="has-text-left"><strong><?php echo $SEDit_users_posts_fname; echo ' '; echo $SEDit_users_posts_lname; ?></strong> <small>@<?php echo $SEDit_users_posts_username; ?></small>
							            	<?php


							            		$SEDit_user_following = "SELECT username, user FROM user_following WHERE username = '$SEDit_users_username'";
												$SEDit_user_following_results = $conn->query($SEDit_user_following);

												if ($SEDit_user_following_results->num_rows > 0) {
																		    // output data of each row
													while($SEDit_user_following_results_row = $SEDit_user_following_results->fetch_assoc()) {

														$SEDit_user_following_username = $SEDit_user_following_results_row['username'];

														$SEDit_user_following_user = $SEDit_user_following_results_row['user'];

														//echo $SEDit_users_posts_ID;
														if($SEDit_users_username == $SEDit_users_posts_username){

															$SEDit_following_bool = true;

														}else{
															if($SEDit_user_following_user == $SEDit_users_posts_username){

																$SEDit_following_bool = true;

															}else{

																$SEDit_following_bool = false;
															}
														}
														

													}
												}
							            	?>
							            	<form method="post">
								          		<button class="button" type="submit" name="followbtn" value="<?php echo $SEDit_users_posts_username; ?>" style="float: right; display:<?php if($SEDit_following_bool == true){ echo "none"; }else{ echo "block"; }?>">
													<span class="icon is-small">
											      		<i class="fa fa-user-plus"></i>
											    	</span>
												</button>
											</form>
										</p>
							          
							          <?php echo $SEDit_users_posts_content; ?>
							        </p>
							      </div>
							      <br/>
							      
							      <nav class="level is-mobile">
							        <div class="level-left">

							        <form action="user_post.php" method="post">
							            <button class="level-item" type="submit" name="repliesin" value="<?php echo $SEDit_users_posts_ID;?>" style="padding: 0.4em 1em;"><i class="fa fa-reply"></i> <?php echo $SEDit_users_posts_replies; ?></button>
							        </form>

							            <button class="level-item" type="submit" name="sharesin" style="padding: 0.4em 1em;"><i class="fa fa-retweet"></i> <?php echo $SEDit_users_posts_shares; ?></button>

							        <form action="data.php" method="post">
							            <button class="level-item" type="submit" name="likesin" value="<?php echo $SEDit_users_posts_ID;?>" style="padding: 0.4em 1em;"><i class="fa fa-heart"></i> <?php echo $SEDit_users_posts_likes; ?></button>
							        </form>

							        </div>
							      </nav>
							      
							    </div>
							</article>
						</div>
					</div>
					<?php 
						}}

					?>


				</div>
				<div class="tile is-3">

				</div>

			</div>
	<footer class="footer" id="foot">
  		<div class="container">
    		<div class="content has-text-centered">
      			<p>
        			&#169;2017 <strong>Goon Studios, LLC.</strong> 	
      			</p>
    		</div>
  		</div>
	</footer>

	</body>
</html>