<?php 
require_once("../server/connect.php"); 
include_once("../session.php"); 
require_once("hasAccessUser.php"); 
include_once("library.php"); 
error_reporting(E_ALL); 
ini_set('display_errors', 1); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
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
        .table thead th {
            background-color: #0056b3;
            color: white;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .back-button {
            margin-bottom: 15px;
        }
        .search-form {
            display: flex;
            justify-content: flex-end;
        }
        .search-input {
            margin-right: 10px;
        }
        .text-danger {
            color: red;
        }
    </style>
</head>
<body class="dashboard_background">
    <?php include_once("menubar.php"); ?>
    <div class="container my-5">
        <div class="card card-custom">
            <div class="card-body">
                <div class="back-button">
                    <button class="btn btn-light" style="color: black; border: 1px solid black;" onclick="window.history.back();">Back</button>
                </div>
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Transactions</h4>
                    <form method="GET" action="" class="search-form">
                        <input type="text" name="search" class="form-control search-input" placeholder="Search...">
                        <button type="submit" class="btn btn-light" style="color: black; border: 1px solid black;">Search</button>
                    </form>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Serial No.</th>
                            <th>From (User)</th>
                            <th>For User</th>
                            <th>Request By</th> \
                            <th>Subject</th>
                            <th>Date & Time of Transfer</th>
                            <th>Date & Time of Key Request</th>
                            <th>Date & Time of Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $searchQuery = "";
                        if (isset($_GET['search'])) {
                            $search = mysqli_real_escape_string($conn, $_GET['search']);
                            $searchQuery = " WHERE u1.username LIKE '%$search%' OR u2.username LIKE '%$search%' OR u3.username LIKE '%$search%' ";
                        }

                        $sql = "
                        SELECT 
                            df.id AS file_id,
                            u1.username AS sender,
                            u2.username AS receiver,
                            u3.username AS request_by_user,  /* New: Fetch the requesting user's name */
                            df.subject AS subject,
                            df.time_of_transfer AS transfer_date,
                            kr.time_of_request AS key_request_date,
                            CASE
                                WHEN kr.status = 'rejected' THEN 'Rejected'
                                ELSE df.time_of_download
                            END AS download_date
                        FROM data_files df
                        JOIN users u1 ON df.sender_id = u1.id
                        JOIN users u2 ON df.receiver_id = u2.id
                        LEFT JOIN key_requests kr ON df.id = kr.file
                        LEFT JOIN users u3 ON kr.request_by_user = u3.id  
                        $searchQuery
                        ORDER BY df.time_of_transfer DESC
                        ";
                        
                        $result = mysqli_query($conn, $sql);

                        if (!$result) {
                            die("Database query failed: " . mysqli_error($conn));
                        }

                        $serial_no = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $downloadDate = $row['download_date'] === 'Rejected' 
                                    ? "<span class='text-danger'>Rejected</span>" 
                                    : (!empty($row['download_date']) ? date('Y-m-d H:i:s', strtotime($row['download_date'])) : 'Pending');
                                
                                echo "<tr>
                                    <td>{$serial_no}</td>
                                    <td>" . (!empty($row['sender']) ? ucfirst($row['sender']) : 'Pending') . "</td>
                                    <td>" . (!empty($row['receiver']) ? ucfirst($row['receiver']) : 'Pending') . "</td>
                                    <td>" . (!empty($row['request_by_user']) ? ucfirst($row['request_by_user']) : 'Pending') . "</td> <!-- Handle null values -->
                                    <td>" . (!empty($row['subject']) ? htmlspecialchars($row['subject']) : 'Pending') . "</td>
                                    <td>" . (!empty($row['transfer_date']) ? date('Y-m-d H:i:s', strtotime($row['transfer_date'])) : 'Pending') . "</td>
                                    <td>" . (!empty($row['key_request_date']) ? date('Y-m-d H:i:s', strtotime($row['key_request_date'])) : 'Pending') . "</td>
                                    <td>{$downloadDate}</td>
                                </tr>";
                                $serial_no++;
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No transactions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
