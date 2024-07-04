<?php
require("functions.php");
session_start();

// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Database connection for PDF uploads
$conn = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle search form submission
if (isset($_POST['btn_search'])) {
    $searchQuery = $_POST['search_query'];

    // SQL query to filter books based on search query
    $sql = "SELECT images.*, lms_users.name AS uploaded_by_name, lms_users.id AS uploaded_by_id, category.cat_name, subcategory.subcat_name, AVG(reviews.rating) AS avg_rating
            FROM images
            LEFT JOIN lms.users AS lms_users ON images.uploaded_by = lms_users.id
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            LEFT JOIN reviews ON images.id = reviews.book_id
            WHERE images.book_name LIKE '%$searchQuery%'
            OR category.cat_name LIKE '%$searchQuery%'
            OR subcategory.subcat_name LIKE '%$searchQuery%'
            OR images.author_name LIKE '%$searchQuery%'
            OR lms_users.name LIKE '%$searchQuery%'
            GROUP BY images.id
            ORDER BY avg_rating DESC, images.date_added DESC";
} else {
    // Default SQL query to display all books
    $sql = "SELECT images.*, lms_users.name AS uploaded_by_name, lms_users.id AS uploaded_by_id, category.cat_name, subcategory.subcat_name, AVG(reviews.rating) AS avg_rating
            FROM images
            LEFT JOIN lms.users AS lms_users ON images.uploaded_by = lms_users.id
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            LEFT JOIN reviews ON images.id = reviews.book_id
            GROUP BY images.id
            ORDER BY avg_rating DESC, images.date_added DESC";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
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

        /* Custom CSS for enhanced appearance */
        .book-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            position: relative; /* For positioning absolute buttons */
        }

        .book-cover {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .book-details {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .book-actions {
            position: absolute;
            top: 15px; /* Adjust as needed */
            right: 15px; /* Adjust as needed */
            display: flex;
            flex-direction: column; /* Arrange buttons vertically */
        }

        .book-actions .btn {
            margin-bottom: 5px; /* Adjust button spacing */
            width: 100%; /* Make buttons full width of parent */
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
            // Handle book upload logic (existing code)
            // ...
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
                $sqlCategories = "SELECT cat_id, cat_name FROM category";
                $resultCategories = mysqli_query($conn, $sqlCategories);
                while ($row = mysqli_fetch_assoc($resultCategories)) {
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
        <!-- Search Form -->
        <div class="container col-6 m-auto">
            <form action="" method="post" class="form-control mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by Book Name, Category, Author, or Uploader" name="search_query">
                    <button class="btn btn-outline-secondary" type="submit" name="btn_search">Search</button>
                </div>
            </form>
        </div>

        <?php
        while ($fetch = mysqli_fetch_assoc($result)) {
            // Calculate average rating for the current book
            $book_id = $fetch['id'];
            $average_rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE book_id = $book_id";
            $average_rating_result = mysqli_query($conn, $average_rating_sql);
            $average_rating_row = mysqli_fetch_assoc($average_rating_result);
            $average_rating = $average_rating_row['avg_rating'];

            // Check if average rating is null (no reviews yet)
            if ($average_rating === null) {
                $average_rating = 0; // Default to 0 if no reviews
            }
        ?>
        <div class="book-card">
            <div class="row">
                <div class="col-md-2">
                    <img src="book_covers/<?php echo $fetch['book_cover'] ?>" alt="Book Cover" class="book-cover">
                </div>
                <div class="col-md-8">
                    <div class="book-details">
                        <span><?php echo $fetch['book_name'] ?></span><br>
                        <span>Category: <?php echo $fetch['cat_name'] ?></span><br>
                        <span>Subject: <?php echo $fetch['subcat_name'] ?></span><br>
                        <span>Author: <?php echo $fetch['author_name'] ?></span><br>
                        <span>Uploaded By: <?php echo $fetch['uploaded_by_name'] ?></span><br>
                    </div>
                    <div class="book-rating">
                        <?php
                        // Display star ratings based on average rating
                        $stars = str_repeat('★', floor($average_rating));
                        if ($average_rating - floor($average_rating) > 0) {
                            $stars .= '☆';
                        }
                        echo $stars . " (" . number_format($average_rating, 2) . ")";
                        ?>
                    </div>
                </div>
                <div class="col-md-2 book-actions">
                    <a href="pdf/<?php echo $fetch['pdf'] ?>" class="btn btn-secondary mb-1">View</a>
                    <a href="edit_book.php?id=<?php echo $fetch['id'] ?>" class="btn btn-secondary">Edit</a>
                    <form action="delete.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $fetch['id'] ?>">
                        <button type="submit" name="btn_delete" class="btn btn-danger mt-2">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<!-- Include JavaScript for AJAX request to fetch subcategories -->
<script>
$(document).ready(function() {
    $('#category_id').change(function() {
        var categoryId = $(this).val();

        // AJAX request to fetch subcategories
        $.ajax({
            url: 'fetch_subcategories.php',
            method: 'GET',
            data: { category_id: categoryId },
            dataType: 'json',
            success: function(response) {
                // Clear previous options
                $('#subcategory_id').empty();

                // Append new options based on response
                if (response.length > 0) {
                    $.each(response, function(index, subcategory) {
                        $('#subcategory_id').append('<option value="' + subcategory.subcat_id + '">' + subcategory.subcat_name + '</option>');
                    });
                } else {
                    $('#subcategory_id').append('<option value="">No Subcategories available</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching subcategories:', error);
            }
        });
    });
});
</script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
