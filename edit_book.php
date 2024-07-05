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

// Initialize variables
$book_name = "";
$author_name = "";
$book_cover = "";
$pdf = "";
$message = "";

// Fetch the current book details
$query = "SELECT * FROM images WHERE id = $book_id";
$result = mysqli_query($pdfupload_connection, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $book_name = $row['book_name'];
    $author_name = $row['author_name'];
    $book_cover = $row['book_cover'];
    $pdf = $row['pdf'];
} else {
    die("Book not found");
}

// Handle form submission for editing the book details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_name = mysqli_real_escape_string($pdfupload_connection, $_POST['book_name']);
    $author_name = mysqli_real_escape_string($pdfupload_connection, $_POST['author_name']);

    // Handle file uploads
    if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] == 0) {
        $book_cover = basename($_FILES['book_cover']['name']);
        $book_cover_target = "admin/book_covers/" . $book_cover;
        move_uploaded_file($_FILES['book_cover']['tmp_name'], $book_cover_target);
    }

    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $pdf = basename($_FILES['pdf']['name']);
        $pdf_target = "admin/pdf/" . $pdf;
        move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_target);
    }

    // Update the book details in the database
    $update_query = "UPDATE images SET book_name = '$book_name', author_name = '$author_name', book_cover = '$book_cover', pdf = '$pdf' WHERE id = $book_id";
    if (mysqli_query($pdfupload_connection, $update_query)) {
        $message = "Notes details updated successfully";
    } else {
        $message = "Failed to update Notes details: " . mysqli_error($pdfupload_connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-container h3 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="form-container">
        <h3>Edit Notes</h3>
        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="edit_book.php?id=<?php echo $book_id; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="book_name" class="form-label">Note Name</label>
                <input type="text" class="form-control" id="book_name" name="book_name" value="<?php echo htmlspecialchars($book_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="author_name" class="form-label">Written By</label>
                <input type="text" class="form-control" id="author_name" name="author_name" value="<?php echo htmlspecialchars($author_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="book_cover" class="form-label">Note Cover</label>
                <input type="file" class="form-control" id="book_cover" name="book_cover">
                <img src="admin/book_covers/<?php echo htmlspecialchars($book_cover); ?>" alt="Current Book Cover" class="img-thumbnail mt-2" width="100">
            </div>
            <div class="mb-3">
                <label for="pdf" class="form-label">PDF File</label>
                <input type="file" class="form-control" id="pdf" name="pdf">
                <a href="admin/pdf/<?php echo htmlspecialchars($pdf); ?>" target="_blank">View Current PDF</a>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
