<?php require_once("../server/connect.php"); ?>
<?php include_once("../session.php"); ?>
<?php include_once("library.php"); ?>
<?php 
function keyRequestStatus($fileid, $asker){
    global $conn;
    $sql = "SELECT * FROM key_requests WHERE file='$fileid' AND request_by_user='$asker'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        return $row;
    }
    return [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File List</title>
    <?php include_once("bootstrap.php"); ?>
    <style>
        .dashboard_background {
            background-color: #f8f9fa;
        }
        .table-container {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .table tbody tr:nth-child(even) {
            background-color: white;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        .btn-custom {
            margin: 5px;
        }
        .alert {
            margin-top: 20px;
        }
        .status-pending, .status-rejected, .status-shared {
            color: black;
            font-weight: bold;
        }
        .status-shared {
            color: black; 
        }
        .btn-back {
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-back:hover {
            background-color: #f8f9fa;
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
        <button class="btn btn-light" style="color: black; border: 1px solid black;" onclick="window.location.href='dashboard.php';">Back</button>
            
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" class="form-control search-input" placeholder="Search...">
                <button type="submit" class="btn btn-light" style="color: black; border: 1px solid black;">Search</button>
            </form>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="table-container">
                    <table class='table table-bordered table-striped'>
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Subject</th>
                                <th>File Name</th>
                                <th>File Size</th>
                                <th>For User</th>    
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>            
                        <?php
                        $self = $_SESSION['user_id'];    
                        $sql = "SELECT * FROM data_files WHERE sender_id != '$self'";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                $n = 0;
                                while ($rows = mysqli_fetch_array($result)) {
                                    $n++;
                                    $id = $rows['id'];
                                    $subject = $rows['subject'];
                                    $filename = $rows['file_name'];
                                    $filesize = $rows['file_size'];
                                    $userid = $rows['receiver_id'];
                                    $user = getusername($userid);
                                    $status = keyRequestStatus($id, $_SESSION['user_id']);
                        ?>
                        <tr>
                            <td><?=$n?></td>
                            <td><?=$subject?></td>
                            <td><?=$filename?></td>
                            <td><?=$filesize?></td>
                            <td><?=ucfirst($user)?></td> 
                            <td>
                                <?php 
                                if (sizeof($status) > 0) {
                                    if ($status['status'] == 'pending') {
                                        echo "<span class='status-pending'>Pending</span>";
                                        echo "<a href='ask_for_key.php?id=$id' class='btn btn-info btn-custom'>Ask for Secret Key</a>";
                                    } else if ($status['status'] == 'shared') {
                                        echo "<span class='status-shared'>Shared (<span style='color: black;'>".$status['secret_key']."</span>)</span>";
                                        echo "<a href='download.php?id=$id' class='btn btn-primary btn-custom'>Download</a>";
                                    } else if ($status['status'] == 'rejected') {
                                        echo "<span class='status-rejected'>Rejected</span>";
                                    }
                                } else {
                                    echo "<a href='download.php?id=$id' class='btn btn-primary btn-custom'>Download</a>";
                                    echo "<a href='ask_for_key.php?id=$id' class='btn btn-info btn-custom'>Ask for Secret Key</a>";
                                }
                                ?>    
                            </td> 
                        </tr>
                        <?php 
                                }
                            } else { 
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-danger">
                                    <strong>Failed:</strong> No files found.
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-danger">
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
