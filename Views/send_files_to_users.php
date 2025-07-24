<?php 
require_once("../server/connect.php"); 
include_once("../session.php"); 
include_once("../sanitize.php");


date_default_timezone_set('Asia/Kolkata');

function generateSecretKey() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $secretKey = '';
    
    for ($i = 0; $i < 4; $i++) {
        $secretKey .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $secretKey;
}

function encryptToMorseCode($inputString) {
    $morseCodeMap = [
		'0' => '.---',  '1' => '---..',  '2' => '-.-.',  '3' => '--.-',  '4' => '..-.',
		'5' => '.-.',   '6' => '-...',   '7' => '--..',  '8' => '----.', '9' => '-.-',
		'A' => '...-',  'B' => '--',     'C' => '.-..',  'D' => '-..-',  'E' => '..',
		'F' => '-..',   'G' => '-....',  'H' => '-.-.',  'I' => '.----', 'J' => '..-',
		'K' => '---',   'L' => '.-',     'M' => '....',  'N' => '-.',    'O' => '-..-',
		'P' => '..--',  'Q' => '--...',  'R' => '.....', 'S' => '---..', 'T' => '--.',
		'U' => '....-', 'V' => '.--.',   'W' => '-.-',   'X' => '.--',   'Y' => '---.',
		'Z' => '--..-'
	];
    
    $inputString = strtoupper($inputString);
    $encryptedMorse = '';
    
    foreach (str_split($inputString) as $char) {
        if (isset($morseCodeMap[$char])) {
            $encryptedMorse .= $morseCodeMap[$char] . ' ';
        }
    }
    
    return trim($encryptedMorse);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Send File</title>
	<?php include_once("bootstrap.php"); ?>
	<style>
		.dashboard_background {
			background-color: #f4f7fa; 
		}
		.card-custom {
			border: none;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			margin-top: 20px;
			margin-bottom: 20px;
		}
		.card-body-custom {
			padding: 30px;
		}
		.form-control {
			border-radius: 5px;
			border-color: #ddd;
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
		}
		.form-control:focus {
			border-color: #0056b3;
			box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
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
		.alert-custom {
			border-radius: 5px;
			margin-bottom: 20px;
		}
		.btn-light-custom {
			background-color: white;
			color: black;
			border: 1px solid black;
			border-radius: 5px;
			padding: 10px 20px;
			margin-top: 20px;
		}
		.text-center a {
			color: #0056b3;
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
			<div class="col-sm-8 mx-auto">
				<div class="card card-custom">
					<div class="card-body card-body-custom">
						<?php
						if(isset($_POST['send_file'])){
							$userid = sanitize($_POST['userid']);
							$senderid = sanitize($_SESSION['user_id']);
							$subject = sanitize($_POST['subject']);

							$filename = $_FILES['file']['name'];
							$file_tmpname = $_FILES['file']['tmp_name'];
							$filesize = $_FILES['file']['size'] / (1024 * 1024); 
							$url = "../assets/files/$filename";
							$ext = pathinfo($url, PATHINFO_EXTENSION);

							if(!empty($subject) && !empty($filename) && !empty($userid)) {
								if($filesize > 100) {
									echo "
									<div class='alert alert-warning alert-custom'>
									<strong>Failed:</strong> File size must be less than 100 MB.
									</div>
									";
								} else {
									if(strtolower($ext) == "pdf") {
										$secretkey = generateSecretKey();
                						$encryptedKey = encryptToMorseCode($secretkey);
										$filename = md5(mt_rand(1, 10000)) . ".$ext";
										$url = "../assets/files/$filename";

										$time_of_transfer = date('Y-m-d H:i:s'); 
										$sql = "INSERT INTO data_files (subject, file_name, file_size, sender_id, receiver_id, secret_key, time_of_transfer)
        											VALUES ('$subject', '$filename', '$filesize', '$senderid', '$userid', '$encryptedKey', '$time_of_transfer')";

										$result = mysqli_query($conn, $sql);
										if($result) {
											if(move_uploaded_file($file_tmpname, $url)) {
												echo "
												<div class='alert alert-success alert-custom'>
												<strong>Success:</strong> File successfully sent.
												</div>
												";
											} else {
												$sql1 = "DELETE FROM data_files WHERE file_name='$filename' AND sender_id='$senderid' LIMIT 1";
												$result1 = mysqli_query($conn, $sql1);
												if($result1) {
													echo "
													<div class='alert alert-danger alert-custom'>
													<strong>Failed:</strong> File upload failed.
													</div>
													";
												} else {
													$error = mysqli_error($conn);
													echo "
													<div class='alert alert-danger alert-custom'>
													<strong>Failed:</strong> Database error: $error
													</div>
													";
												}
											}
										} else {
											$error = mysqli_error($conn);
											echo "
											<div class='alert alert-danger alert-custom'>
											<strong>Failed:</strong> Database error: $error
											</div>
											";
										}
									} else {
										echo "
										<div class='alert alert-warning alert-custom'>
										<strong>Failed:</strong> Only PDF files are allowed.
										</div>
										";
									}
								}
							} else {
								echo "
								<div class='alert alert-warning alert-custom'>
								<strong>Failed:</strong> Please fill in all fields.
								</div>
								";
							}
						}
						?>

						<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
							<div class="form-group mb-3">
								<label for="user">Select User</label>
								<select name='userid' class='form-control' required>
									<option value="">Select a user</option>
									<?php								
									$self = $_SESSION['user_id'];
									$sql = "SELECT * FROM users WHERE admin_active='1' AND id<>'$self' ORDER BY id ASC";
									$result = mysqli_query($conn, $sql);
									if($result && mysqli_num_rows($result) > 0) {
										while($rows = mysqli_fetch_array($result)) {
											$userid = $rows['id'];
											$username = ucfirst($rows['username']);
											echo "<option value='$userid'>$username</option>";
										}
									}	
									?>								
								</select>
							</div>

							<div class="form-group mb-3">
								<label for="subject">Subject</label>
								<input type="text" name="subject" class="form-control" required>
							</div>

							<div class="form-group mb-3">
								<label for="file">File</label>
								<input type="file" name="file" class="form-control" required>
							</div>

							<div class="form-group text-center">
								<input type="submit" name="send_file" value="Send" class="btn btn-custom">
							</div>
						</form>
					</div>
				</div>							
			</div>
		</div>
	</div>

	<div class="container text-center my-3">
		<button class="btn btn-light-custom" onclick="window.location.href='dashboard.php';">Back</button>
	</div>
</body>
</html>
