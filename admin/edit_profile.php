<?php
session_start();
		// Check if the user is not logged in, redirect to index.php
        if (!isset($_SESSION['id'])) {
            header("Location: ../index.php");
            exit();
        }

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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<a class="navbar-brand" href="admin_dashboard.php">Notera</a>
					<li class="nav-item">
						<a class="nav-link active" href="../user_dashboard.php">User View</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">My Profile </a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
							</ul>
					</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">Books</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="add_book.php">Manage Notera</a></li>
									
					</ul>
				<li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">Category</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="add_cat.php">Add New Category</a></li>
									<div class="dropdown-divider"></div>
						<li?><a class="dropdown-item" href="manage_cat.php">Manage Category</a></li>
                                    <div class="dropdown-divider"></div>
                        <li?><a class="dropdown-item" href="add_subcat.php">Add Sub Category</a></li>	
                                    <div class="dropdown-divider"></div>		
                        <li?><a class="dropdown-item" href="manage_subcat.php">Manage Sub Category</a></li>
					</ul>
				<li>

				<li class="nav-item">
					<a class="nav-link" href="../logout.php">Logout</a>
				</li>
				</ul>
			</div>
		</div>
	</nav><br>
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
