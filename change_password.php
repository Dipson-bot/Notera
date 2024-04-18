<?php
session_start();

if (isset($_POST['update'])) {
    // Reuse the database connection from signup.php
    $connection = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($connection, "lms");

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
                $_SESSION['password_updated'] = true;
                ?>
                <script type="text/javascript">
                    alert("Password updated successfully...");
                    window.location.href = "user_dashboard.php";
                </script>
                <?php
                exit();
            } else {
                $_SESSION['password_update_failed'] = true;
            }
        } else {
            $_SESSION['incorrect_password'] = true;
        }
    } else {
        $_SESSION['user_not_found'] = true;
    }
}

//HandlNotera search
$connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
$search_query = "";
if (isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];
    
    $sql = "SELECT images.*, category.cat_name
            FROM images
            LEFT JOIN category ON images.cat_id = category.cat_id
            WHERE images.book_name LIKE '%$search_query%'
            OR category.cat_name LIKE '%$search_query%'
            OR images.author_name LIKE '%$search_query%'
            ORDER BY images.date_added DESC";
} else {

    $sql = "SELECT images.*, category.cat_name
            FROM images
            LEFT JOIN category ON images.cat_id = category.cat_id
            ORDER BY images.date_added DESC";
}

$result = mysqli_query($connection, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
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
<nav class="navbar navbar-expand lg navbar-dark bg-dark">
            <div class="container-fluid">
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <a class="navbar-brand" href="user_dashboard.php">Notera</a>
                        <?php
                            // Check if the user is an admin
                            if ($_SESSION['role'] === 'admin') {
                                echo '<li class="nav-item">';
                                echo '<a class="nav-link active" href="admin/admin_dashboard.php">Admin</a>';
                                echo '</li>';
                            }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="listofBooks.php">List of Books</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link active" href="contact.php">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="aboutus.php">About Us</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">My Profile </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="downloads.php">Downloads</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="uploadpdf.php">Upload Books</a></li>
                            </ul>
                        </li>

                        
                        <li class="nav-item">
                            <a class="nav-link" role="button" href="logout.php">Logout</a>
                        </li>
                    </ul>
                    

                    <!-- Search bar -->
                    <form class="d-flex" action="listofBooks.php" method="post">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query" value="<?php echo $search_query; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
                </div>
            </div>
        </nav>

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
            <?php
            if (isset($_SESSION['password_update_failed']) && $_SESSION['password_update_failed']) {
                echo '<div class="alert alert-danger mt-3" role="alert">Password update failed. Please try again.</div>';
                unset($_SESSION['password_update_failed']);
            } elseif (isset($_SESSION['incorrect_password']) && $_SESSION['incorrect_password']) {
                echo '<div class="alert alert-danger mt-3" role="alert">Incorrect current password. Please try again.</div>';
                unset($_SESSION['incorrect_password']);
            } elseif (isset($_SESSION['user_not_found']) && $_SESSION['user_not_found']) {
                echo '<div class="alert alert-danger mt-3" role="alert">User not found. Please log in again.</div>';
                unset($_SESSION['user_not_found']);
            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
