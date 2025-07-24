<?php require_once("../server/connect.php"); ?>
<?php include_once("../session.php"); ?>
<?php require_once("library.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <?php include_once("bootstrap.php"); ?>
    <style>
        body {
            background-color: #f8f9fa; 
        }
        .container {
            margin-top: 30px;
        }
        .table {
            margin-bottom: 0; 
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table th {
            background-color: #007bff; 
            color: white;
            text-align: center;
        }
        .table td {
            background-color: #ffffff; 
            color: #495057; 
        }
        .table .btn {
            border-radius: 5px; 
        }
        .btn-success {
            background-color: #28a745; 
            border: none;
        }
        .btn-success:hover {
            background-color: #218838; 
        }
        .btn-danger {
            background-color: #dc3545; 
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333; 
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background-color: #138496; 
        }
        .btn-warning {
            background-color: #ffc107;
            color: black;
            border: none;
        }
        .btn-warning:hover {
            background-color: #e0a800; 
        }
        .btn-light {
            background-color: #f8f9fa;
            color: black;
            border: 1px solid #ced4da;
        }
        .btn-light:hover {
            background-color: #e2e6ea; 
        }
        .row {
            margin-bottom: 20px; 
        }
        .table td {
            padding: 10px; 
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-form {
            display: flex;
        }
        .search-input {
            margin-right: 10px;
        }
    </style>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?>
    <div class="container">
        <div class="row my-5">
            <div class="col-sm-12">
                <div class="top-bar">
                    <button class="btn btn-light" style="color: black; border: 1px solid black;" onclick="window.location.href='dashboard.php';">Back</button>
                    
                    <form method="GET" action="" class="search-form">
                        <input type="text" name="search" class="form-control search-input" placeholder="Search...">
                        <button type="submit" class="btn btn-light" style="color: black; border: 1px solid black;">Search</button>
                    </form>
                </div>
                
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>User Name</th>
                            <th>Email Id</th>
                            <th>Activate Account</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($_POST['activate'])) {
                        $userid = $_POST['userid'];
                        $sql = "UPDATE users SET admin_active='1' WHERE admin_active='0' AND id='$userid' LIMIT 1";
                        mysqli_query($conn, $sql);
                    } else if (isset($_POST['deactivate'])) {
                        $userid = $_POST['userid'];
                        $sql = "UPDATE users SET admin_active='0' WHERE admin_active='1' AND id='$userid' LIMIT 1";
                        mysqli_query($conn, $sql);
                    }

                    $searchQuery = "";
                    if (isset($_GET['search'])) {
                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                        $searchQuery = " AND (username LIKE '%$search%' OR email LIKE '%$search%') ";
                    }

                    $sql = "SELECT * FROM users WHERE user_type='user' $searchQuery ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            $i = 0;
                            while ($rows = mysqli_fetch_array($result)) {
                                $i++;
                                $userid = $rows['id'];
                                $username = $rows['username'];
                                $email = $rows['email'];
                                $admin_active = $rows['admin_active'];
                                $user = getuser($userid);
                    ?>
                        <tr>
                            <td><?=$i?></td>
                            <td>
                                <?=ucfirst($username)?><br/>
                                <strong>Gender:</strong> <?=$rows['gender']?> <br/>
                                <strong>Mobile:</strong> <?=$rows['mobile']?> <br/>
                            </td>
                            <td><?=ucfirst($email)?></td>
                            <td>
                            <?php 
                            if ($admin_active == "0") {
                            ?>
                                <form action='<?=$_SERVER["PHP_SELF"]?>' method='post'>
                                    <input type='hidden' name='userid' value='<?=$userid?>'>
                                    <input type='submit' name='activate' value='Activate' class='btn btn-success mb-2'>
                                </form>
                            <?php
                            } else if ($admin_active == "1") {
                            ?>
                                <form action='<?=$_SERVER["PHP_SELF"]?>' method='post'>
                                    <input type='hidden' name='userid' value='<?=$userid?>'>
                                    <input type='submit' name='deactivate' value='Deactivate' class='btn btn-danger mb-2'>
                                </form>
                            <?php
                            }
                            ?>
                            <?php if ($user['blocked'] == "0") { ?>
                                <a href="block_user.php?id=<?=$user['id']?>" class="btn btn-info mb-1">Block</a>
                                <a href="remove_user.php?id=<?=$user['id']?>" class="btn btn-danger mb-1">Remove</a>
                            <?php } else if ($user["blocked"] == "1") { ?>
                                <a href="unblock_user.php?id=<?=$user['id']?>" class="btn btn-warning mb-1">Unblock</a>
                            <?php } ?>
                            </td>
                        </tr>
                    <?php
                            }
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
