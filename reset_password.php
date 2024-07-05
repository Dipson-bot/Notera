<?php
session_start();

if (isset($_POST['reset_password'])) {
    // Reuse the database connection from your previous code
    $connection = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($connection, "pdfupload");

    $newPassword = $_POST['new_password'];
    $email = $_SESSION['reset_email'];

    // Validate new password length
    if (strlen($newPassword) < 8) {
        $_SESSION['reset_error'] = "Password must be at least 8 characters long.";
    } else {
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

        // Retrieve the user's current password hash
        $query = "SELECT password FROM users WHERE email = '$email'";
        $query_run = mysqli_query($connection, $query);
        $result = mysqli_fetch_assoc($query_run);
        $currentPasswordHash = $result['password'];

        // Check if the new password matches the current password
        if (password_verify($newPassword, $currentPasswordHash)) {
            $_SESSION['reset_error'] = "New password cannot be the same as the current password.";
        } else {
            // Update the user's password in the database
            $query = "UPDATE users SET password = '$newPasswordHash' WHERE email = '$email'";
            $query_run = mysqli_query($connection, $query);

            if ($query_run) {
                // Password reset successful, redirect to a success page
                header("Location: reset_success.php");
                exit();
            } else {
                // Password reset failed, handle accordingly
                $_SESSION['reset_error'] = "Sorry, we could not reset your password. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<style type="text/css">
    /* Add your custom styles here */
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
    <div class="col-md-8">
        <center><h3><u>Reset Password</u></h3></center>
        <?php
        if (isset($_SESSION['reset_error'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['reset_error'] . '</div>';
            unset($_SESSION['reset_error']);
        }
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="new_password">Enter New Password:</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <button type="submit" name="reset_password" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
