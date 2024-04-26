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
<html lang="en">
    <head>
        
        <title>Add Books</title>
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
    <!-- Navbar 1 (Dark Theme) -->
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
        
    <!-- Rest of your HTML code -->
    <br>
    <div>
        <h1 class="text-center">Upload Books</h1>
    </div>
        
    <div class="container col-12 m-5">
        <div class="col-6 m-auto">
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_img']))
            {
                $con = mysqli_connect("localhost","root","","pdfupload");
                $filename = $_FILES["choosefile"]["name"];
                $tempfile = $_FILES["choosefile"]["tmp_name"];
                $folder = "pdf/".$filename;
                $bookCover = $_FILES["bookcover"]["name"]; // Add book cover field
                $bookCoverTemp = $_FILES["bookcover"]["tmp_name"]; // Temporary file for book cover
                $bookName = $_POST["bookname"]; // Add book name field
                $authorName = $_POST["authorname"]; // Add author name field
                $publishedDate = date('Y-m-d'); // Add published date field
                $category_id = $_POST["category_id"]; // Corrected variable name to category_id
                $subcat_id = $_POST['subcat_id'];
            
                // Check if the uploaded file has a PDF extension
                $fileExtension = !empty($filename) ? strtolower(pathinfo($filename, PATHINFO_EXTENSION)) : '';
                if ($fileExtension !== "pdf")
                {
                    echo "<div class='alert alert-danger' role='alert'>
                    <h4 class='text-center'>Only PDF files are allowed</h4>
                    </div>";
                }

                else
                {
                    // Check if the uploaded file is an image
                    $imageInfo = getimagesize($bookCoverTemp);
                    if ($imageInfo === false) {
                        echo "<div class='alert alert-danger' role='alert'>
                        <h4 class='text-center'>Invalid image format for book cover</h4>
                        </div>";
                    }
                    else
                    {
                        // Get the last ID from the database
                        $lastIdQuery = "SELECT MAX(id) AS max_id FROM images";
                        $result = mysqli_query($con, $lastIdQuery);
                        $row = mysqli_fetch_assoc($result);
                        $lastId = $row['max_id'];
                        
                        // Increment the ID
                        $newId = $lastId + 1;
                    
                        // Insert thNotera information into the database
                        $sql = "INSERT INTO images (id, pdf, book_cover, book_name, author_name, published_date, cat_id, subcat_id)
                         VALUES ('$newId', '$filename', '$bookCover', '$bookName', '$authorName', '$publishedDate', '$category_id', '$subcat_id')";
                        if($filename == "")
                        {
                            echo "<div class='alert alert-danger' role='alert'>
                            <h4 class='text-center'>Blank Not Allowed</h4>
                            </div>";
                        }
                        else
                        {
                            $result = mysqli_query($con, $sql);
                            if($result)
                            {
                                // Move the uploaded files to their respective folders
                                move_uploaded_file($tempfile, $folder);
                                move_uploaded_file($bookCoverTemp, "book_covers/".$bookCover);
                                echo 
                                "<div class='alert alert-success' role='alert'>
                                <h4 class='text-center'>PDF uploaded</h4>
                                </div>";
                            }
                            else
                            {
                                echo "<div class='alert alert-danger' role='alert'>
                                <h4 class='text-center'>Error uploading PDF</h4>
                                </div>";
                            }
                        }
                    }
                }
            }
        ?>
        <form action="" method="post" class="form-control" enctype="multipart/form-data">
            <input type="file" class="form-control" name="choosefile" required>
            <input type="file" class="form-control" name="bookcover" accept="image/*" required>
            <input type="text" class="form-control" name="bookname" placeholder="Book Name" required>
            <input type="text" class="form-control" name="authorname" placeholder="Author Name" required>
            <!-- <input type="date" class="form-control" name="pubdate" placeholder="Published Date" required> -->
            <!-- Add a dropdown for category selection -->
            <select class="form-control" name="category_id" id="category_id" required>
                <option value="">Select Semester</option>
                    <?php
                    // Retrieve category names and IDs from the database
                    $conn = mysqli_connect("localhost", "root", "", "pdfupload");
                    $sql = "SELECT cat_id, cat_name FROM category";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['cat_id'] . "'>" . $row['cat_name'] . "</option>";
                    }
                    ?>
            </select>
                 <!-- Add a dropdown for subcategory selection -->
             <select class="form-control" name="subcat_id" id="subcategory_id" required>
                 <option value="" data-category="">Select Subject</option>
                 <!-- Subcategory options will be populated dynamically using JavaScript -->
             </select>
            <div class="col-6 m-auto">
                <button type="submit" name="btn_img" class="btn btn-outline-success m-4">
                    SUBMIT
                </button>
            </div>
        </form><br>
        </div>
    </div>
            
        <!-- Rest of your HTML code -->
            
            <!-- Table to display filtered Books -->
<div class="container col-12 m-5">
    <div class="col-12 m-auto">
        <h2 class="text-center">List of Books</h2>
        <table class="table text-center">
            <tr>
                <!-- <th>ID</th> -->
                <th>Book Cover</th>
                <th>Book Name</th>
                <th>Author Name</th>
                <th>Uploaded Date</th>
                <th>Semester</th>
                <th>Subject</th>
                <th>Action</th>
            </tr>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "pdfupload");

            // Check if the search form is submitted
            if (isset($_POST['btn_search'])) {
                $searchQuery = $_POST['search_query'];

                // Create an SQL query to filter Books based on the search query for book name or category
                $sql2 = "SELECT i.id, i.pdf, i.book_cover, i.book_name, i.author_name, i.published_date, c.cat_name, s.subcat_name 
                        FROM images i 
                        INNER JOIN category c ON i.cat_id = c.cat_id 
                        LEFT JOIN subcategory s ON i.subcat_id = s.subcat_id 
                        WHERE i.book_name LIKE '%$searchQuery%' OR c.cat_name LIKE '%$searchQuery%' OR s.subcat_name LIKE '%$searchQuery%'";
                $result2 = mysqli_query($conn, $sql2);

                if (!$result2) {
                    die("Query failed: " . mysqli_error($conn));
                }
            } else {
                // Default query to display all Books
                $sql2 = "SELECT i.id, i.pdf, i.book_cover, i.book_name, i.author_name, i.published_date, c.cat_name, s.subcat_name 
                        FROM images i 
                        INNER JOIN category c ON i.cat_id = c.cat_id 
                        LEFT JOIN subcategory s ON i.subcat_id = s.subcat_id";
                $result2 = mysqli_query($conn, $sql2);
                if (!$result2) {
                    die("Query failed: " . mysqli_error($conn));
                }
            }

            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <tr>

                    <td><img src="./book_covers/<?php echo $fetch['book_cover'] ?>" alt="Book Cover" style="max-width: 100px;"></td>
                    <td><?php echo $fetch['book_name'] ?></td>
                    <td><?php echo $fetch['author_name'] ?></td>
                    <td><?php echo $fetch['published_date'] ?></td>
                    <td><?php echo $fetch['cat_name'] ?></td>
                    <td><?php echo $fetch['subcat_name'] ?></td> <!-- Subcategory -->
                    <td>
                        <a href="./pdf/<?php echo $fetch['pdf'] ?>" target="_blank" class="btn btn-outline-primary">View</a>
                        <form action="delete.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $fetch['id'] ?>">
                            <button type="submit" name="btn_delete" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>


            <!-- JavaScript code to populate subcategories based on selected category -->
            <script>
        document.getElementById('category_id').addEventListener('change', function () {
            var categoryId = this.value;
            var subcategoryDropdown = document.getElementById('subcategory_id');

            // Clear existing options
            subcategoryDropdown.innerHTML = '<option value="" data-category="">Select Subject</option>';

            // Fetch subcategories based on selected category
            if (categoryId !== '') {
                fetch('fetch_subcategories.php?category_id=' + categoryId)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subcategory => {
                            var option = document.createElement('option');
                            option.value = subcategory.subcat_id;
                            option.setAttribute('data-category', categoryId);
                            option.textContent = subcategory.subcat_name;
                            subcategoryDropdown.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    </script>
                
    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-Mk538Md5axz0zJ2n5n/4wtl3l5St5fO5fR5u5BvF5t5z5r5Qfa9aF1a1x1" crossorigin="anonymous"></script>
    </body>
</html>
