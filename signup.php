<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign up</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<style type="text/css">
    #main_content {
        padding: 50px;
        background-color: whitesmoke;
    }

    #side_bar {
        background-color: whitesmoke;
        padding: 50px;
        width: 300px;
        height: 450px;
    }
</style>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Notera Management System</a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item">
                <a class="nav-link" href="admin/index.php">Admin Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="signup.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Login</a>
            </li>
        </ul>
    </div>
</nav><br>

<div class="row justify-content-center"> <!-- Center the content -->
    <div class="col-md-8" id="main_content">
        <center><h3><u>User Registration Form</u></h3></center>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email ID:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password (Minimum 8 characters):</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" name="mobile" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>
            <button type="submit" name="register" class="btn btn-primary">Register</button>
        </form>
        <?php
        if (isset($_POST['register'])) {
            $connection = mysqli_connect("localhost", "root", "");
            $db = mysqli_select_db($connection, "lms");
            $name = $_POST['name'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

            // Check if email or mobile already exist in the database
            $check_email_query = "SELECT * FROM users WHERE email='$email'";
            $check_email_result = mysqli_query($connection, $check_email_query);

            $check_mobile_query = "SELECT * FROM users WHERE mobile='$mobile'";
            $check_mobile_result = mysqli_query($connection, $check_mobile_query);
            if (!preg_match("/^[a-zA-Z ]{3,}$/", $name)) {
                echo '<script type="text/javascript">alert("Enter full name (must contain only letters and spaces) ")</script>';
            }  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<script type="text/javascript">alert("Invalid email format. Please provide a valid email address with both \'@\' and \'.\'.")</script>';
            }  elseif (strlen($_POST['password']) < 8) {
                echo '<script type="text/javascript">alert("Password must be at least 8 characters long.")</script>';
            } elseif (!preg_match("/^[0-9]{10}$/", $mobile) || empty($mobile)) {
                echo '<script type="text/javascript">alert("Invalid mobile number. Please provide a 10-digit mobile number.")</script>';
            } elseif (mysqli_num_rows($check_email_result) > 0) {
                echo '<script type="text/javascript">alert("This email is already registered.")</script>';
            } elseif (mysqli_num_rows($check_mobile_result) > 0) {
                echo '<script type="text/javascript">alert("This mobile number is already registered.")</script>';
            } else {
                $query = "INSERT INTO users (name, email, password, mobile, address) VALUES ('$name', '$email', '$password', '$mobile', '$_POST[address]')";
                $query_run = mysqli_query($connection, $query);
                if ($query_run) {
                    echo '<script type="text/javascript">alert("Registration successful. You can now log in.")</script>';
                } else {
                    echo '<script type="text/javascript">alert("Registration failed: ' . mysqli_error($connection) . '")</script>';
                }
                
            }
        }
        ?>
    </div>
</div>
</body>
</html>
