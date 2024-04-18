
<?php
session_start();
// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Create a database connection
$connection = mysqli_connect("localhost", "root", "", "pdfupload");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$subcat_id = "";

// Check if the subcategory ID is set in the URL
if (isset($_GET['sid'])) {
    $subcat_id = $_GET['sid'];

    // Delete subcategory from the database using prepared statement
    $query = "DELETE FROM subcategory WHERE subcat_id = ?";
    $stmt = mysqli_prepare($connection, $query);

    // Bind parameter
    mysqli_stmt_bind_param($stmt, "i", $subcat_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Close the statement
        mysqli_stmt_close($stmt);
        // Redirect to the manage_subcat.php page after successful deletion
        header("location: manage_subcat.php");
    } else {
        echo "Subcategory deletion failed: " . mysqli_error($connection);
    }
} else {
    echo "Subcategory ID not provided.";
}

// Close the database connection
mysqli_close($connection);
?>
