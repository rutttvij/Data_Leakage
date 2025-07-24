<?php require_once("../server/connect.php"); ?>
<?php include_once("../session.php"); ?>
<?php include_once("../sanitize.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include_once("bootstrap.php"); ?>
    <style>
        body {
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .login_background {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-body {
            padding: 30px;
        }
        .card-title {
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #343a40;
        }
        .form-control {
            border-radius: 5px;
            border-color: #ced4da;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert {
            margin-bottom: 20px;
        }
        .text-center a {
            color: #007bff;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="login_background">

    <div class="container">
        <div class="row my-5">
            <div class="col-sm-6 col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Login</h2>
                        <?php include_once("success_message.php"); ?>						
                        <?php 
                        if(isset($_POST['login'])){
                            $email = sanitize($_POST['email']);
                            $password = sanitize($_POST['password']);
                            $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
                            if(mysqli_num_rows($result) > 0){
                                $rows = mysqli_fetch_array($result);
                                if ($rows['logged_in'] == '1') {
                                    echo "<div class='alert alert-warning'>You are already logged in on another device.</div>";
                                } else {	
                                    if($rows['admin_active'] == "1"){
                                        $_SESSION['user_id'] = $rows['id'];
                                        $_SESSION['username'] = $rows['username'];
                                        $_SESSION['profile'] = $rows['profile'];
                                        $_SESSION['user_type'] = $rows['user_type'];
                                        $_SESSION['is_login'] = "loginned";
                                        $user_id = $rows['id'];
                                        $update_query = "UPDATE users SET logged_in='1' WHERE id='$user_id'";
                                        mysqli_query($conn, $update_query);
                                        $url = "dashboard.php";
                                        $_SESSION['success_message'] = "You are successfully logged in.";
                                        header("Location:$url");
                                        exit();				
                                    } else {
                                        echo "<div class='alert alert-warning'>Failed to login: Your account is not approved by admin.</div>";
                                    }
                                }			
                            } else {
                                echo "<div class='alert alert-danger'>Invalid email id or password.</div>";		
                            }
                        }
                        ?>
                        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email id</label>
                                <input type="text" name="email" class="form-control" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3 text-center">
                                <input type="submit" name="login" value="Login" class="btn btn-primary">
                            </div>
                            <div class="mb-3 text-center">
                                <a href="register.php" class="text-decoration-none">Does not have an account?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
