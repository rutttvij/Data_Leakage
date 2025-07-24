<?php require_once("../server/connect.php"); ?>
<?php include_once("../session.php"); ?>
<?php require_once("hasAccessUser.php"); ?>
<?php include_once("library.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php include_once("bootstrap.php"); ?>
    <style>
        .dashboard_background {
            background-color: #f4f7fa; 
        }
        .sidebar {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            color: #343a40;
            font-weight: 500;
        }
        .sidebar a:hover {
            background-color: #f0f0f0;
            text-decoration: none;
            border-radius: 5px;
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
    </style>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?>
    <div class="container-fluid">
        <div class="row my-3">
            <div class="col-sm-3">
                <div class="sidebar">
                    <div class="list-group">
                        <a href="user_profile.php" class="list-group-item"><i class="bi bi-person-circle"></i> Profile</a>
                        <a href="send_files_to_users.php" class="list-group-item"><i class="bi bi-send-fill"></i> Send Files</a>
                        <a href="list_of_key_requests.php" class="list-group-item"><i class="bi bi-key-fill"></i> Key Requests</a>
                        <a href="list_of_files_send_by_me.php" class="list-group-item"><i class="bi bi-send-check-fill"></i> Files sent by me</a>
                        <a href="list_of_files_send_by_other_users.php" class="list-group-item"><i class="bi bi-people"></i> Received Files</a>
                        <?php 
                        if(isset($_SESSION['user_type'])){
                            if($_SESSION['user_type']=='admin'){
                        ?>
                        <a href="users.php" class="list-group-item"><i class="bi bi-info-circle-fill"></i> User registration request</a>
                        <a href="leaker_user_list.php" class="list-group-item"><i class="bi bi-paint-bucket"></i> Leaker</a>
                        <a href="leaked_messages.php" class="list-group-item"><i class="bi bi-exclamation-triangle-fill"></i> Leaked Messages</a>
                        <a href="transactions.php" class="list-group-item"><i class="bi bi-file-earmark-text"></i> Transactions</a>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <?php include_once('success_message.php');?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
