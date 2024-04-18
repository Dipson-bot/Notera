<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "lms");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['new_name'];
    $newMobile = $_POST['new_mobile'];
    $newAddress = $_POST['new_address'];

    // Validation logic
    if (!preg_match("/^[a-zA-Z ]{3,}$/", $newName)) {
            echo '<script type="text/javascript">alert("Name must contain only letters and spaces and it must be at least 3 letters")</script>';
        } elseif (!preg_match("/^[0-9]{10}$/", $newMobile) || empty($newMobile)) {
            echo '<script type="text/javascript">alert("Invalid mobile number. Please provide a 10-digit mobile number.")</script>';
        } else {

    $updateQuery = "UPDATE users SET name = '$newName', mobile = '$newMobile', address = '$newAddress' WHERE email = '$_SESSION[email]'";

        if (mysqli_query($connection, $updateQuery)) {
            // Update successful, show a JavaScript alert and redirect to view_profile.php
            echo '<script type="text/javascript">alert("Changes have been successfully saved."); window.location.href = "view_profile.php";</script>';
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($connection);
        }
    }
}

$name = "";
$email = "";
$mobile = "";
$address = "";

$query = "SELECT * FROM users WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($query_run)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $address = $row['address'];
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
    <title>Edit Profile</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
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

<br><center><h4>Profile Detail</h4><br></center>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form method="post">
            <div class="form-group">
                <label for="new_name">Name:</label>
                <input type="text" class="form-control" name="new_name" value="<?php echo $name; ?>">
            </div>
            <div class="form-group">
                <label for="new_email">Email:</label>
                <input type="text" class="form-control" name="new_email" value="<?php echo $email; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="new_mobile">Mobile:</label>
                <input type="text" class="form-control" name="new_mobile" value="<?php echo $mobile; ?>">
            </div>
            <div class="form-group">
                <label for="new_address">Address:</label>
                <input type="text" class="form-control" name="new_address" value="<?php echo $address; ?>">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
    <div class="col-md-4"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
