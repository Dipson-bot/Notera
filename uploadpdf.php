<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);



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
<!-- Rest of your HTML code -->


<!-- Form submission and other HTML content -->
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include Bootstrap CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
    <title>Upload Books</title>
    
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

    <div class="container mt-5">
        <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_img'])) {
                $con = mysqli_connect("localhost", "root", "", "pdfupload");
                $filename = $_FILES["choosefile"]["name"];
                $tempfile = $_FILES["choosefile"]["tmp_name"];
                $folder = "admin/pdf/" . $filename;
                $bookCover = $_FILES["bookcover"]["name"];
                $bookCoverTemp = $_FILES["bookcover"]["tmp_name"];
                $bookName = $_POST["bookname"];
                $authorName = $_POST["authorname"];
                $publishedDate = date('Y-m-d');
                $user_id = $_SESSION['id'];
                $category_id = $_POST["category_id"]; // Corrected variable name to category_id
                $subcat_id = $_POST['subcat_id'];
                
                // $category_name = isset($_POST["category_name"]) ? $_POST["category_name"] : "";
                // $category_name = $_POST["category_name"];
                
                // Check if the uploaded file has a PDF extension
                $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if ($fileExtension !== "pdf") {
                    echo "<div class='alert alert-danger' role='alert'>
                    <h4 class='text-center'>Only PDF files are allowed</h4>
                    </div>";
                } else {
                    // Check if the uploaded file is an image
                    $imageInfo = getimagesize($bookCoverTemp);
                    if ($imageInfo === false) {
                        echo "<div class='alert alert-danger' role='alert'>
                        <h4 class='text-center'>Invalid image format for book cover</h4>
                        </div>";
                    } else {
                        // Fetch the category ID based on the selected category name
                        // $query = "SELECT cat_id FROM category WHERE cat_name = '$category_name'";
                        // $result = mysqli_query($con, $query);
                        // $row = mysqli_fetch_assoc($result);
                        // $category_id = $row['cat_id'];
                        // Get the last ID from the database
                        $lastIdQuery = "SELECT MAX(id) AS max_id FROM pending_Books";
                        $result = mysqli_query($con, $lastIdQuery);
                        if ($result) {
                            // Success
                        } else {
                            // Failure
                            echo "Error: " . mysqli_error($con);
                        }

                        $row = mysqli_fetch_assoc($result);
                        $lastId = $row['max_id'];

                        // Increment the ID
                        $newId = $lastId + 1;
                        $sql = "INSERT INTO pending_Books (id, user_id, pdf, book_cover, book_name, author_name, published_date, cat_id, subcat_id) 
                                VALUES ('$newId', '$user_id', '$filename', '$bookCover', '$bookName', '$authorName', '$publishedDate', '$category_id', '$subcat_id')";
                        if ($filename == "") {
                            echo "<div class='alert alert-danger' role='alert'>
                            <h4 class='text-center'>Blank Not Allowed</h4>
                            </div>";
                        } else {
                            $result = mysqli_query($con, $sql);
                            if ($result) {
                                move_uploaded_file($tempfile, $folder);
                                move_uploaded_file($bookCoverTemp, "admin/book_covers/" . $bookCover);
                                echo "<div class='alert alert-success' role='alert'>
                                <h4 class='text-center'>Note uploaded. Waiting for admin approval.</h4>
                                </div>";
                            } else {
                                echo "Error: " . mysqli_error($con);
                                // echo "<div class='alert alert-danger' role='alert'>
                                // <h4 class='text-center'>Error uploading PDF</h4>
                                // </div>";
                            }
                        }
                    }
                }
            }
         ?>   
        <h2 class="text-center">Upload Books</h2>
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


<!-- Copyright Notice Modal -->
<div class="modal fade" id="copyrightModal" tabindex="-1" aria-labelledby="copyrightModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="text-center w-100"> <!-- Center-align content and make it full width -->
                    <h2 class="modal-title" id="copyrightModalLabel">
                        Copyright Notice
                    </h2>
                    <p>&copy; <?php echo date("Y"); ?> Notera. All rights reserved.</p>
                </div>
            </div>
            <div class="modal-body">
                <!-- Center-align the Terms of Service heading -->
                <h5 class="text-center">Terms of Service</h5>
                <p>This agreement governs your use of our note management system. By using our platform, you agree to the following terms:</p><br>
                <ul>
                    <li>You retain ownership of the notes you upload to the platform.</li><br>
                    <li>You grant us permission to host, display, and distribute your notes on our platform.</li><br>
                    <li>You agree not to upload copyrighted material without proper authorization.</li><br>
                    <li>We respect intellectual property rights and will respond to valid copyright infringement claims.</li><br>
                </ul>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary" id="uploadBtn">Proceed to Upload</button> -->
            </div>
        </div>
    </div>
</div>



    <!-- JavaScript code for copyright policy -->
    <script>
        $(document).ready(function () {
            // Show copyright notice modal on page load
            $('#copyrightModal').modal('show');

            // Handle upload button click in the modal
            $('#uploadBtn').click(function () {
                $('#uploadForm').submit(); // Submit the form when the user clicks Proceed to Upload
            });
        });
    </script>



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
integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
crossorigin="anonymous"></script>
</body>

</html>