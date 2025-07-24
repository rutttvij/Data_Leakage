<?php 
require_once("../server/connect.php"); 
include_once("../session.php"); 
include_once("../sanitize.php"); 
require_once("hasAccessUser.php"); 
require_once("library.php");

date_default_timezone_set('Asia/Kolkata');

check_attempts($_GET['id']);

function checkSecretKeyRequest($id, $user_id){
    global $conn;
    $sql = "SELECT * FROM key_requests WHERE file='$id' AND request_by_user='$user_id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        if($row['status'] == 'pending' || $row['status'] == 'rejected'){
            return 'no';
        }        
        return "yes";
    }
    return 'no';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include_once("bootstrap.php"); ?>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 mx-auto">
                <div class="card my-5">
                    <div class="card-body">
<?php
if(isset($_POST['download'])){    
    $id = $_GET['id'];    
    $user_id = $_SESSION['user_id'];
    
    $remaining_attempts = get_attempt($id);
    if($remaining_attempts == 0){
        echo "
        <div class='alert alert-danger'>
            Cannot be Downloadable. You have reached the maximum number of attempts.
        </div>";
    } else {
        $secret_key = sanitize($_POST['secret_key']);
        $sql = "SELECT * FROM data_files WHERE id='$id' AND secret_key='$secret_key'";
        $result = mysqli_query($conn, $sql);
        if($result){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);

                $current_time = date("Y-m-d H:i:s"); 
                $update_sql = "UPDATE data_files SET time_of_download='$current_time' WHERE id='$id'";
                mysqli_query($conn, $update_sql);

                $status = checkSecretKeyRequest($row['id'], $_SESSION['user_id']);
                if($status == "no"){
                    $subject = $row['subject'];
                    $secret_key = $row['secret_key'];
                    $file_id = $row['id'];
                    $created_at = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO leakers(user_id, subject, file_id, secret_key, leaked_at) VALUES ('$user_id', '$subject', '$file_id', '$secret_key', '$created_at')";
                    mysqli_query($conn, $sql);

                    echo "
                    <div class='alert alert-danger'>
                        You have been marked as a leaker. Date and Time: $created_at
                    </div>";
                }

                $filename = $row['file_name'];
                $url = "../assets/files/$filename";
                $ext = pathinfo($url, PATHINFO_EXTENSION);
                if($ext == "pdf"){
                    $url = "../download_with_watermark.php?name=$filename";
                    header("Location:$url");
                    exit();
                } else {
                    $contenttype = "application/force-download";
                    header("Content-Type: " . $contenttype);
                    header("Content-Disposition: attachment; filename=\"" .$filename. "\";");
                    readfile("../assets/files/".$filename);
                    exit();        
                }

            } else {            
                mark_attempt($id);            
                $remaining_attempts = get_attempt($id);
                echo "
                <div class='alert alert-danger'>
                    Invalid secret key, try again. Only $remaining_attempts attempt(s) left.
                </div>";
                if($remaining_attempts == 0){
                    $created_at = date("Y-m-d H:i:s"); 
                    $sql = "INSERT INTO leaked_messages(user_id, file_id, created_at) VALUES ('$user_id', '$id', '$created_at')";
                    $result = mysqli_query($conn, $sql);
                }
            }
        }
    }
}
?>
                        <form action="download.php?id=<?=$_GET['id']?>" method="POST">
                            <div class="mb-2">
                                <input 
                                placeholder="Enter Secret Key to download file" 
                                type="text" name="secret_key" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <input type="submit" name="download" value="Download" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-3">
        <button class="btn btn-light" style="color: black; border: 1px solid black;" onclick="window.history.back();">Back</button>
    </div>
</body>
</html>
