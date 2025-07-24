<?php require_once("../server/connect.php"); ?>
<?php include_once("../session.php"); ?>
<?php include_once("library.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaker Information</title>
    <?php include_once("bootstrap.php"); ?>
    <style>
        .table-custom {
            background-color: #f9f9f9;
            color: #333;
        }

        .table-custom th, .table-custom td {
            padding: 15px;
            text-align: left;
        }

        .table-custom thead {
            background-color: #007bff;
            color: white;
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-custom tbody tr:hover {
            background-color: #e0e0e0;
        }

        .btn-custom {
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
        }

        .btn-info-custom {
            background-color: #17a2b8;
        }

        .btn-danger-custom {
            background-color: #dc3545;
        }

        .btn-warning-custom {
            background-color: #ffc107;
        }

        .alert-leaker {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $currentUserId = $_SESSION['user_id'];
                $isLeakerQuery = "SELECT * FROM leakers WHERE user_id='$currentUserId'";
                $leakerResult = mysqli_query($conn, $isLeakerQuery);
                
                if (mysqli_num_rows($leakerResult) > 0) {
                    echo '<div class="alert-leaker">You are currently listed as a leaker. Please review your activities.</div>';
                }
                ?>
                <table class='table table-bordered table-custom my-5'>
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>File Details</th>
                            <th>Leaker</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM leakers ORDER BY id DESC";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                $n = 0;
                                while ($rows = mysqli_fetch_array($result)) {
                                    $n++;
                                    $id = $rows['id'];
                                    $userid = $rows['user_id'];
                                    $subject = $rows['subject'];
                                    $fileid = $rows['file_id'];
                                    $secretkey = $rows['secret_key'];
                                    $user = getuser($userid);
                                    $file_detail = getfiledetail($fileid);
                        ?>   
                        <tr>
                            <td><?=$n?></td>
                            <td><?=$file_detail?></td>
                            <td>
                                <strong>Username:</strong> <?=$user['username']?><br/>
                                <strong>Email:</strong> <?=$user['email']?><br/>
                                <strong>Mobile:</strong> <?=$user['mobile']?><br/>
                                <?php if ($user['blocked'] == "0") { ?>
                                    <a href="block_user.php?id=<?=$user['id']?>" class="btn btn-custom btn-info-custom mb-1">Block</a>
                                    <a href="remove_user.php?id=<?=$user['id']?>" class="btn btn-custom btn-danger-custom mb-1">Remove</a>
                                <?php } else if ($user["blocked"] == "1") { ?>
                                    <a href="unblock_user.php?id=<?=$user['id']?>" class="btn btn-custom btn-warning-custom mb-1">Unblock</a>
                                <?php } ?>
                            </td>
                        </tr>         
                        <?php } } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container my-3">
        <button class="btn btn-light" onclick="window.history.back();">Back</button>
    </div>
</body>
</html>
