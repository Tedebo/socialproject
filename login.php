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

	$SEDit_bool = false;

	if($_POST){

		$SEDit_users = "SELECT email, password FROM users";
		$SEDit_users_results = $conn->query($SEDit_users);

		if ($SEDit_users_results->num_rows > 0) {
		    // output data of each row
		    while($SEDit_users_row = $SEDit_users_results->fetch_assoc()) {
		        $SEDit_users_email = $SEDit_users_row['email'];
		        $SEDit_users_password = $SEDit_users_row['password'];
		        //echo $usernameGet;

		        if($_POST['emailin'] == $SEDit_users_email && $_POST['passwordin'] == $SEDit_users_password){

					$_SESSION['SEDit_con_user'] = 'SEDit_con_user_true';
					$_SESSION['SEDit_con_user_email'] = $_POST['emailin'];

					header('Location: index.php');

				}else{

					$SEDit_bool = true;
				}
		    }
		}


	}

?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>SEDit - Log in</title>
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

			    <form method="post" style="margin: 0px 15em;">
			    	<span class="subtitle"><?php if($SEDit_bool == true){ echo "* Incorrect Login, please try again"; } else{ echo "Login"; }?></span>
			      	
			      	<div class="field">
						  <p class="control has-icons-left has-icons-right">
						    <input class="input" type="email" name="emailin" placeholder="Email" required>
						    <span class="icon is-small is-left">
						      <i class="fa fa-envelope"></i>
						    </span>
						    <span class="icon is-small is-right">
						      <i class="fa fa-check"></i>
						    </span>
						  </p>
					</div>
					<div class="field">
						  <p class="control has-icons-left">
						    <input class="input" type="password" name="passwordin" placeholder="Password" required>
						    <span class="icon is-small is-left">
						      <i class="fa fa-lock"></i>
						    </span>
						  </p>
					</div>


					<div class="" style="float: left;">
						<input class="button is-info" type="submit" value="Login">
					</div>

					<div class="" style="float: right;">
						<a class="button is-info" href="register.php">Sign up</a>
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