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
$subcat_name = "";
$cat_id = "";

// Check if the subcategory ID is set in the URL
if (isset($_GET['sid'])) {
    $subcat_id = $_GET['sid'];

    // Fetch subcategory data
    $query = "SELECT subcat_name, cat_id FROM subcategory WHERE subcat_id = ?";
    $stmt = mysqli_prepare($connection, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "i", $subcat_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Bind the result variables
        mysqli_stmt_bind_result($stmt, $subcat_name, $cat_id);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        die("Query failed: " . mysqli_error($connection));
    }
} else {
    echo "Subcategory ID not provided.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Subcategory</title>
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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <a class="navbar-brand" href="admin_dashboard.php">Notera</a>
                <li class="nav-item">
                    <a class="nav-link active" href="../user_dashboard.php">User View</a>
                </li>
                <!-- Other navigation items -->
            </ul>
        </div>
    </div>
</nav><br>

<center><h4>Edit Subcategory</h4><br></center>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Subcategory Name:</label>
                <input type="text" class="form-control" name="subcat_name" value="<?php echo $subcat_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" name="cat_id" required>
                    <?php
                    // Fetch categories from the database
                    $cat_query = "SELECT cat_id, cat_name FROM category";
                    $cat_result = mysqli_query($connection, $cat_query);

                    if (!$cat_result) {
                        die("Query failed: " . mysqli_error($connection));
                    }

                    while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                        $selected = ($cat_id == $cat_row['cat_id']) ? 'selected' : '';
                        echo "<option value='{$cat_row['cat_id']}' $selected>{$cat_row['cat_name']}</option>";
                    }

                    // Free the result set
                    mysqli_free_result($cat_result);
                    ?>
                </select>
            </div><br>
            <button type="submit" name="update_subcat" class="btn btn-primary">Update Subcategory</button>
        </form>
    </div>
    <div class="col-md-4"></div>
</div>
</body>
</html>

<?php
if (isset($_POST['update_subcat'])) {
    $new_subcat_name = $_POST['subcat_name'];
    $new_cat_id = $_POST['cat_id'];

    // Update subcategory in the database using prepared statement
    $query = "UPDATE subcategory SET subcat_name = ?, cat_id = ? WHERE subcat_id = ?";
    $stmt = mysqli_prepare($connection, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sii", $new_subcat_name, $new_cat_id, $subcat_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        header("location: manage_subcat.php");
    } else {
        echo "Subcategory update failed: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($connection);
?>
