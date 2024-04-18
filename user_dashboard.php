<?php
session_start();

// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Include your database connection code here
$connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
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
if (isset($_GET['book_id']) && isset($_GET['pdf'])) {
    $bookId = $_GET['book_id'];
    $pdf = $_GET['pdf'];
    $userId = $_SESSION['id'];
    $downloadDate = date('Y-m-d H:i:s');

    // Database connection for pdfupload database
    $pdfupload_connection = mysqli_connect("localhost", "root", "", "pdfupload");
    if (!$pdfupload_connection) {
        die("PDFUpload Database connection failed: " . mysqli_connect_error());
    }

    // Update downloads table
    $insert_query = "INSERT INTO downloads (book_id, user_id, download_date) VALUES ('$bookId', '$userId', '$downloadDate')";
    if (mysqli_query($pdfupload_connection, $insert_query)) {
        // File download logic here (use appropriate file path and headers)
        $pdf_path = 'admin/pdf/' . $pdf;
        header("Content-Disposition: attachment; filename=" . urlencode($pdf));
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize($pdf_path));
        flush();
        $fp = fopen($pdf_path, "r");
        while (!feof($fp)) {
            echo fread($fp, 65536);
            flush();
        }
        fclose($fp);
        exit();
    } else {
        echo "Error updating downloads table: " . mysqli_error($pdfupload_connection);
    }

    // Close the database connection
    mysqli_close($pdfupload_connection);
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
    <link href="styles/book_card.css" rel="stylesheet" type="text/css">
    <link href="styles/background.css" rel="stylesheet" type="text/css"> 
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
    



    <style>
            /* body {
                    margin: 0;
                    font-family: var(--bs-body-font-family);
                    font-size: var(--bs-body-font-size);
                    font-weight: var(--bs-body-font-weight);
                    line-height: var(--bs-body-line-height);
                    color: var(--bs-body-color);
                    text-align: var(--bs-body-text-align);
                    background-color: #181a1b;                           
                    -webkit-text-size-adjust: 100%;
                    -webkit-tap-highlight-color: transparent;
                } */

                h1{
                    color: #d1cdc7; /*dark reader ma use vako text ko color. By default chai color vanne nai thena yesma */
                    text-align: center;             
                    }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Adjust transparency as needed */
        }

        /* .custom-caption {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            padding: 20px;
            text-align: center;
        } */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
        }
        /* .marquee-container {
            position: relative;
            width: 300px; 
            overflow: hidden;
        }

        .marquee-text {
            position: absolute;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
            animation: marquee 5s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        } */

        /* buttons color */
        .btn-custom {
            background-color: #4CAF50; /* Green color, change it to your desired color */
            border: none;
            color: white;
            padding: 10px 20px; /* Adjust padding as needed */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px; /* Remove margin between buttons */
            cursor: pointer;
            transition-duration: 0.4s;
        }       

        .btn-custom:hover {
            background-color: #45a049; /* Darker green color on hover, change it to your desired color */
        }

        
    </style>
        
</head>

<body>
<div class="animated-section">
        <div class="lines">
            <div class="line"></div>
            
            <div class="line"></div>
        </div>
        <!-- <nav class="navbar navbar-expand-lg bg-dark border-bottom border-bottom-dark" data-bs-theme="dark"> -->
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
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query" value="<?php echo $search_query; ?>" <?php echo isset($_SESSION['id']) ? '' : 'disabled'; ?>>
                        <button class="btn btn-outline-success" type="submit" <?php echo isset($_SESSION['id']) ? '' : 'disabled'; ?>>Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Carousel Slider -->
        <div class="container-fill">
            <div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2000">
                <div class="carousel-inner">
                        <div class="carousel-item active">
                                <img src="carousel/book.jpg" class="d-block w-100" alt="...">
                                <div class="overlay"></div>
                                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                                        <h1>Welcome to Notera</h1>
                                        <p><?php echo $_SESSION['name'];?></p>
                                        <div class="button-container">
                                            <!-- (COMPONENTS-5)Buttons -->
                                            <!-- <a href="<?php echo isset($_SESSION['id']) ? 'fantasy.php' : 'index.php'; ?>" class="btn btn-primary">Fantasy</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'horror.php' : 'index.php'; ?>" class="btn btn-secondary">Horror</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'adventure.php' : 'index.php'; ?>" class="btn btn-success">Adventure</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'programming.php' : 'index.php'; ?>" class="btn btn-danger">Programming</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'novel.php' : 'index.php'; ?>" class="btn btn-warning">Novel</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'finance.php' : 'index.php'; ?>" class="btn btn-info">Finance</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'scifi.php' : 'index.php'; ?>" class="btn btn-danger">SciFi</a> -->
                                            <?php
                                                $categories = getCategories($connection);
                                                $colors = array("green", "blue", "red", "orange"); // Define an array of colors for categories
                                                $index = 0; // Index to keep track of color array

                                                foreach ($categories as $categoryId => $category) {
                                                    $color = $colors[$index % count($colors)]; // Get color based on index, cycling through available colors
                                                    echo '<a href="listofBooks.php?category=' . urlencode($categoryId) . '" class="btn btn-custom" style="background-color: ' . $color . '; color: white;">' . $category . '</a>';
                                                    $index++; // Move to the next color for the next category
                                                }
                                            ?>



                                        </div>
                                    </div>
                        </div>
                        
                        <div class="carousel-item">
                                <img src="carousel/2.jpeg" class="d-block w-100" alt="...">
                                <div class="overlay"></div>
                                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                                        <h1>Simple and Minimalistic</h1>
                                        <p>With quick search and downloads</p>
                                        <div class="button-container">
                                            <!-- (COMPONENTS-5)Buttons -->
                                            <!-- <a href="<?php echo isset($_SESSION['id']) ? 'fantasy.php' : 'index.php'; ?>" class="btn btn-primary">Fantasy</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'horror.php' : 'index.php'; ?>" class="btn btn-secondary">Horror</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'adventure.php' : 'index.php'; ?>" class="btn btn-success">Adventure</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'programming.php' : 'index.php'; ?>" class="btn btn-danger">Programming</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'novel.php' : 'index.php'; ?>" class="btn btn-warning">Novel</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'finance.php' : 'index.php'; ?>" class="btn btn-info">Finance</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'scifi.php' : 'index.php'; ?>" class="btn btn-danger">SciFi</a> -->
                                            <?php
                                                $categories = getCategories($connection);
                                                $colors = array("green", "blue", "red", "orange"); // Define an array of colors for categories
                                                $index = 0; // Index to keep track of color array

                                                foreach ($categories as $categoryId => $category) {
                                                    $color = $colors[$index % count($colors)]; // Get color based on index, cycling through available colors
                                                    echo '<a href="listofBooks.php?category=' . urlencode($categoryId) . '" class="btn btn-custom" style="background-color: ' . $color . '; color: white;">' . $category . '</a>';
                                                    $index++; // Move to the next color for the next category
                                                }
                                            ?>

                                        </div>
                                    </div>
                        </div>

                        <div class="carousel-item">
                                <img src="carousel/7.jpeg" class="d-block w-100" alt="...">
                                <div class="overlay"></div>
                                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                                        <h1>Free</h1>
                                        <p>Our website is 100% free to use</p>
                                        <div class="button-container">
                                            <!-- (COMPONENTS-5)Buttons -->
                                            <!-- <a href="<?php echo isset($_SESSION['id']) ? 'fantasy.php' : 'index.php'; ?>" class="btn btn-primary">Fantasy</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'horror.php' : 'index.php'; ?>" class="btn btn-secondary">Horror</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'adventure.php' : 'index.php'; ?>" class="btn btn-success">Adventure</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'programming.php' : 'index.php'; ?>" class="btn btn-danger">Programming</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'novel.php' : 'index.php'; ?>" class="btn btn-warning">Novel</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'finance.php' : 'index.php'; ?>" class="btn btn-info">Finance</a>
                                            <a href="<?php echo isset($_SESSION['id']) ? 'scifi.php' : 'index.php'; ?>" class="btn btn-danger">SciFi</a> -->
                                            <?php
                                                $categories = getCategories($connection);
                                                $colors = array("green", "blue", "red", "orange"); // Define an array of colors for categories
                                                $index = 0; // Index to keep track of color array

                                                foreach ($categories as $categoryId => $category) {
                                                    $color = $colors[$index % count($colors)]; // Get color based on index, cycling through available colors
                                                    echo '<a href="listofBooks.php?category=' . urlencode($categoryId) . '" class="btn btn-custom" style="background-color: ' . $color . '; color: white;">' . $category . '</a>';
                                                    $index++; // Move to the next color for the next category
                                                }
                                            ?>
                                        </div>
                                    </div>
                        </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>


        <!-- (COMPONENTS-4)breadcrumb start-->
        <!-- <div class="container-fluid my-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="about.php">About</a></li>
                    <li class="breadcrumb-item"><a href="https://drive.google.com/drive/u/3/folders/1__CTIwLvJOsouCck5AxuGNMANst8zxNF" target="_blank">Engineering Books</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Updates<span class="badge rounded-pill text-bg-danger">Unavailable</span></li>
                </ol>
            </nav>
        </div> -->
        <!-- breadcrumb end-->

    



    <!-- List of Books -->
            <div class="container my-5">
                <h1><center>Recently Added<center></h1>

                    <div class="book-container">
                            
                            <div class="row justify-content-center" id="bookList">
                                <ul>
                                    <?php
                                    $conn = mysqli_connect("localhost", "root", "", "pdfupload");

                                    if (!$conn) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }
                        
                                    if (isset($_POST['search_query'])) {
                                        $searchQuery = $_POST['search_query'];
                        
                                        $sql = "SELECT * FROM images WHERE (book_name LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%') ORDER BY date_added DESC LIMIT 6;";
                                    } else {
                                        $sql = "SELECT * FROM images ORDER BY date_added DESC LIMIT 6;";
                                    }
                        
                                    $result = mysqli_query($conn, $sql);
                        
                                    if (!$result) {
                                        die("Query failed: " . mysqli_error($conn));
                                    }
                                    
                                    $count = 0;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $pdfFilePath = 'admin/pdf/' . $row['pdf'];
                                        $bookCoverPath = 'admin/book_covers/' . $row['book_cover'];

                                        echo '<li class="booking-card" style="background-image: url(' . $bookCoverPath . ');">';
                                        
                                        echo '<div class="book-container">';
                                            echo '<div class="content">';
                                            echo '<a href="' . $pdfFilePath . '" class="btn">Read Online</a>';
                                            echo '</div>';
                                            
                                            

                                        echo '</div>';

                                        echo '<div class="informations-container">';
                                        echo '<h2 class="title" style="color: #82c7f4;">' . $row['book_name'] . '</h2>';
                                        echo '<p class="sub-title" style="color: #82c7f4;">Author: ' . $row['author_name'] . '</p>';
                                        echo '<div class="more-information">';
                                        echo '<div class="info-and-date-container">';
                                        echo '<div class="box date">';
                                        echo '<p style="color: #82c7f4;">Published Date: ' . $row['published_date'] . '</p>'; // Assuming you have a published_date column in your database
                                        echo '</div>';
                                        echo '</div>';
                                        
                                    

                                        echo '<br>';
                                            echo '<div class="content">';
                                            echo '<a href="downloads.php?book_id=' . $row['id'] . '&pdf=' . urlencode($row['pdf']) . '" class="btn" style="color: #D1CDC7;">Download</a>';
                                            echo '</div>';

                                        echo '</div>';
                                        echo '</div>';
                                        echo '</li>';

                                        $count++;
                                    }
                                    
                                    mysqli_close($conn);
                                    ?>
                                </ul>

                                <?php if ($count === 0): ?>
                                    <h2 class="text-center">No results found</h2>
                                <?php endif; ?>

                                

                                

                            </div>
                    </div>

            </div>
            </div>                                 
 
       
            <?php
                function getCategories($connection) {
                    $categories = array();
                    $query = "SELECT * FROM pdfupload.category";
                    $query_run = mysqli_query($connection, $query);
                
                    if (!$query_run) {
                        die("Query failed: " . mysqli_error($connection));
                    }
                
                    while ($row = mysqli_fetch_assoc($query_run)) {
                        $categories[$row['cat_id']] = $row['cat_name']; // Use cat_id as the key and cat_name as the value
                    }
                
                    return $categories;
                }
            ?>


    
 
    

<!-- Footer -->
<footer class="bg-dark text-center text-white">
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2023 Copyright: Notera Management System <br> All rights reserved.
        <a class="text-white" href="https://mdbootstrap.com/"></a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->
<!-- Add this script at the end of your HTML body -->
<script>
    // Function to show the login modal
    function showLoginModal() {
        $('#loginModal').modal('show');
    }
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
crossorigin="anonymous"></script>


</body>

</html>





