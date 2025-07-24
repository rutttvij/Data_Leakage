<?php 
require_once("../server/connect.php");
include_once("../session.php");
include_once("library.php");

if (isset($_POST['share_key'])) {
    $fileid = $_POST['fileid'];
    $requestby = $_POST['requestby'];

    $sql = "UPDATE key_requests SET status='shared' WHERE status='pending' AND file='$fileid' AND request_by_user='$requestby' LIMIT 1";
    mysqli_query($conn, $sql);
}

if (isset($_POST['decline'])) {
    $fileid = $_POST["fileid"];
    $requestby = $_POST["requestby"];

    $sql = "UPDATE key_requests SET status='rejected' WHERE file='$fileid' AND request_by_user='$requestby' LIMIT 1";
    mysqli_query($conn, $sql);     
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Sharing Requests</title>
    <?php include_once("bootstrap.php"); ?>
    <style>
        .dashboard_background {
            background-color: #f4f7fa; 
        }
        .table-custom {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table-custom thead {
            background-color: #007bff;
            color: white;
        }
        .table-custom th, .table-custom td {
            text-align: center;
            padding: 12px;
        }
        .table-custom tbody tr {
            background-color: #ffffff; 
        }
        .table-custom tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table-custom tbody tr:hover {
            background-color: #e9ecef;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-light-custom {
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .btn-light-custom:hover {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .back-button-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?>
    <div class="back-button-container">
        <button class="btn btn-light-custom" onclick="window.location.href='dashboard.php';">Back</button>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-custom">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Request By</th>
                            <th>File</th>
                            <th>Status</th>     
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $self = $_SESSION['user_id'];
                        $sql = "SELECT * FROM key_requests WHERE request_to_user='$self'";
                        $result = mysqli_query($conn, $sql);
                        $n = 0;
                        while($rows = mysqli_fetch_array($result)){
                            $n++;
                            $requestby = $rows['request_by_user'];
                            $fileid = $rows['file'];
                            $request_label = $rows['status'];            
                            $username = getusername($requestby);
                            $file_detail = getfiledetail($fileid);
                        ?>
                        <tr>
                            <td><?=$n?></td>
                            <td><?=$username?></td>
                            <td><?=$file_detail?></td>
                            <td>
                                <?php if($request_label == "pending"){ ?>
                                <form action="<?=$_SERVER['PHP_SELF']?>" method='post' style="display: inline;">
                                    <input type='hidden' name='fileid' value='<?=$fileid?>'>
                                    <input type='hidden' name='requestby' value='<?=$requestby?>'>
                                    <input type='submit' name='share_key' value='Share Key' class='btn btn-custom'>
                                </form>
                                <form action="<?=$_SERVER['PHP_SELF']?>" method='post' style="display: inline;">
                                    <input type='hidden' name='fileid' value='<?=$fileid?>'>
                                    <input type='hidden' name='requestby' value='<?=$requestby?>'>
                                    <input type='submit' name='decline' value='Decline' class='btn btn-custom'>
                                </form>
                                <?php } else if($request_label == "rejected"){ ?>
                                    <span class="text-danger">Rejected</span>
                                <?php } else if($request_label == "shared"){ ?>
                                    <span class="text-success">Shared</span>
                                <?php } ?>        
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
