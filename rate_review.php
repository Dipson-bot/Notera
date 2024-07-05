<?php
session_start();
include('admin/functions.php');

// Set the default timezone to Kathmandu, Nepal
date_default_timezone_set('Asia/Kathmandu');

// Database connection for lms database
$lms_connection = mysqli_connect("localhost", "root", "", "lms");
if (!$lms_connection) {
    die("LMS Database connection failed: " . mysqli_connect_error());
}

// Database connection for pdfupload database
$pdfupload_connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$pdfupload_connection) {
    die("PDFUpload Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
} else {
    die("Book ID not provided.");
}

// Handle review submission
$review_success_message = '';
if (isset($_POST['submit_review'])) {
    if (!isset($_SESSION['id'])) {
        die("You must be logged in to submit a review.");
    }

    $user_id = $_SESSION['id']; // Use 'id' from $_SESSION directly

    // Check if the user has already reviewed the book
    $check_review_sql = "SELECT * FROM reviews WHERE book_id = $book_id AND user_id = $user_id";
    $check_review_result = mysqli_query($pdfupload_connection, $check_review_sql);
    if (mysqli_num_rows($check_review_result) > 0) {
        die("You have already reviewed this book.");
    }

    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Insert review into pdfupload.reviews table
    $review_sql = "INSERT INTO reviews (book_id, user_id, rating, review_text) VALUES ('$book_id', '$user_id', '$rating', '$review_text')";
    if (mysqli_query($pdfupload_connection, $review_sql)) {
        $review_success_message = "Review submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($pdfupload_connection);
    }
}

// Fetch book details from pdfupload database
$sql_book = "SELECT * FROM images WHERE id = $book_id";
$result_book = mysqli_query($pdfupload_connection, $sql_book);
if (!$result_book) {
    die("Query failed: " . mysqli_error($pdfupload_connection));
}
$book = mysqli_fetch_assoc($result_book);

// Function to include average_rating.php and retrieve average rating
function get_average_rating($pdfupload_connection, $book_id) {
    ob_start(); // Start output buffering
    include "average_rating.php";
    $output = ob_get_clean(); // Get the output from the included file
    return $output;
}

// Calculate average rating for the book
$average_rating = get_average_rating($pdfupload_connection, $book_id);

// Ensure $average_rating is a float before using number_format()
if (!empty($average_rating)) {
    $average_rating = floatval($average_rating);
} else {
    // Handle case where no ratings are available
    $average_rating = 0.0; // Default to 0.0 or any suitable value
}

// Fetch reviews for the current book from pdfupload.reviews and link to lms.users
$review_sql = "SELECT reviews.*, users.name AS user_name FROM reviews LEFT JOIN lms.users ON reviews.user_id = users.id WHERE book_id = $book_id";
$review_result = mysqli_query($pdfupload_connection, $review_sql);
if (!$review_result) {
    die("Query failed: " . mysqli_error($pdfupload_connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $book['book_name']; ?> - Rate and Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin/rate_style.css"> <!-- Include your rate_style.css file here -->

    <!-- jQuery and Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to display success message in modal -->
    <script>
    // Function to show success modal on page load if success message is set
    $(document).ready(function () {
        var reviewSuccessMessage = '<?php echo $review_success_message; ?>';
        if (reviewSuccessMessage) {
            $('#reviewSuccessModal').modal('show');
        }
    });
    </script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .book-cover {
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .book-details h4 {
            margin-bottom: 15px;
        }
        .star-rating span {
            font-size: 24px;
            margin-right: 5px;
        }
        .review {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .review strong {
            display: inline-block;
            width: 100px;
        }
        .review p {
            margin: 5px 0;
        }
        .form-group label {
            font-weight: bold;
        }
        .modal-content {
            padding: 20px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="admin/book_covers/<?php echo $book['book_cover']; ?>" alt="Book Cover" class="book-cover">
        </div>
        <div class="col-md-8 book-details">
            <h4><strong>Book Name:</strong> <?php echo $book['book_name']; ?></h4>
            <h4><strong>Author:</strong> <?php echo $book['author_name']; ?></h4>
            <h4><strong>Published Date:</strong> <?php echo date('F j, Y', strtotime($book['published_date'])); ?></h4>
            <hr>
            <h4><strong>Average Rating:</strong></h4>
            <div class="star-rating">
                <?php
                $full_stars = floor($average_rating);
                $half_star = ceil($average_rating - $full_stars);
                $empty_stars = 5 - $full_stars - $half_star;

                for ($i = 0; $i < $full_stars; $i++) {
                    echo "<span class='text-warning'>&#9733;</span>"; // Filled star
                }
                if ($half_star) {
                    echo "<span class='text-warning'>&#189;</span>"; // Half star
                }
                for ($i = 0; $i < $empty_stars; $i++) {
                    echo "<span class='text-secondary'>&#9733;</span>"; // Empty star
                }
                ?>
            </div>
            <p><?php echo number_format($average_rating, 1); ?>/5</p>
        </div>
    </div>

    <hr>

    <h3 class="mb-4">Reviews</h3>
    <?php
    while ($review = mysqli_fetch_assoc($review_result)) {
        echo "<div class='review'>";
        echo "<p><strong>User:</strong> " . htmlspecialchars($review['user_name']) . "</p>";
        echo "<p><strong>Rating:</strong> " . htmlspecialchars($review['rating']) . "/5</p>";
        echo "<p><strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "</p>";
        echo "<p><strong>Date:</strong> " . date('F j, Y', strtotime($review['review_date'])) . "</p>";
        echo "</div>";
    }
    ?>

    <hr>

    <?php
    // Check if the user has already reviewed the book
    $check_review_sql = "SELECT * FROM reviews WHERE book_id = $book_id AND user_id = $_SESSION[id]";
    $check_review_result = mysqli_query($pdfupload_connection, $check_review_sql);
    if (mysqli_num_rows($check_review_result) == 0) {
    ?>
    <h3>Submit Your Review</h3>
    <form method="post" action="">
        <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
        <!-- Store user_id from session to submit with review -->
        <div class="form-group">
            <label for="rating">Rating:</label>
            <select name="rating" class="form-control">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="review_text">Review:</label>
            <textarea name="review_text" class="form-control" rows="4" placeholder="Write your review here..."></textarea>
        </div>
        <button type="submit" name="submit_review" class="btn btn-primary mt-2">Submit</button>
    </form>
    <?php
    } else {
        echo "<p class='alert alert-info'>You have already reviewed this book.</p>";
    }
    ?>
</div>

<!-- Success Modal -->
<div class="modal fade" id="reviewSuccessModal" tabindex="-1" aria-labelledby="reviewSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewSuccessModalLabel">Review Submission Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo $review_success_message; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
