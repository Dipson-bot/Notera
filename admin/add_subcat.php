<?php
require("functions.php");
session_start();
// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
require("db_connection.php"); // Include the database connection file

// Fetch categories from the database
$query = "SELECT cat_id, cat_name FROM category";
$result = mysqli_query($connection, $query);

// Check if the categories were fetched successfully
if (!$result) {
    echo "Error fetching categories: " . mysqli_error($connection);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Subcategory</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
    <script type="text/javascript">
        function alertMsg(){
            alert("Subcategory added successfully...");
            window.location.href = "admin_dashboard.php";
        }
    </script>
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


<center><h4>Add a new Subcategory</h4><br></center>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form action="" method="post">
            <div class="form-group">
                <label for="subcat_name">Subcategory Name:</label>
                <input type="text" class="form-control" name="subcat_name" required>
            </div>
            <div class="form-group">
                <label for="cat_id">Category:</label>
                <select class="form-control" name="cat_id" required>
                    <option value="">Select Category</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value=\"{$row['cat_id']}\">{$row['cat_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <br>
            <button type="submit" name="add_subcat" class="btn btn-primary">Add Subcategory</button>
        </form>
    </div>
    <div class="col-md-4"></div>
</div>
</body>
</html>

<?php
if (isset($_POST['add_subcat'])) {
    $subcat_name = $_POST['subcat_name'];
    $cat_id = $_POST['cat_id'];

    // Use prepared statements to insert data safely
    $query = "INSERT INTO subcategory (subcat_name, cat_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    // Bind the parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "si", $subcat_name, $cat_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: manage_subcat.php");
        exit; // Make sure to exit after a header redirect
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
