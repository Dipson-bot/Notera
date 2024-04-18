<?php
if (isset($_POST['btn_delete'])) {
    $id = $_POST['id'];
    $con = mysqli_connect("localhost", "root", "", "pdfupload");

    if (!$con) {
        die("Database connection error: " . mysqli_connect_error());
    }

    // Retrieve the PDF file name from the database
    $sql = "SELECT pdf FROM images WHERE id = $id";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Error retrieving PDF record: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    $pdfFilename = $row['pdf'];

    // Delete the PDF file from the server, if it exists
    $pdfFilePath = "pdf/" . $pdfFilename;
    if (file_exists($pdfFilePath)) {
        if (unlink($pdfFilePath)) {
            // PDF file deleted successfully from the server

            // Delete the record from the database
            $deleteSql = "DELETE FROM images WHERE id = $id";
            $deleteResult = mysqli_query($con, $deleteSql);

            if ($deleteResult) {
                // Redirect back to add_book.php
                header("Location: add_book.php");
                exit;
            } else {
                echo "Error deleting PDF record from the database: " . mysqli_error($con);
            }
        } else {
            echo "Error deleting PDF file from the server.";
        }
    } else {
        // PDF file not found on the server, but still, delete the record from the database
        $deleteSql = "DELETE FROM images WHERE id = $id";
        $deleteResult = mysqli_query($con, $deleteSql);

        if ($deleteResult) {
            // Redirect back to add_book.php
            header("Location: add_book.php");
            exit;
        } else {
            echo "Error deleting PDF record from the database: " . mysqli_error($con);
        }
    }

    mysqli_close($con);
} else {
    echo "Invalid request.";
}
?>
