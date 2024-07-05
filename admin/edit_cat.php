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

$cat_id = "";
$cat_name = "";

// Check if the category ID is set in the URL
if (isset($_GET['cid'])) {
    $cat_id = $_GET['cid'];

    // Fetch category data
    $query = "SELECT cat_name FROM category WHERE cat_id = ?";
    $stmt = mysqli_prepare($connection, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "i", $cat_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Bind the result variable
        mysqli_stmt_bind_result($stmt, $cat_name);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        die("Query failed: " . mysqli_error($connection));
    }
} else {
    echo "Category ID not provided.";
}

$message = "";

if (isset($_POST['update_cat'])) {
    $new_cat_name = $_POST['cat_name'];

    // Check if the new category name already exists
    $check_query = "SELECT cat_id FROM category WHERE cat_name = ? AND cat_id != ?";
    $check_stmt = mysqli_prepare($connection, $check_query);
    mysqli_stmt_bind_param($check_stmt, "si", $new_cat_name, $cat_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $message = "Category name already exists.";
    } else {
        // Update category in the database using prepared statement
        $query = "UPDATE category SET cat_name = ? WHERE cat_id = ?";
        $stmt = mysqli_prepare($connection, $query);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "si", $new_cat_name, $cat_id);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            header("Location: manage_cat.php");
        } else {
            $message = "Category update failed: " . mysqli_error($connection);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Close the check statement
    mysqli_stmt_close($check_stmt);
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>    
</head>
<body>
<?php include 'admin_navbar.php'; ?>

<center><h4>Edit Category</h4><br></center>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <?php if ($message != ""): ?>
            <div class="alert alert-warning" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" class="form-control" name="cat_name" value="<?php echo $cat_name; ?>" required>
            </div><br>
            <button type="submit" name="update_cat" class="btn btn-primary">Update Category</button>
        </form>
    </div>
    <div class="col-md-4"></div>
</div>
</body>
</html>
