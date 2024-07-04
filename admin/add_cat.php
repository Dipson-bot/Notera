<?php
require("functions.php");
session_start();

// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require("db_connection.php"); // Include the database connection file

// Function to check if a category already exists
function categoryExists($connection, $cat_name)
{
    $query = "SELECT * FROM category WHERE cat_name = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $cat_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}

if (isset($_POST['add_cat'])) {
    $cat_name = $_POST['cat_name'];

    // Check if the category already exists
    if (categoryExists($connection, $cat_name)) {
        echo "<div class='alert alert-danger' role='alert'>
                Category '$cat_name' already exists. Please choose a different category name.
              </div>";
    } else {
        // Use prepared statements to insert data safely
        $query = "INSERT INTO category (cat_name) VALUES (?)";
        $stmt = mysqli_prepare($connection, $query);

        // Bind the parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "s", $cat_name);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: manage_cat.php");
            exit; // Make sure to exit after a header redirect
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}
?>
<!-- Rest of your HTML code -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Category</title>
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
            alert("Category added successfully...");
            window.location.href = "admin_dashboard.php";
        }
    </script>
</head>
<body>
<?php include 'admin_navbar.php'; ?>


<center><h4>Add a new Category</h4><br></center>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" class="form-control" name="cat_name" required>
            </div>
            <br>
            <button type="submit" name="add_cat" class="btn btn-primary">Add Category</button>
        </form>
    </div>
    <div class="col-md-4"></div>
</div>
</body>
</html>

<?php
mysqli_close($connection);
?>
