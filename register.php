<?php
	session_start();

	if(isset($_SESSION['SEDit_con_user'])){

		if($_SESSION['SEDit_con_user'] == 'SEDit_con_user_true'){

			header('Location: index.php');

		}

	}
	

	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'SEDit';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die('Connection failed: ' . $conn->connect_error);
	}

	$SEDit_bool_email = false;
	$SEDit_bool_username = false;

	if($_POST){

		$SEDit_users = "SELECT username, email FROM users";
		$SEDit_users_results = $conn->query($SEDit_users);


		if ($SEDit_users_results->num_rows > 0) {
		    // output data of each row
		    while($SEDit_users_row = $SEDit_users_results->fetch_assoc()) {
		        $SEDit_users_email = $SEDit_users_row['email'];
		        $SEDit_users_username = $SEDit_users_row['username'];
		        //echo $SEDit_users_email;
		    }
		}

		

		if($_POST['passwordin'] == $_POST['passwordin_confirm']){

			$SEDit_username = $_POST['usernamein'];
			$SEDit_first_name = $_POST['firstnamein'];
			$SEDit_last_name = $_POST['lastnamein'];
			$SEDit_dob = $_POST['dobin'];
			$SEDit_email = $_POST['emailin'];
			$SEDit_password = $_POST['passwordin'];
			$SEDit_sex = $_POST['sexin'];
			$SEDit_f_user = 'jeanlaureano';

			if(isset($_POST['emailin'])){

				if($_POST['emailin'] == $SEDit_users_email){

					$SEDit_bool_email = true;

				}else{

					if($_POST['usernamein'] == $SEDit_users_username){

						$SEDit_bool_username = true;
					}else{

						$SEDit_users_insert = "INSERT INTO users (username, fname, lname, dob, sex, email, password) VALUES ('$SEDit_username', '$SEDit_first_name', '$SEDit_last_name', '$SEDit_dob', '$SEDit_sex', '$SEDit_email', '$SEDit_password')";

						$SEDit_gathered = "INSERT INTO gathered (email, username, likes, posts, following, followers) VALUES ('$SEDit_email', '$SEDit_username', '0', '0', '1', '0')";

						$SEDit_following_insert = "INSERT INTO user_following (username, user) VALUES ('$SEDit_username', '$SEDit_f_user')";

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

						$SEDit_following_update_other = "UPDATE gathered SET followers = '$SEDit_f_gathered_followers' WHERE username = '$SEDit_f_user'";

						if ($conn->query($SEDit_users_insert) === TRUE & $conn->query($SEDit_gathered) === TRUE & $conn->query($SEDit_following_insert) === TRUE & $conn->query($SEDit_following_update_other) === TRUE) {
						    //echo "New record created successfully";
						} else {
						    //echo "Error: " . $SEDit_users_insert . "<br>" . $conn->error;
						}

						header('Location: login.php');

					}

				}
			}
		}else{

			echo "Password does not match";
		}
	}



?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>SEDit - Sign up</title>
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.1/css/bulma.min.css">
		<link rel="stylesheet" href="CSS\index.css">
  	</head>
	<body> 
		<div class="section">


			<section class="hero is-primary">
			  <div class="hero-body">
			    <div class="container padded">
			      <h1 class="title">
			        SEDit
			      </h1>
			      <h2 class="subtitle">
			        Social network simplified.
			      </h2>

			    <form method="post" style="width: 100%;">

			    	<?php

			    		if($SEDit_bool_email == true){
			    			echo "<span class='subtitle'>* Email is already in use, please try again</span>";
			    		}else{
			    			if($SEDit_bool_username == true){
			    				echo "<span class='subtitle'>* Username is already in use, please try again</span>";
					    	}else{
					    			echo "<span class='subtitle'>Sign Up</span>";
					    	}
					    }

			    	?>

			    	<div class="field is-horizontal">
				    	<div class="field" style="width: 100%;">
							  <p class="control has-icons-left">
							    <input class="input" type="text" name="firstnamein" placeholder="First Name" required>
							    <span class="icon is-small is-left">
							      <i class="fa fa-user"></i>
							    </span>
							  </p>
						</div>
						
						<div class="field" style="width: 100%;">
							  <p class="control">
							    <input class="input" type="text" name="lastnamein" placeholder="Last Name" required>
							  </p>
						</div>
					</div>

					<div class="field is-horizontal">
						<div class="field" style="width: 100%;">
							  <p class="control has-icons-left">
							    <input class="input <?php if($SEDit_bool_username == true){ echo 'is-danger'; }else{ echo '';}?>" type="text" name="usernamein" placeholder="Username" required>
							    <span class="icon is-small is-left">
							      <i class="fa fa-at"></i>
							    </span>
							  </p>
						</div>

						<div class="field" style="width: 100%;">
							<p class="control">
								<input class="input" type="date" name="dobin" placeholder="dob" required> 
							</p>
						</div>
					</div>

					<div class="field is-horizontal">
			      	<div class="field" style="width: 100%;">
						  <p class="control has-icons-left has-icons-right">
						    <input class="input <?php if($SEDit_bool_email == true){ echo 'is-danger'; }else{ echo '';}?>" type="email" name="emailin" placeholder="Email" required>
						    <span class="icon is-small is-left">
						      <i class="fa fa-envelope"></i>
						    </span>
						    <span class="icon is-small is-right">
						      <i class="fa <?php if($SEDit_bool == true){ echo "fa-times"; } else{ echo "fa-check"; }?>"></i>
						    </span>
						  </p>
					</div>

					<div class="field" style="width: 100%;">
						<select class="input" name="sexin" required>
							<option value="male">Male</option>
							<option value="female">Female</option>
						</select>
					</div>

					</div>

					<div class="field is-horizontal">
						<div class="field" style="width: 100%;">
							  <p class="control has-icons-left">
							    <input class="input" type="password" name="passwordin" placeholder="Password" required>
							    <span class="icon is-small is-left">
							      <i class="fa fa-lock"></i>
							    </span>
							  </p>
						</div>
						
						<div class="field" style="width: 100%;">
							  <p class="control">
							    <input class="input" type="password" name="passwordin_confirm" placeholder="Confirm Password" required>
							  </p>
						</div>
					</div>



					<div class="">
						<input class="button is-info" type="submit" name="submit" value="Sign up">
					</div>
				</form>

			    </div>
			  </div>
			</section>

				<div class="box noradius" id="grey">
				    <nav class="level is-mobile" id="white">
				  <div class="level-item has-text-centered">
				    <div>
				      <p class="heading">- Albert Einstein -</p>
				      <p class="heading">“There are only two ways to live your life. One is as though nothing is a miracle. The other is as though everything is a miracle.”</p>
				    </div>
				  </div>
				</nav>
			</div>
			<br/><br/>

			<div class="tile is-ancestor">
				<div class="tile is-3">

				</div>
				<div class="tile is-6 is-vertical is-parent">
					
					

				</div>
				<div class="tile is-3">

				</div>

			</div>

	</body>
</html>