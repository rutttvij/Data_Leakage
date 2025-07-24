<?php require_once("../server/connect.php"); ?>
<?php include_once("../session.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Profile</title>
	<?php include_once("bootstrap.php"); ?>
	<style>
		.dashboard_background {
			background-color: #f4f7fa;
		}
		.card-custom {
			background-color: #ffffff;
			border: none;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			margin-bottom: 30px;
		}
		.card-body-custom {
			padding: 30px;
		}
		.profile-img {
			width: 150px;
			height: 150px;
			border-radius: 50%;
			margin: 20px auto;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
		}
		.profile-info {
			font-size: 1.1rem;
			color: #333;
			padding: 10px 0;
			margin: 10px 0;
			background-color: #f8f9fa;
			border-radius: 5px;
			padding: 15px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
		}
		.profile-info strong {
			font-weight: 600;
			color: #0056b3;
		}
		.btn-custom {
			background-color: #0056b3;
			color: white;
			border: none;
			border-radius: 5px;
			padding: 10px 20px;
			transition: background-color 0.3s ease;
		}
		.btn-custom:hover {
			background-color: #004494;
		}
		.btn-light-custom {
			background-color: white;
			color: black;
			border: 1px solid black;
			border-radius: 5px;
			padding: 10px 15px;
			margin-top: 20px;
		}
		.text-center a {
			color: #fcfeff;
			font-weight: bold;
			text-decoration: none;
			transition: color 0.3s ease;
		}
		.text-center a:hover {
			color: #003d7a;
		}
	</style>
</head>
<body class="dashboard_background">
	<?php include_once("menubar.php"); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 mx-auto">
				<div class="card card-custom my-3">
					<div class="card-body card-body-custom">
						<?php
						$userid = $_SESSION['user_id'];
						$sql = "SELECT * FROM users WHERE id='$userid' LIMIT 1";
						$result = mysqli_query($conn, $sql);     
						$rows = mysqli_fetch_array($result);
						?>

						<div class="text-center">
							<img src="../assets/profiles/<?=$rows['profile']?>" alt="Profile Image" class="profile-img">
						</div>

						<div class="row">
							<div class="col-sm-10 mx-auto my-3">
								<div class="profile-info"><strong>Username:</strong> <?=$rows['username']?></div>
								<div class="profile-info"><strong>Email ID:</strong> <?=$rows['email']?></div>
								<div class="profile-info"><strong>Gender:</strong> <?=$rows['gender']?></div>
								<div class="profile-info"><strong>Mobile:</strong> <?=$rows['mobile']?></div>
								<div class="text-center mt-4">
									<a href="profile.php" class="btn btn-custom">Edit Profile</a>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container text-center my-3">
		<button class="btn btn-light-custom" onclick="window.history.back();">Back</button>
	</div>	
</body>
</html>
