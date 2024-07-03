<?php
session_start();
require('functions.php');

// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if book ID is provided via GET parameter
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch book details from database based on ID
    $sql = "SELECT * FROM images WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    $book = mysqli_fetch_assoc($result);

    // Process form submission for updating book details
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_edit'])) {
        $bookName = $_POST['bookname'];
        $authorName = $_POST['authorname'];
        $category_id = $_POST['category_id'];
        $subcat_id = $_POST['subcat_id'];

        // Check if a new book cover file is uploaded
        $bookCover = $_FILES["bookcover"]["name"];
        $bookCoverTemp = $_FILES["bookcover"]["tmp_name"];

        if (!empty($bookCover)) {
            // Check if the uploaded file is an image
            $imageInfo = getimagesize($bookCoverTemp);
            if ($imageInfo === false) {
                echo "<div class='alert alert-danger' role='alert'>
                        <h4 class='text-center'>Invalid image format for book cover</h4>
                      </div>";
            } else {
                // Move uploaded file to book_covers folder
                $newBookCover = "book_covers/" . basename($bookCover);
                move_uploaded_file($bookCoverTemp, $newBookCover);

                // Update book details including book cover
                $updateSql = "UPDATE images SET book_name = '$bookName', author_name = '$authorName', book_cover = '$bookCover', cat_id = '$category_id', subcat_id = '$subcat_id' WHERE id = $id";
                if (mysqli_query($conn, $updateSql)) {
                    echo "<div class='alert alert-success' role='alert'>
                            <h4 class='text-center'>Book details updated successfully!</h4>
                          </div>";
                    // Optionally, redirect to another page after successful update
                    // header("Location: list_books.php");
                    // exit();
                } else {
                    echo "<div class='alert alert-danger' role='alert'>
                            <h4 class='text-center'>Error updating book details: " . mysqli_error($conn) . "</h4>
                          </div>";
                }
            }
        } else {
            // Update book details excluding book cover
            $updateSql = "UPDATE images SET book_name = '$bookName', author_name = '$authorName', cat_id = '$category_id', subcat_id = '$subcat_id' WHERE id = $id";
            if (mysqli_query($conn, $updateSql)) {
                echo "<div class='alert alert-success' role='alert'>
                        <h4 class='text-center'>Book details updated successfully!</h4>
                      </div>";
                // Optionally, redirect to another page after successful update
                // header("Location: list_books.php");
                // exit();
            } else {
                echo "<div class='alert alert-danger' role='alert'>
                        <h4 class='text-center'>Error updating book details: " . mysqli_error($conn) . "</h4>
                      </div>";
            }
        }
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>
            <h4 class='text-center'>Book ID not provided.</h4>
          </div>";
    // Redirect or handle error as per your application flow
    // header("Location: list_books.php");
    // exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<?php include 'admin_navbar.php'; ?>

<div class="container col-6 mt-5">
    <h1 class="text-center">Edit Book</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" class="form-control mb-3" name="bookname" placeholder="Book Name" value="<?php echo $book['book_name']; ?>" required>
        <input type="text" class="form-control mb-3" name="authorname" placeholder="Author Name" value="<?php echo $book['author_name']; ?>" required>
        <!-- Add input for book cover -->
        <input type="file" class="form-control mb-3" name="bookcover" accept="image/*">
        <!-- Add a dropdown for category selection -->
        <select class="form-control mb-3" name="category_id" required>
            <option value="">Select Category</option>
            <?php
            // Retrieve category names and IDs from the database
            $sql = "SELECT cat_id, cat_name FROM category";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row['cat_id'] == $book['cat_id']) ? "selected" : "";
                echo "<option value='" . $row['cat_id'] . "' $selected>" . $row['cat_name'] . "</option>";
            }
            ?>
        </select>
        <!-- Add a dropdown for subcategory selection -->
        <select class="form-control mb-3" name="subcat_id" required>
            <option value="">Select Subcategory</option>
            <?php
            // Retrieve subcategory names and IDs from the database based on selected category
            $selectedCatId = $book['cat_id'];
            $sqlSub = "SELECT subcat_id, subcat_name FROM subcategory WHERE cat_id = $selectedCatId";
            $resultSub = mysqli_query($conn, $sqlSub);
            while ($rowSub = mysqli_fetch_assoc($resultSub)) {
                $selectedSub = ($rowSub['subcat_id'] == $book['subcat_id']) ? "selected" : "";
                echo "<option value='" . $rowSub['subcat_id'] . "' $selectedSub>" . $rowSub['subcat_name'] . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="btn_edit" class="btn btn-primary">Update Book</button>
    </form>
</div>

<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
