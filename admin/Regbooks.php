<?php
	require("functions.php");
	session_start();

    // Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Registered Books</title>
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
	
    <div class="container col-12 m-5">
        <div class="col-12 m-auto">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "pdfupload");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql2 = "SELECT * FROM images";
            $result2 = mysqli_query($conn, $sql2);

            if (!$result2) {
                die("Query failed: " . mysqli_error($conn));
            }
            ?>

            <h2 class="text-center">List of Books</h2>
			<br>

            <!-- Add a table to display the list of Books -->
            <table class="table text-center">
                <tr>
                    
                    <th>Book Cover</th>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Published Date</th>
                    <th>Action</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result2)) {
                    ?>
                    <tr>
                        
                        <td><img src="./book_covers/<?php echo $row['book_cover'] ?>" alt="Book Cover" style="max-width: 100px;"></td>
                        <td><?php echo $row['book_name'] ?></td>
                        <td><?php echo $row['author_name'] ?></td>
                        <td><?php echo $row['published_date'] ?></td>
                        <td>
                            <a href="./pdf/<?php echo $row['pdf'] ?>" target="_blank" class="btn btn-primary">View</a>
                            <a href="./pdf/<?php echo $row['pdf'] ?>" download class="btn btn-success">Download</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
	<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8FqFjcJ6pajs/rfdfs3SO+kXTKZ6/pjxuy0W5XpF72PibFo" crossorigin="anonymous"></script>
</body>
</html>
