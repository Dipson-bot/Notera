<?php
require("functions.php");
require("db_connection.php"); // Include the database connection file 
session_start();
		// Check if the user is not logged in, redirect to index.php
        if (!isset($_SESSION['id'])) {
            header("Location: ../index.php");
            exit();
        }

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

$connection = mysqli_connect("localhost", "root", "", "lms");

// Check if the database connection was successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$name = "";
$email = "";
$mobile = "";

$query = "SELECT * FROM users WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($connection, $query);

if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}

while ($row = mysqli_fetch_assoc($query_run)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Category</title>
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
			</div>
		</div>
	</nav><br>

    
    <center><h4>Manage Category</h4><br></center>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th style="width: 50%;">Name</th>
                    <th class="text-center" style="width: 50%;">Action</th>
                </tr>
            </thead>
            <?php
            $query = "SELECT * FROM pdfupload.category";
            $query_run = mysqli_query($connection, $query);

            if (!$query_run) {
                die("Query failed: " . mysqli_error($connection));
            }

            while ($row = mysqli_fetch_assoc($query_run)) {
            ?>
                <tr>
                    <!-- <td><?php echo $row['cat_id']; ?></td> -->
                    <td><?php echo $row['cat_name']; ?></td>
                    <td class="text-center">
                        <a href="edit_cat.php?cid=<?php echo $row['cat_id']; ?>" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['cat_id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <div class="col-md-2"></div>
</div>



  <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="deleteCategory()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Functions -->
    <script>
        function confirmDelete(catId) {
            // Set the catId in a data attribute to use it later in the deleteCategory function
            $('#confirmDeleteModal').data('catid', catId);
            // Show the confirmation modal
            $('#confirmDeleteModal').modal('show');
        }

            function deleteCategory() {
        // Get the catId from the data attribute
        var catId = $('#confirmDeleteModal').data('catid');
        // TODO: Perform the deletion action using AJAX or form submission

        // For example, you can use AJAX to delete the category
        $.ajax({
            url: 'delete_cat.php?cid=' + catId, // Update the URL with your backend endpoint
            type: 'GET',
            success: function (response) {
                // Handle the response (e.g., show a success message)
                alert('Category deleted successfully!');
                // Reload the page after deletion
                window.location.reload(); // Reload the page after category is deleted
            },
            error: function (xhr, status, error) {
                // Handle errors if any
                console.error('Error deleting category: ' + error);
            }
        });

        // Close the confirmation modal
        $('#confirmDeleteModal').modal('hide');
     }

        
    </script>
</body>

</html>






