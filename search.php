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
<nav class="navbar navbar-expand-lg bg-dark border-bottom border-bottom-dark" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <a class="navbar-brand" href="user_dashboard.php">Notera</a>
                <li class="nav-item">
                    <a class="nav-link active" href="listofBooks.php">List of Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="contact.php">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="aboutus.php">About Us</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">My Profile</a>
                    <ul class="dropdown-menu">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" role="button" href="logout.php">Logout</a>
                </li>
            </ul>

            <!-- search bar -->
            <form class="d-flex" action="search.php" method="post">
                <input class="form-control me-2" type="search" placeholder="Search by Book Name or Category"
                       aria-label="Search" name="search_query">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_query"])) {
    $searchTerm = $_POST["search_query"];

    $conn = mysqli_connect("localhost", "root", "", "pdfupload");

    // Search by both book_name and cat_name using JOIN with the 'category' table
    $searchQuery = "SELECT images.*, category.cat_name 
                    FROM images 
                    LEFT JOIN category ON images.cat_id = category.cat_id 
                    WHERE images.book_name LIKE '%$searchTerm%' OR category.cat_name LIKE '%$searchTerm%'";
    
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
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>
