<?php
session_start();
include('admin/functions.php');

// Set the default timezone to Kathmandu, Nepal
date_default_timezone_set('Asia/Kathmandu');

// Database connection for lms database
$lms_connection = mysqli_connect("localhost", "root", "", "lms");
if (!$lms_connection) {
    die("LMS Database connection failed: " . mysqli_connect_error());
}

// Database connection for pdfupload database
$pdfupload_connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$pdfupload_connection) {
    die("PDFUpload Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['pdf']) && isset($_GET['book_id'])) {
    $pdf = $_GET['pdf'];
    $bookId = $_GET['book_id'];
    $userId = $_SESSION['id'];
    $downloadDate = date('Y-m-d H:i:s');

    // Update downloads table
    $insert_query = "INSERT INTO downloads (book_id, user_id, download_date) VALUES ('$bookId', '$userId', '$downloadDate')";
    if (mysqli_query($pdfupload_connection, $insert_query)) {
        // File download logic here (use appropriate file path and headers)
        $file_path = 'admin/pdf/' . $pdf;
        header("Content-Disposition: attachment; filename=" . urlencode($pdf));
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize($file_path));
        flush();
        $fp = fopen($file_path, "r");
        while (!feof($fp)) {
            echo fread($fp, 65536);
            flush();
        }
        fclose($fp);
        exit();
    } else {
        echo "Error updating downloads table: " . mysqli_error($pdfupload_connection);
    }
}

//HandlNotera search
$connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
$search_query = "";
if (isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];
    
    $sql = "SELECT images.*, category.cat_name
            FROM images
            LEFT JOIN category ON images.cat_id = category.cat_id
            WHERE images.book_name LIKE '%$search_query%'
            OR category.cat_name LIKE '%$search_query%'
            OR images.author_name LIKE '%$search_query%'
            ORDER BY images.date_added DESC";
} else {

    $sql = "SELECT images.*, category.cat_name
            FROM images
            LEFT JOIN category ON images.cat_id = category.cat_id
            ORDER BY images.date_added DESC";
}

$result = mysqli_query($connection, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Downloaded Books</title>
</head>

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
            <h2 class="text-center">Downloaded Books</h2>
            <p class="text-center">Total Downloads: <?php echo get_downloads_count($pdfupload_connection); ?></p>

            <!-- Add a table to display the list of downloaded Books -->
            <table class="table text-center">
                <tr>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Download Date</th>
                </tr>
                <?php foreach (get_downloaded_Books($pdfupload_connection) as $book) : ?>
                    <tr>
                        <td><?php echo $book['book_name']; ?></td>
                        <td><?php echo $book['author_name']; ?></td>
                        <td><?php echo $book['download_date']; ?></td>
                        <td>
                        
                        

                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
// Close database connections at the end of the script
mysqli_close($lms_connection);
mysqli_close($pdfupload_connection);
?>
