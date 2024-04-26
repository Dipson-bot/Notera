<?php
    require("functions.php");
    session_start();
		// Check if the user is not logged in, redirect to index.php
	if (!isset($_SESSION['id'])) {
		header("Location: ../index.php");
		exit();
	}

    // Connecting to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "contactus";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
	
    // Fetch contact form submissions from the database
    $query = "SELECT * FROM `contactus`";
    $result = mysqli_query($conn, $query);


	// Database connection for the new 'pdfupload' database
		$pdfUploadServername = "localhost";
		$pdfUploadUsername = "root";
		$pdfUploadPassword = "";
		$pdfUploadDatabase = "pdfupload";

		$pdfUploadConn = mysqli_connect($pdfUploadServername, $pdfUploadUsername, $pdfUploadPassword, $pdfUploadDatabase);

if (!$pdfUploadConn) {
    die("Connection to pdfupload database failed: " . mysqli_connect_error());
}

$pdfUploadQuery = "SELECT pb.*, c.cat_name as category_name FROM `pending_Books` pb 
LEFT JOIN `category` c ON pb.cat_id = c.cat_id";




$pdfUploadResult = mysqli_query($pdfUploadConn, $pdfUploadQuery);

if (!$pdfUploadResult) {
    die("Error executing query: " . mysqli_error($pdfUploadConn));
}

		// $pdfUploadQuery = "SELECT * FROM `pending_Books`";
		// $pdfUploadResult = mysqli_query($pdfUploadConn, $pdfUploadQuery);

		// Count the number of pending Books
		$countPendingBooksQuery = "SELECT COUNT(*) as total FROM `pending_Books`";
		$countPendingBooksResult = mysqli_query($pdfUploadConn, $countPendingBooksQuery);
		$countPendingBooks = mysqli_fetch_assoc($countPendingBooksResult)['total'];


        // Database connection for the 'lms' database
        // $lmsServername = "localhost";
        // $lmsUsername = "root";
        // $lmsPassword = "";
        // $lmsDatabase = "lms";

        // $lmsConn = mysqli_connect($lmsServername, $lmsUsername, $lmsPassword, $lmsDatabase);

        // if (!$lmsConn) {
        //     die("Connection to lms database failed: " . mysqli_connect_error());
        // }

	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>   
	
		

	
	<style>
        .card {
            margin-bottom: 20px; /* Add margin-bottom to create space between cards */
        }
		/* Custom style to increase the modal width */
		.modal-xl {
		    max-width: 95vw; /* Set the maximum width of the modal */
		}
		

		/* Optional: Adjust the modal's height if needed */
		.modal-xl .modal-content {
		    height: 80vh; /* Set the height of the modal content */
		    overflow-y: auto; /* Enable vertical scrolling if the content exceeds the modal height */
		}
		
    </style>
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

	<div class="container mt-3">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">Registered User</div>
                    <div class="card-body">
                        <p class="card-text">No. total Users: <?php echo get_user_count();?></p>
                        <a class="btn btn-danger" href="Regusers.php">View Registered Users</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Total Book</div>
                    <div class="card-body">
                        <p class="card-text">No of Books available: <?php echo get_book_count();?></p>
                        <a class="btn btn-success" href="RegBooks.php">View All Books</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Book Categories</div>
                    <div class="card-body">
                        <p class="card-text">No of Book's Categories: <?php echo get_category_count();?></p>
                        <a class="btn btn-warning" href="Regcat.php">View Categories</a>
                    </div>
                </div>
            </div>
			<div class="col">
                <div class="card">
                    <div class="card-header">Pending Books</div>
                    <div class="card-body">
                        <p class="card-text">No. of Books pending: <?php echo $countPendingBooks; ?></p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pendingBooksModal">View Pending Books</button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Contact Form Submissions</div>
                    <div class="card-body">
                        <p class="card-text">No. of Submissions: <?php echo mysqli_num_rows($result);?></p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#contactModal">
                            View Submissions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Modal to display contact form submissions -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Contact Form Submissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Sno</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Concern</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Actions</th> <!-- New column for delete button -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Loop through the results and display them in the table
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['sno'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['concern'] . "</td>";
                                echo "<td>" . $row['dt'] . "</td>";
                                echo "<td><button class='btn btn-danger' onclick='deleteContact(" . $row['sno'] . ")'>Delete</button></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal to display pending Books -->
<div class="modal fade" id="pendingBooksModal" tabindex="-1" aria-labelledby="pendingBooksModalLabel"
        aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pendingBooksModalLabel">Pending Books</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Book Cover</th>
                            <th scope="col">Book Name</th>
                            <th scope="col">Author Name</th>
                            <th scope="col">Uploaded Date</th>
                            <th scope="col">Category</th>
                            <th scope="col">User Name</th>
                            <th scope="col">View PDF</th> 
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($fetch = mysqli_fetch_assoc($pdfUploadResult)) {
                           // Inside the loop fetching pending Books
                            $userId = $fetch['user_id'];
                            $userQuery = "SELECT name FROM lms.users WHERE id = '$userId'";
                            $userResult = mysqli_query($pdfUploadConn, $userQuery);
                            $userName = mysqli_fetch_assoc($userResult)['name'];
                            // if ($userResult) {
                            //     // Check if the query returned any rows
                            //     if (mysqli_num_rows($userResult) > 0) {
                            //         $userName = mysqli_fetch_assoc($userResult)['name'];
                            //     } else {
                            //         $userName = "Unknown User"; // Set a default value or handle it as per your requirement
                            //     }
                            // } else {
                            //     // Handle the case when the query fails
                            //     $userName = "Unknown User";
                            // }

                        ?>
                        <tr>
                            <td><img src="./book_covers/<?php echo $fetch['book_cover'] ?>" alt="Book Cover"
                                    style="max-width: 100px;"></td>
                            <td><?php echo $fetch['book_name'] ?></td>
                            <td><?php echo $fetch['author_name'] ?></td>
                            <td><?php echo $fetch['published_date'] ?></td>
                            <td><?php echo $fetch['category_name'] ?></td>
                            <td><?php echo $userName ?></td>
                            <td>
                                <a href="./pdf/<?php echo $fetch['pdf']; ?>" target="_blank"
                                    class="btn btn-primary">View PDF</a>
                            </td>
                            <td>
                                <div style="margin-bottom: 10px;">
                                    <form action="approve_book.php" method="post">
                                        <input type="hidden" name="book_id" value="<?php echo $fetch['id'] ?>">
                                        <button type="submit" name="btn_approve" class="btn btn-success">Approve</button>
                                    </form>
                                </div>
                                <form action="reject_book.php" method="post">
                                    <input type="hidden" name="book_id" value="<?php echo $fetch['id'] ?>">
                                    <button type="submit" name="btn_reject" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    function deleteContact(sno) {
        var confirmation = confirm("Are you sure you want to delete this contact?");
        if (confirmation) {
            $.ajax({
                type: 'POST',
                url: 'delete_contact.php',
                data: {
                    sno: sno
                },
                success: function (response) {
                    location.reload();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
    }
</script>

	

</body>

</html>
