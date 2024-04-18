<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_reject'])) {
    $bookId = $_POST['book_id'];
    
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "pdfupload";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Delete thNotera entry from the pending_Books table
    $deleteQuery = "DELETE FROM `pending_Books` WHERE `id`='$bookId'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard after rejection
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
