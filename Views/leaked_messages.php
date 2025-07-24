<?php 
require_once("../server/connect.php"); 
include_once("../session.php");       
require_once("hasAccessUser.php");    
include_once("library.php");          

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: user_profile.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaked Messages</title>
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
            margin-bottom: 20px;
        }
        .card-custom h4 {
            color: #0056b3;
        }
        .delete-btn {
            margin-top: 10px;
        }
        .list-group-item {
            border: none;
        }
        .timestamp {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .btn-back {
            background-color: #f0f0f0;
            color: #0056b3;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?> 
    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-custom">
                    <h4 class="text-white bg-primary p-2 rounded">Leaked Messages</h4>
                    <?php 
                    $sql = "SELECT * FROM leaked_messages ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);
                    ?>
                    <div class="list-group">
                        <?php 
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($rows = mysqli_fetch_array($result)) { ?>
                                <div class="list-group-item">
                                    <?=getfiledetail($rows['file_id'])?> 
                                    <span class='text-primary'>
                                        <small>
                                            <?=ucfirst(getusername($rows['user_id']))?> tried to download the file
                                        </small>
                                    </span>
                                    <div class="timestamp">
                                        <small>Leaked at: <?=date("Y-m-d H:i:s", strtotime($rows['created_at']))?></small>
                                    </div>
                                    <a href="delete_leaked_message.php?id=<?=$rows['id']?>" class="btn btn-danger btn-sm delete-btn">Delete</a>                        
                                </div>
                        <?php 
                            }
                        } else { ?>
                            <div class="list-group-item">
                                <p>No leaked messages found.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center my-3">
            <button class="btn-back" onclick="window.history.back();">Back</button>
        </div>
    </div>
</body>
</html>
