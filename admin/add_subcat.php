<?php
require("functions.php");
require("db_connection.php");

session_start();

// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch categories from the database
$query = "SELECT cat_id, cat_name FROM category";
$result = mysqli_query($connection, $query);

// Check if the categories were fetched successfully
if (!$result) {
    echo "Error fetching categories: " . mysqli_error($connection);
    exit();
}

// Function to check if a subcategory already exists
function subcategoryExists($connection, $subcat_name, $cat_id)
{
    $query = "SELECT * FROM subcategory WHERE subcat_name = ? AND cat_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "si", $subcat_name, $cat_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}

if (isset($_POST['add_subcat'])) {
    $subcat_name = $_POST['subcat_name'];
    $cat_id = $_POST['cat_id'];

    // Check if the subcategory already exists for the selected category
    if (subcategoryExists($connection, $subcat_name, $cat_id)) {
        echo "<div class='alert alert-danger' role='alert'>
                Subcategory '$subcat_name' already exists for the selected category. Please choose a different subcategory name.
              </div>";
    } else {
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

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($connection); // Close database connection
            echo '<script>alertMsg();</script>';
            exit; // Make sure to exit after a successful insert
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Subcategory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function alertMsg(){
            alert("Subcategory added successfully...");
            window.location.href = "admin_dashboard.php";
        }
    </script>
</head>
<body>
<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h4 class="text-center">Add a new Subcategory</h4>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="add_subcat.php" method="post">
                <div class="form-group">
                    <label for="subcat_name">Subcategory Name:</label>
                    <input type="text" class="form-control" id="subcat_name" name="subcat_name" required>
                </div>
                <div class="form-group">
                    <label for="cat_id">Category:</label>
                    <select class="form-control" id="cat_id" name="cat_id" required>
                        <option value="">Select Category</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value=\"{$row['cat_id']}\">{$row['cat_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="add_subcat" class="btn btn-primary">Add Subcategory</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<?php
mysqli_close($connection);
?>
