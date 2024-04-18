<?php
session_start();
// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Create a database connection
$connection = mysqli_connect("localhost", "root", "", "pdfupload");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$cat_id = "";
$cat_name = "";

// Check if the category ID is set in the URL
if (isset($_GET['cid'])) {
    $cat_id = $_GET['cid'];

    // Fetch category data
    $query = "SELECT cat_name FROM category WHERE cat_id = ?";
    $stmt = mysqli_prepare($connection, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "i", $cat_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Bind the result variable
        mysqli_stmt_bind_result($stmt, $cat_name);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        die("Query failed: " . mysqli_error($connection));
    }
} else {
    echo "Category ID not provided.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>	
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
                <form class="d-flex ms-auto" action="" method="post">
                    <input class="form-control me-2" type="text" placeholder="Search" aria-label="Search" name="search_query">
                    <button class="btn btn-outline-success" type="submit" name="btn_search">Search</button>
                </form>
			</div>
		</div>
    	</nav><br>

    <center><h4>Edit Category</h4><br></center>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form action="" method="post">
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input type="text" class="form-control" name="cat_name" value="<?php echo $cat_name; ?>" required>
                </div><br>
                <button type="submit" name="update_cat" class="btn btn-primary">Update Category</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</body>
</html>

<?php
if (isset($_POST['update_cat'])) {
    $new_cat_name = $_POST['cat_name'];

    // Update category in the database using prepared statement
    $query = "UPDATE category SET cat_name = ? WHERE cat_id = ?";
    $stmt = mysqli_prepare($connection, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "si", $new_cat_name, $cat_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        header("location: manage_cat.php");
    } else {
        echo "Category update failed: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($connection);
?>
</body>
</html>
