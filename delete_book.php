<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Connect to the 'pdfupload' database
$pdfupload_connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$pdfupload_connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the book ID from the query parameter
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the book details to get the file name
$query = "SELECT * FROM images WHERE id = $book_id";
$result = mysqli_query($pdfupload_connection, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $book_cover = $row['book_cover'];
    $pdf = $row['pdf'];
}

// Delete the book from the database
$query = "DELETE FROM images WHERE id = $book_id";
if (mysqli_query($pdfupload_connection, $query)) {
    // If the book is deleted successfully, delete the files from the server
    if (file_exists("admin/book_covers/$book_cover")) {
        unlink("admin/book_covers/$book_cover");
    }
    if (file_exists("admin/pdf/$pdf")) {
        unlink("admin/pdf/$pdf");
    }
    header("Location: view_profile.php?message=Book+deleted+successfully"); // Redirect to profile page with a success message
} else {
    header("Location: view_profile.php?message=Failed+to+delete+book"); // Redirect to profile page with an error message
}

mysqli_close($pdfupload_connection);
?>
