<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<?php include 'navbar.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_query"])) {
    $searchTerm = $_POST["search_query"];

    $conn = mysqli_connect("localhost", "root", "", "pdfupload");
    $conn_lms = mysqli_connect("localhost", "root", "", "lms");

    if (!$conn || !$conn_lms) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Search by book_name, cat_name, and user name
    $searchQuery = "SELECT images.*, category.cat_name, users.name AS uploaded_by_name 
                    FROM images 
                    LEFT JOIN category ON images.cat_id = category.cat_id 
                    LEFT JOIN lms.users ON images.uploaded_by = lms.users.id 
                    WHERE images.book_name LIKE '%$searchTerm%' 
                    OR category.cat_name LIKE '%$searchTerm%' 
                    OR users.name LIKE '%$searchTerm%'";

    $result = mysqli_query($conn, $searchQuery);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="container">';
        echo '<h2 class="text-center mt-4">Search Results</h2>';
        echo '<table class="table text-center">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Book Cover</th>';
        echo '<th>Book Name</th>';
        echo '<th>Author Name</th>';
        echo '<th>Published Date</th>';
        echo '<th>Category</th>';
        echo '<th>Uploaded By</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            $pdfFilePath = 'admin/pdf/' . $row['pdf'];
            $bookCoverPath = 'admin/book_covers/' . $row['book_cover'];

            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td><img src="' . $bookCoverPath . '" alt="Book Cover" style="max-width: 100px;"></td>';
            echo '<td>' . $row['book_name'] . '</td>';
            echo '<td>' . $row['author_name'] . '</td>';
            echo '<td>' . $row['published_date'] . '</td>';
            echo '<td>' . $row['cat_name'] . '</td>';
            echo '<td>' . $row['uploaded_by_name'] . '</td>';
            echo '<td>';
            echo '<a href="' . $pdfFilePath . '" target="_blank" class="btn btn-primary">View</a>';
            echo '<a href="' . $pdfFilePath . '" download class="btn btn-success">Download</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="container mt-4">';
        echo '<div class="alert alert-info text-center" role="alert">No matching Books found.</div>';
        echo '</div>';
    }

    mysqli_close($conn);
    mysqli_close($conn_lms);
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
