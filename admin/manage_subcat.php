<?php
require("functions.php");
require("db_connection.php"); // Include the database connection file 
session_start();
// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

$connection = mysqli_connect("localhost", "root", "", "pdfupload");

// Check if the database connection was successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Subcategory</title>
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

    
<center><h4>Manage Subcategory</h4><br></center>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th style="width: 50%;">Name</th>
                    <th style="width: 25%;">Category</th>
                    <th class="text-center" style="width: 25%;">Action</th>
                </tr>
            </thead>
            <?php
            $query = "SELECT s.subcat_id, s.subcat_name, c.cat_name FROM subcategory s JOIN category c ON s.cat_id = c.cat_id";
            $query_run = mysqli_query($connection, $query);

            if (!$query_run) {
                die("Query failed: " . mysqli_error($connection));
            }

            while ($row = mysqli_fetch_assoc($query_run)) {
            ?>
                <tr>
                    <!-- <td><?php echo $row['subcat_id']; ?></td> -->
                    <td><?php echo $row['subcat_name']; ?></td>
                    <td><?php echo $row['cat_name']; ?></td>
                    <td class="text-center">
                        <a href="edit_subcat.php?sid=<?php echo $row['subcat_id']; ?>" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['subcat_id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <div class="col-md-2"></div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this subcategory?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteSubcategory()">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Functions -->
<script>
    function confirmDelete(subcatId) {
        // Set the subcatId in a data attribute to use it later in the deleteSubcategory function
        $('#confirmDeleteModal').data('subcatid', subcatId);
        // Show the confirmation modal
        $('#confirmDeleteModal').modal('show');
    }

    function deleteSubcategory() {
        // Get the subcatId from the data attribute
        var subcatId = $('#confirmDeleteModal').data('subcatid');
        // TODO: Perform the deletion action using AJAX or form submission

        // For example, you can use AJAX to delete the subcategory
        $.ajax({
            url: 'delete_subcat.php?sid=' + subcatId, // Update the URL with your backend endpoint
            type: 'GET',
            success: function (response) {
                // Handle the response (e.g., show a success message)
                alert('Subcategory deleted successfully!');
                // Reload the page after deletion
                window.location.reload(); // Reload the page after subcategory is deleted
            },
            error: function (xhr, status, error) {
                // Handle errors if any
                console.error('Error deleting subcategory: ' + error);
            }
        });

        // Close the confirmation modal
        $('#confirmDeleteModal').modal('hide');
    }
</script>
</body>
</html>
