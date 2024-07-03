<?php
// Database connection for pdfupload database
$pdfupload_connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$pdfupload_connection) {
    die("PDFUpload Database connection failed: " . mysqli_connect_error());
}

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
} else {
    die("Book ID not provided.");
}

// Calculate average rating for the book
$average_rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE book_id = $book_id";
$average_rating_result = mysqli_query($pdfupload_connection, $average_rating_sql);
if (!$average_rating_result) {
    die("Query failed: " . mysqli_error($pdfupload_connection));
}

$average_rating = mysqli_fetch_assoc($average_rating_result)['avg_rating'];

// Output the average rating
echo $average_rating;

// Close database connection
mysqli_close($pdfupload_connection);
?>
