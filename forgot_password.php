<?php
session_start();

if (isset($_POST['reset_request'])) {
    $connection = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($connection, "pdfupload");

    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    // Check if the email and mobile number match a user in your database
    $query = "SELECT * FROM users WHERE email = '$email' AND mobile = '$mobile'";
    $query_run = mysqli_query($connection, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $_SESSION['reset_email'] = $email;
        header("Location: reset_password.php");
        exit();
    } else {
        $_SESSION['reset_error'] = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
        <a class="navbar-brand" href="user_dashboard.php">Notera</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                // Check if the user is an admin
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link active" href="admin/admin_dashboard.php">Admin</a>';
                    echo '</li>';
                }
                ?>
            </ul>
            
        </div>
    </div>
</nav>


<div class="row justify-content-center">
    <div class="col-md-8" id="main_content">
        <center><h3><u>Forgot Password</u></h3></center>
        <?php
        if (isset($_SESSION['reset_error']) && $_SESSION['reset_error']) {
            echo '<div class="alert alert-danger" role="alert">Sorry, we could not process your request. Please check your registered email and mobile number and try again.</div>';
            unset($_SESSION['reset_error']);
        }
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="email">Enter your Email:</label>
                <input type="text" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mobile">Enter your Mobile Number:</label>
                <input type="text" name="mobile" class="form-control" required>
            </div>
            <button type="submit" name="reset_request" class="btn btn-primary">Request Reset</button>
        </form>
    </div>
</div>
</body>
</html>
