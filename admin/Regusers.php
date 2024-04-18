<?php
session_start();


if (!isset($_SESSION['id'])) {
	header("Location: ../index.php");
	exit();
}
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    $updateQuery = "UPDATE users SET role = 'admin' WHERE id = $userId";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        $_SESSION['success_message'] = "User has been converted to admin.";
    } else {
        $_SESSION['error_message'] = "Failed to convert user to admin.";
    }

    header("Location: Regusers.php");
    exit();
}

$queryAdmin = "SELECT * FROM users WHERE role = 'admin'";
$queryUser = "SELECT * FROM users WHERE role = 'user'";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>All Reg Users</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JavaScript -->
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

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <center><h4>Admins</h4></center>
            <table class="table table-bordered" style="text-align: center">
                <!-- Admin table headers -->
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
                <?php
                $query_run = mysqli_query($connection, $queryAdmin);
                while ($row = mysqli_fetch_assoc($query_run)) {
                    $name = $row['name'];
                    $email = $row['email'];
                    $mobile = $row['mobile'];
                    $address = $row['address'];
                    echo "<tr><td>$name</td><td>$email</td><td>$mobile</td><td>$address</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <center><h4>Users</h4></center>
            <table class="table table-bordered" style="text-align: center">
                <!-- User table headers -->
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
                <?php
                $query_run = mysqli_query($connection, $queryUser);
                while ($row = mysqli_fetch_assoc($query_run)) {
                    $userId = $row['id'];
                    $name = $row['name'];
                    $email = $row['email'];
                    $mobile = $row['mobile'];
                    $address = $row['address'];
                    echo "<tr><td>$name</td><td>$email</td><td>$mobile</td><td>$address</td>
                          <td>
                              <form method='post'>
                                  <input type='hidden' name='user_id' value='$userId'>
                                  <button type='submit' class='btn btn-primary'>Convert to Admin</button>
                              </form>
                          </td>
                          </tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<!-- Add this HTML code for the modal at the end of the body tag -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (isset($_SESSION['success_message'])) {
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Add this JavaScript code to handle the modal
    $(document).ready(function(){
        <?php
        if(isset($_SESSION['success_message'])) {
        ?>
            // Show the success modal if there is a success message
            $('#successModal').modal('show');
        <?php
        }
        ?>
    });
</script>

</body>
</html>
