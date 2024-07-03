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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .book-rating {
            font-size: 16px;
            color: #FFD700; /* Golden color for stars */
        }
    </style>
</head>
<body>
<!-- Navbar -->
<?php include 'admin_navbar.php'; ?>

<br>
<div>
    <h1 class="text-center">Upload Books</h1>
</div>

<div class="container col-12 m-5">
    <div class="col-6 m-auto">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_img'])) {
            $con = mysqli_connect("localhost", "root", "", "pdfupload");
            $filename = $_FILES["choosefile"]["name"];
            $tempfile = $_FILES["choosefile"]["tmp_name"];
            $folder = "pdf/" . $filename;
            $bookCover = $_FILES["bookcover"]["name"]; // Add book cover field
            $bookCoverTemp = $_FILES["bookcover"]["tmp_name"]; // Temporary file for book cover
            $bookName = $_POST["bookname"]; // Add book name field
            $authorName = $_POST["authorname"]; // Add author name field
            $publishedDate = date('Y-m-d'); // Add published date field
            $category_id = $_POST["category_id"]; // Corrected variable name to category_id
            $subcat_id = $_POST['subcat_id'];

            // Check if the uploaded file has a PDF extension
            $fileExtension = !empty($filename) ? strtolower(pathinfo($filename, PATHINFO_EXTENSION)) : '';
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
                    // Get the last ID from the database
                    $lastIdQuery = "SELECT MAX(id) AS max_id FROM images";
                    $result = mysqli_query($con, $lastIdQuery);
                    $row = mysqli_fetch_assoc($result);
                    $lastId = $row['max_id'];

                    // Increment the ID
                    $newId = $lastId + 1;

                    // Insert the information into the database
                    $sql = "INSERT INTO images (id, pdf, book_cover, book_name, author_name, published_date, cat_id, subcat_id)
                         VALUES ('$newId', '$filename', '$bookCover', '$bookName', '$authorName', '$publishedDate', '$category_id', '$subcat_id')";
                    if ($filename == "") {
                        echo "<div class='alert alert-danger' role='alert'>
                            <h4 class='text-center'>Blank Not Allowed</h4>
                            </div>";
                    } else {
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            // Move the uploaded files to their respective folders
                            move_uploaded_file($tempfile, $folder);
                            move_uploaded_file($bookCoverTemp, "book_covers/" . $bookCover);
                            echo
                            "<div class='alert alert-success' role='alert'>
                                <h4 class='text-center'>PDF uploaded</h4>
                            </div>";
                        } else {
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
                <!-- Input for book cover -->
            <label for="bookcover" class="form-label">Choose Image</label>
            <input type="file" class="form-control mb-3" id="bookcover" name="bookcover" accept="image/*">
            <!-- Input for PDF file -->
            <label for="choosefile" class="form-label">Choose PDF</label>
            <input type="file" class="form-control mb-3" id="choosefile" name="choosefile">
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

<!-- Table to display filtered Books -->
<div class="container col-12 m-5">
    <div class="col-12 m-auto">
        <h2 class="text-center">List of Books</h2>
        <table class="table text-center">
            <thead>
                <tr>
                    <th>Book Cover</th>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Uploaded Date</th>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Action</th>
                    <th>Average Rating</th> <!-- New column for Average Rating -->
                </tr>
            </thead>
            <tbody>
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
                    // Calculate average rating for the current book
                    $book_id = $fetch['id'];
                    $average_rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE book_id = $book_id";
                    $average_rating_result = mysqli_query($conn, $average_rating_sql);
                    $average_rating = mysqli_fetch_assoc($average_rating_result)['avg_rating'];
                ?>
                <tr>
                    <td><img src="book_covers/<?php echo $fetch['book_cover'] ?>" alt="Book Cover" style="max-width: 100px;"></td>
                    <td><?php echo $fetch['book_name'] ?></td>
                    <td><?php echo $fetch['author_name'] ?></td>
                    <td><?php echo $fetch['published_date'] ?></td>
                    <td><?php echo $fetch['cat_name'] ?></td>
                    <td><?php echo $fetch['subcat_name'] ?></td>
                    <td>
                        <a href="pdf/<?php echo $fetch['pdf'] ?>" target="_blank" class="btn btn-outline-primary">View</a>
                        <a href="delete.php?id=<?php echo $fetch['id'] ?>" class="btn btn-outline-danger">Delete</a>
                        <a href="edit_book.php?id=<?php echo $fetch['id'] ?>" class="btn btn-outline-secondary">Edit</a> <!-- Added Edit Button -->
                    </td>
                    <td>
                        <!-- Display average rating as stars -->
                        <div class="book-rating">
                            <?php
                            if ($average_rating !== null) {
                                $average_rating = round($average_rating, 1);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $average_rating) ? '★' : '☆';
                                }
                                echo " ($average_rating)";
                            } else {
                                echo "No ratings yet";
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    // Function to fetch and populate subcategories based on selected category
    function fetchSubcategories(categoryId) {
        $.ajax({
            url: 'fetch_subcategories.php',
            type: 'POST',
            data: { category_id: categoryId },
            success: function(response) {
                $('#subcategory_id').html(response);
            }
        });
    }

    // Fetch subcategories when a category is selected
    $('#category_id').change(function() {
        var categoryId = $(this).val();
        fetchSubcategories(categoryId);
    });

    // Initial fetch for the selected category if it exists
    var initialCategoryId = $('#category_id').val();
    if (initialCategoryId) {
        fetchSubcategories(initialCategoryId);
    }
});
</script>

</body>
</html>
