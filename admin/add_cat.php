
<?php
require("functions.php");
session_start();
		// Check if the user is not logged in, redirect to index.php
		if (!isset($_SESSION['id'])) {
			header("Location: ../index.php");
			exit();
		}
require("db_connection.php"); // Include the database connection file

if (isset($_POST['add_cat'])) {
    $cat_name = $_POST['cat_name'];

    // Use prepared statements to insert data safely
    $query = "INSERT INTO category (cat_name) VALUES (?)";
    $stmt = mysqli_prepare($connection, $query);

    // Bind the parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "s", $cat_name);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: manage_cat.php");
        exit; // Make sure to exit after a header redirect
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
<!-- Rest of your HTML code -->

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Add New Category</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>	
  	<script type="text/javascript">
  		function alertMsg(){
  			alert(Book added successfully...);
  			window.location.href = "admin_dashboard.php";
  		}
  	</script>
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
									
					</ul>
				<li>

				<li class="nav-item">
					<a class="nav-link" href="../logout.php">Logout</a>
				</li>
				</ul>
			</div>
		</div>
	</nav><br>
	
	
		<center><h4>Add a new Category</h4><br></center>
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<form action="" method="post">
					<div class="form-group">
						<label for="name">Category Name:</label>
						<input type="text" class="form-control" name="cat_name" required>
					</div>
					<br>
					<button type="submit" name="add_cat" class="btn btn-primary">Add Category</button>
				</form>
			</div>
			<div class="col-md-4"></div>
		</div>
</body>
</html>

<?php
if(isset($_POST['add_cat'])) {
    $connection = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($connection, "pdfupload");

    // Check if the connection was successful
    if ($connection && $db) {
        // Use prepared statements to insert data safely
        $query = "INSERT INTO category (cat_name) VALUES (?)";

        $cat_name = $_POST['cat_name'];

        $stmt = mysqli_prepare($connection, $query);

        // Bind the parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "s", $cat_name);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($connection);

            header("Location: manage_cat.php");
            exit; // Make sure to exit after a header redirect
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "Failed to connect to the database.";
    }
}
?>

