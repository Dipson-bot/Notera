<?php
session_start();
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");

if (isset($_POST['update'])) {
    // Verify if the user is logged in
    if (!isset($_SESSION['email'])) {
        ?>
        <script type="text/javascript">
            alert("User not logged in. Please log in again.");
            window.location.href = "index.php";
        </script>
        <?php
        exit(); // Stop script execution
    }

    // Retrieve the current password hash from the database
    $query = "SELECT password FROM users WHERE email = '$_SESSION[email]'";
    $query_run = mysqli_query($connection, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $currentPasswordHash = $row['password'];

        // Verify the old password entered by the user
        if (password_verify($_POST['old_password'], $currentPasswordHash)) {
            // Hash the new password
            $newPasswordHash = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            // Update the password in the database
            $updateQuery = "UPDATE users SET password = '$newPasswordHash' WHERE email = '$_SESSION[email]'";
            $updateQueryRun = mysqli_query($connection, $updateQuery);

            if ($updateQueryRun) {
                ?>
                <script type="text/javascript">
                    alert("Password updated successfully...");
                    window.location.href = "user_dashboard.php";
                </script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    alert("Password update failed. Please try again.");
                    window.location.href = "change_password.php";
                </script>
                <?php
            }
        } else {
            ?>
            <script type="text/javascript">
                alert("Incorrect current password. Please try again.");
                window.location.href = "change_password.php";
            </script>
            <?php
        }
    } else {
        ?>
        <script type="text/javascript">
            alert("User not found. Please log in again.");
            window.location.href = "index.php";
        </script>
        <?php
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h4 class="text-center">Change Password</h4>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="old_password" class="form-label">Enter Current Password:</label>
                    <input type="password" class="form-control" name="old_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Enter New Password:</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
