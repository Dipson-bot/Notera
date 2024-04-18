<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_approve'])) {
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

    // Fetch data from pending_Books table based on book_id
    $selectQuery = "SELECT * FROM pending_Books WHERE id='$bookId'";
    $selectResult = mysqli_query($conn, $selectQuery);

    if ($selectResult && mysqli_num_rows($selectResult) > 0) {
        $row = mysqli_fetch_assoc($selectResult);
        $categoryId = $row['cat_id'];
        $subcategoryId = $row['subcat_id']; // Fetch subcat_id from pending_Books table

        // Insert data into images table with subcat_id
        $insertQuery = "INSERT INTO `images` (`pdf`, `book_name`, `author_name`, `published_date`, `book_cover`, `cat_id`, `subcat_id`)
                        SELECT `pdf`, `book_name`, `author_name`, `published_date`, `book_cover`, '$categoryId', '$subcategoryId' 
                        FROM `pending_Books` WHERE `id`='$bookId'";

        if (mysqli_query($conn, $insertQuery)) {
            // Remove thNotera from pending_Books table after moving to images table
            $deleteQuery = "DELETE FROM `pending_Books` WHERE `id`='$bookId'";
            if (mysqli_query($conn, $deleteQuery)) {
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard after approval and removal
                exit();
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        } else {
            echo "Error inserting record: " . mysqli_error($conn);
        }
    } else {
        echo "Book not found.";
    }

    mysqli_close($conn);
}
?>
