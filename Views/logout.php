<?php 
require_once("../session.php");
require_once("../server/connect.php"); 
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $update_query = "UPDATE users SET logged_in='0' WHERE id='$user_id'";
    mysqli_query($conn, $update_query);
}
session_unset();
session_destroy();
$url = "../index.php";
header("Location: $url");
exit();
?>