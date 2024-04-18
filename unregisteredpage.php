<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha384-Tn538w5Q/TN3bu5Fv1BAA7G0t5m6en5F5+P5FFPz9bO5kc2p5C5f5f5f5f5f5f5fs5F5+P5FFPz9bO5kc2p5C5f5f5f5f5f5f5f5F5+P5FFPz9bO5kc2p5C5f5f5f5f5f5f5f5F5+P5FFPz9bO5kc2p5C5f5f5f5f5f5f5" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        /* Add your custom CSS styles here */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Adjust transparency as needed */
        }

        .custom-caption {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg bg-dark border-bottom border-bottom-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="unregisteredpage.php">Notera</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                    <li class="nav-item">
                        <a class="nav-link active navbar-item" href="unregisteredpage.php">List of Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active navbar-item" href="unregisteredpage.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active navbar-item" href="unregisteredpage.php">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">My Profile</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item navbar-item" href="unregisteredpage.php">View Profile</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item navbar-item" href="unregisteredpage.php">Edit Profile</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item navbar-item" href="unregisteredpage.php">Change Password</a></li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link navbar-item" role="button" href="index.php">Login</a>
                    </li> -->
                    <li>
                        <a class="nav-link" role="button" href="index.php">Login</a>
                    </li>
                </ul>

                <!-- Search bar -->
                <form class="d-flex" action="index.php" method="post">
                    <input class="form-control me-2" type="search" placeholder="Search by Book Name" aria-label="Search" name="book_name">
                    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-success navbar-item">Search</a>
                    <!-- <button class="btn btn-outline-success" type="submit">Search</button> -->
                </form>
            </div>
        </div>
    </nav>

    <!-- Carousel Slider -->
    <div class="container-fill">
        <div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="1.jpeg" class="d-block w-100" alt="...">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-none d-md-block text-light custom-caption">
                        <h1>Welcome to E-Books</h1>
                        <p>Please login to use all features</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="2.jpeg" class="d-block w-100" alt="...">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-none d-md-block custom-caption">
                        <h1>Simple and Minimalistic</h1>
                        <p>With quick search and downloads</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="7.jpeg" class="d-block w-100" alt="...">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-none d-md-block text-light custom-caption">
                        <h1>Free</h1>
                        <p>Our website is 100% free to use</p>
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

    <div class="container-fluid my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">About</a></li>
                <li class="breadcrumb-item"><a href="unregisteredpage.php" target="_blank">Engineering Books</a></li>
                <li class="breadcrumb-item active" aria-current="page">Updates<span
                        class="badge rounded-pill text-bg-danger">Unavailable</span></li>
            </ol>
        </nav>
    </div>
    <!-- breadcrumb end-->

    <!-- (COMPONENTS-5)Buttons-->
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-primary navbar-item">Fantasy</a>
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-secondary navbar-item">Adventure</a>
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-success navbar-item">Horror</a>
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-danger navbar-item">Programming</a>
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-warning navbar-item">Thriller</a>
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-info navbar-item">Humor</a>
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-danger navbar-item">Biography</a>
    <a href="unregisteredpage.php" button type="button" class="btn btn-outline-dark navbar-item">Drama</a>
    <!--/Buttons-->

    <!-- List of Books -->
    <div class="container my-5">
        <h2 class="text-center">Recently Added Books</h2><br>
        <div class="row justify-content-center" id="bookList">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "pdfupload");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Select all records from the 'images' table, ordered by the date added in descending order
            $sql = "SELECT * FROM images
            ORDER BY date_added DESC
            LIMIT 4;"; // Order by date added
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            $count = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                $pdfFilePath = 'admin/pdf/' . $row['pdf'];
                $bookCoverPath = 'admin/book_covers/' . $row['book_cover'];

                echo '<div class="col-md-3 mb-4">';
                echo '<div class="card" style="width: 18rem;">';
                echo '<img src="' . $bookCoverPath . '" class="card-img-top" alt="Book Cover" style="height: 300px;">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['book_name'] . '</h5>';
                echo '<p class="card-text">Author: ' . $row['author_name'] . '</p>';
                echo '<p class="card-text">Published Date: ' . $row['published_date'] . '</p>';

                // Modified View PDF and Download PDF buttons to redirect to unregisteredpage.php
                echo '<a href="unregisteredpage.php" class="btn btn-primary navbar-item">View PDF</a>';
                echo '<a href="unregisteredpage.php" class="btn btn-success navbar-item">Download PDF</a>';

                echo '</div>';
                echo '</div>';
                echo '</div>';

                $count++;
            }

            mysqli_close($conn);
            ?>
        </div>

        <?php if ($count >= 4) : ?>
        <div class="text-center">
            <a href="unregisteredpage.php" class="btn btn-primary navbar-item">View More</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and jQuery scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
    // JavaScript function to show login message and redirect to the login page
    document.addEventListener("DOMContentLoaded", function () {
        const navbarItems = document.querySelectorAll(".navbar-item");

        navbarItems.forEach(function (item) {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                const confirmLogin = confirm("You need to login to use all the features.");
                if (confirmLogin) {
                    window.location.href = "index.php";
                }
                // No need to redirect if cancel is pressed
            });
        });
    });
</script>


    <!-- Footer -->
    <footer class="bg-dark text-center text-white">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2023 Copyright:
            <a class="text-white" href="https://mdbootstrap.com/"></a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->
</body>
</html>
