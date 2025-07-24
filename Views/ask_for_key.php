<?php 
require_once("../session.php");
require_once("../server/connect.php");

date_default_timezone_set('Asia/Kolkata');

$id = $_GET['id'];
$asker = $_SESSION['user_id'];

function getfiledetail($fileid) {
    global $conn;
    $sql = "SELECT * FROM data_files WHERE id='$fileid' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_array($result);
}

$file = getfiledetail($id);
$secret_key = $file['secret_key'];
$request_to_user = $file['sender_id'];
$time_of_request = date('Y-m-d H:i:s'); 

$sql = "INSERT INTO key_requests(request_by_user, request_to_user, file, secret_key, status, time_of_request)
        VALUES('$asker', '$request_to_user', '$id', '$secret_key', 'pending', '$time_of_request')";
mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Sent</title>
    <style>
        body {
            background-color: #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }
        .message-box {
            background-color: #ffffff; 
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); 
            text-align: center;
            max-width: 400px; 
            width: 90%;
        }
        .message-box h1 {
            color: #343a40; 
            margin-bottom: 20px;
        }
        .message-box p {
            color: #6c757d; 
            margin-bottom: 20px;
        }
        .button {
            padding: 12px 24px;
            background-color: #007bff; 
            color: white;
            border: none;
            border-radius: 8px; 
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Request Sent</h1>
        <p>Your request for the secret key has been successfully sent.</p>
        <a class="button" href="dashboard.php">Return to Dashboard</a>
    </div>
</body>
</html>
