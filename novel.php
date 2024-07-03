<?php
session_start();

// Include your database connection code here
$connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch and display Books from the 'novel' category
$category = 'novel'; // Replace 'novel' with the category you want to display
$query = "SELECT * FROM images WHERE cat_id = (SELECT cat_id FROM category WHERE cat_name = ?) ORDER BY date_added DESC";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $category);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Novel Books</title>
</head>
<body>
    <!-- Navigation bar -->
    <?php include 'navbar.php'; ?>
    
    <div class="container col-12 m-5">
        <div class="col-12 m-auto">
            <h2 class="text-center">Novel Books</h2>
    
            <!-- Add a table to display the list of Books -->
            <table class="table text-center">
                <tr>
                    <th>Book Cover</th>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Published Date</th>
                    <th>Action</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><img src="admin/book_covers/<?php echo $row['book_cover'] ?>" alt="Book Cover" style="max-width: 100px;"></td>
                        <td><?php echo $row['book_name'] ?></td>
                        <td><?php echo $row['author_name'] ?></td>
                        <td><?php echo $row['published_date'] ?></td>
                        <td>
                            <a href="admin/pdf/<?php echo $row['pdf'] ?>" target="_blank" class="btn btn-primary">View</a>
                            <a href="admin/pdf/<?php echo $row['pdf'] ?>" download class="btn btn-success">Download</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
</body>
</html>
