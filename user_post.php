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

	$SEDit_likes_gathered = "SELECT likes, posts FROM gathered WHERE email='$SEDit_users_email'";
	$SEDit_likes_gathered_results = $conn->query($SEDit_likes_gathered);

	if ($SEDit_likes_gathered_results->num_rows > 0) {
							    // output data of each row
		while($SEDit_likes_gathered_results_row = $SEDit_likes_gathered_results->fetch_assoc()) {

			$SEDit_likes_gathered_likes = $SEDit_likes_gathered_results_row['likes'];

			$SEDit_posts_gathered_posts = $SEDit_likes_gathered_results_row['posts'];

						//echo $SEDit_users_posts_ID;

		}
	}

	if(isset($_POST['repliesin'])){
		$_SESSION['post_id'] = $_POST['repliesin'];
	}

	
	
	$SEDit_post_ID = $_SESSION['post_id'];



	//echo $SEDit_post_ID;



	if($_POST){

		



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

				
				$SEDit_updated_gathered_posts = $SEDit_posts_gathered_posts + 1;

				$SEDit_post_info = "SELECT username, likes, replies FROM user_post_data WHERE ID='$SEDit_post_ID'";
				$SEDit_post_info_results = $conn->query($SEDit_post_info);

				if ($SEDit_post_info_results->num_rows > 0) {
										    // output data of each row
					while($SEDit_post_info_results_row = $SEDit_post_info_results->fetch_assoc()) {

						$SEDit_post_info_replies = $SEDit_post_info_results_row['replies'];
						$SEDit_post_info_username = $SEDit_post_info_results_row['username'];

						//echo $SEDit_post_info_username;

					}
				}
				$SEDit_updated_replies = $SEDit_post_info_replies + 1;

				// GET VALUE FROM FORM POST

				

				// UPDATE REPLIES IN POSTS

				$SEDit_user_post = "UPDATE user_post_data SET replies = '$SEDit_updated_replies' WHERE ID = '$SEDit_post_ID'";

				// UPDATE USER LOGGED IN POSTS

				$SEDit_posts_gathered_quer = "UPDATE gathered SET posts = '$SEDit_updated_gathered_posts' WHERE email = '$SEDit_users_email'";

				// INSERT DATA TO TABLE

				$SEDit_users_insert = "INSERT INTO post_replies (postID, username, sex, fname, lname, email, content) VALUES ('$SEDit_post_ID', '$SEDit_username', '$SEDit_sex', '$SEDit_fname', '$SEDit_lname', '$SEDit_email', '$SEDit_content')";

				if ($conn->query($SEDit_users_insert) === TRUE & $conn->query($SEDit_posts_gathered_quer) === TRUE & $conn->query($SEDit_user_post) === TRUE) {
					//echo "New record created successfully";
					header('Location: user_post.php');
				} else {
					//echo "Error: " . $SEDit_users_insert . "<br>" . $conn->error;
				}

				
			}
		}


	}

	$SEDit_post_info = "SELECT username FROM user_post_data WHERE ID='$SEDit_post_ID'";
	$SEDit_post_info_results = $conn->query($SEDit_post_info);

	if ($SEDit_post_info_results->num_rows > 0) {
		// output data of each row
		while($SEDit_post_info_results_row = $SEDit_post_info_results->fetch_assoc()) {

			//$SEDit_post_info_replies = $SEDit_post_info_results_row['replies'];
			$SEDit_post_info_username = $SEDit_post_info_results_row['username'];

			//echo $SEDit_post_info_username;

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
				      <p class="title">0</p>
				    </div>
				  </div>
				  <div class="level-item has-text-centered">
				    <div>
				      <p class="heading">Followers</p>
				      <p class="title">0</p>
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

					<a href="index.php" style="height: 100%; padding: 1em; font-size: 2em; background-color: rgba(20,20,20,0.1);"><i class="fa fa-reply"></i></a>
				</div>
				<div class="tile is-6 is-vertical is-parent">

					<div class="tile is-child">
						
						<div class="" style="">

							<form method="post">
								<div class="field is-horizontal">
								<div class="field" style="width: 100%;">
									 <p class="control has-icons-left">
									    <input class="input" type="text" name="postin" placeholder="Reply to ..." required>
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

						//$SEDit_post_ID = $_POST['repliesin'];

						$SEDit_users_posts = "SELECT ID, username, sex, fname, lname, content, likes, shares, replies FROM user_post_data WHERE ID = '$SEDit_post_ID'";
						$SEDit_users_posts_results = $conn->query($SEDit_users_posts);


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
							          <p class="has-text-left"><strong><?php echo $SEDit_users_posts_fname; echo ' '; echo $SEDit_users_posts_lname; ?></strong> <small>@<?php echo $SEDit_users_posts_username; ?></small></p>
							          
							          <?php echo $SEDit_users_posts_content; ?>
							        </p>
							      </div>
							      <br/>
							      
							      <nav class="level is-mobile">
							        <div class="level-left">

							        
							            <button class="level-item" type="submit" name="repliesin"  style="padding: 0.4em 1em;"><i class="fa fa-reply"></i> <?php echo $SEDit_users_posts_replies; ?></button>

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

					<?php

						//$SEDit_post_ID = $_POST['repliesin'];

						$SEDit_users_posts = "SELECT ID, postID, username, sex, fname, lname, email, content FROM post_replies WHERE postID = '$SEDit_post_ID'";
						$SEDit_users_posts_results = $conn->query($SEDit_users_posts);


						if ($SEDit_users_posts_results->num_rows > 0) {
							    // output data of each row
							while($SEDit_users_posts_row = $SEDit_users_posts_results->fetch_assoc()) {

								$SEDit_users_posts_ID = $SEDit_users_posts_row['ID'];
							    $SEDit_users_posts_content = $SEDit_users_posts_row['content'];
							    $SEDit_users_posts_username = $SEDit_users_posts_row['username'];
							    $SEDit_users_posts_sex = $SEDit_users_posts_row['sex'];
							    $SEDit_users_posts_fname = $SEDit_users_posts_row['fname'];
							    $SEDit_users_posts_lname = $SEDit_users_posts_row['lname'];

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
							          <p class="has-text-left"><strong><?php echo $SEDit_users_posts_fname; echo ' '; echo $SEDit_users_posts_lname; ?></strong> <small>@<?php echo $SEDit_users_posts_username; ?></small></p>
							          <p class="has-text-right"><strong>Reply to </strong>@<?php echo $SEDit_post_info_username; ?></p>
							          
							          <?php echo $SEDit_users_posts_content; ?>
							        </p>
							      </div>
							      <br/>
							      
							      <nav class="level is-mobile">
							        <div class="level-left">

							        
							            <button class="level-item" type="submit" name="repliesin"  style="padding: 0.4em 1em;"><i class="fa fa-reply"></i> <?php //echo $SEDit_users_posts_replies; ?></button>

							            <button class="level-item" type="submit" name="sharesin" style="padding: 0.4em 1em;"><i class="fa fa-retweet"></i> <?php //echo $SEDit_users_posts_shares; ?></button>

							        <form action="data.php" method="post">
							            <button class="level-item" type="submit" name="likesin" value="<?php echo $SEDit_users_posts_ID;?>" style="padding: 0.4em 1em;"><i class="fa fa-heart"></i> <?php //echo $SEDit_users_posts_likes; ?></button>
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

	</body>
</html>