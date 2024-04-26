<?php
// var_dump($_GET['category']);
session_start();

$conn = mysqli_connect("localhost", "root", "", "pdfupload");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the 'category' query parameter is set
if (isset($_GET['category'])) {
    $selectedCategory = urldecode($_GET['category']);
    // echo "Selected Category ID: " . $selectedCategory; 
    // Query to fetch Books based on the selected category (assuming cat_id is the correct column name)
    $sql = "SELECT * FROM images WHERE cat_id = '$selectedCategory'";
    // echo "SQL Query: " . $sql;
} else {
    // Default query to fetch all Books if no category is selected
    $sql = "SELECT * FROM images";
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}




// HandlNotera download
if (isset($_GET['download_book'])) {
    $book_id = $_GET['download_book'];
    $sql = "SELECT * FROM images WHERE id = $book_id";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    
    $pdf_file = $row['pdf'];
    $pdf_path = 'admin/pdf/' . $pdf_file;

    if (file_exists($pdf_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($pdf_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($pdf_path));
        readfile($pdf_path);
        exit;
    }
}

// HandlNotera search
$search_query = "";
if (isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];

    $sql = "SELECT images.*, category.cat_name, subcategory.subcat_name
            FROM images
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            WHERE images.book_name LIKE '%$search_query%'
            OR category.cat_name LIKE '%$search_query%'
            OR subcategory.subcat_name LIKE '%$search_query%'
            OR images.author_name LIKE '%$search_query%'
            ORDER BY images.date_added DESC";
} else if (isset($_GET['category'])) {
    // Modify the SQL query to select Books from a specific category
    $selectedCategory = $_GET['category'];
    $sql = "SELECT images.*, category.cat_name, subcategory.subcat_name
            FROM images
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            WHERE images.cat_id = $selectedCategory
            ORDER BY images.date_added DESC";
} else {
    $sql = "SELECT images.*, category.cat_name, subcategory.subcat_name
            FROM images
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            ORDER BY images.date_added DESC";
}



$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}



   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .navbar {
                position: sticky;
                top: 0;
                z-index: 100;
            }
    </style>
<body>
        <nav class="navbar navbar-expand lg navbar-dark bg-dark">
            <div class="container-fluid">
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <a class="navbar-brand" href="user_dashboard.php">Notera</a>
                        <?php
                            // Check if the user is an admin
                            if ($_SESSION['role'] === 'admin') {
                                echo '<li class="nav-item">';
                                echo '<a class="nav-link active" href="admin/admin_dashboard.php">Admin</a>';
                                echo '</li>';
                            }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="listofBooks.php">List of Books</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link active" href="contact.php">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="aboutus.php">About Us</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">My Profile </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="downloads.php">Downloads</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="uploadpdf.php">Upload Books</a></li>
                            </ul>
                        </li>

                        
                        <li class="nav-item">
                            <a class="nav-link" role="button" href="logout.php">Logout</a>
                        </li>
                    </ul>
                    

                    <!-- Search bar -->
                    <form class="d-flex" action="listofBooks.php" method="post">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query" value="<?php echo $search_query; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
                </div>
            </div>
        </nav>

        <div class="container col-12 m-5">
    <div class="col-12 m-auto">
        <h2 class="text-center">List of Books</h2>

        <!-- Add a table to display the list of Books -->
        <table class="table text-center">
            <tr>
                <th>Book Cover</th>
                <th>Book Name</th>
                <th>Author Name</th>
                <th>Uploaded Date</th>
                <th>Semester</th>
                <th>Subject</th> <!-- Add Subcategory column -->
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
                    <td><?php echo $row['cat_name'] ?></td>
                    <td><?php echo $row['subcat_name'] ?></td> <!-- Display Subcategory -->
                    <td>
                        <a href="admin/pdf/<?php echo $row['pdf'] ?>" target="_blank" class="btn btn-primary">View</a>
                        <a href="downloads.php?book_id=<?php echo $row['id']; ?>&pdf=<?php echo urlencode($row['pdf']); ?>" class="btn btn-success">Download</a>
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
