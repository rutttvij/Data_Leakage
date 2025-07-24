<?php require_once("../server/connect.php"); ?>
<?php include_once("../session.php"); ?>
<?php include_once("library.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File List</title>
    <?php include_once("bootstrap.php"); ?>
    <style>
        .dashboard_background {
            background-image: url('your-background-image.jpg'); 
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .table-custom {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            table-layout: fixed; 
        }

        .table-custom thead {
            background-color: #007bff;
            color: white;
        }

        .table-custom th, .table-custom td {
            text-align: center;
            padding: 12px;
            word-wrap: break-word;
        }

        .table-custom tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-custom tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-light-custom {
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        .btn-light-custom:hover {
            background-color: #f8f9fa;
        }

        .alert-custom {
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .container {
            margin-top: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            align-items: center;
        }

        .search-input {
            margin-right: 10px;
        }
    </style>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?>

    <div class="container">
        <div class="top-bar">
            <button class="btn btn-light-custom" onclick="window.history.back();">Back</button>
            
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" class="form-control search-input" placeholder="Search...">
                <button type="submit" class="btn btn-light-custom">Search</button>
            </form>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-custom my-5">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Subject</th>
                                <th>File Name</th>
                                <th>File Size (MB)</th>
                                <th>For User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $self = $_SESSION['user_id'];    
                            $sql = "SELECT * FROM data_files WHERE sender_id='$self'";
                            $result = mysqli_query($conn, $sql);
                            if($result) {
                                if(mysqli_num_rows($result) > 0) {
                                    $n = 0;
                                    while($rows = mysqli_fetch_array($result)) {
                                        $n++;
                                        $subject = $rows['subject'];
                                        $filename = $rows['file_name'];
                                        $filesize = number_format($rows['file_size'], 2); 
                                        $userid = $rows['receiver_id'];
                                        $user = getusername($userid);
                            ?>
                            <tr>
                                <td><?=$n?></td>
                                <td><?=$subject?></td>
                                <td><?=$filename?></td>
                                <td><?=$filesize?></td>
                                <td><?=ucfirst($user)?></td>
                            </tr>
                            <?php 
                                    }
                                } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-custom alert-danger">
                                        <strong>Failed:</strong> No files found.
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else { 
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-custom alert-danger">
                                        <strong>Failed:</strong> Error retrieving data.
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
