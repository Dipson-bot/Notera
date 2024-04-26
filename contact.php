
<?php
session_start();
		// Check if the user is not logged in, redirect to index.php
    if (!isset($_SESSION['id'])) {
      header("Location: ../index.php");
      exit();
    }
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $desc = $_POST['desc'];

        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "contactus";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to insert data into the table
        $sql = "INSERT INTO contactus (name, email, concern, dt) VALUES ('$name', '$email', '$desc', current_timestamp())";

        // Execute SQL query
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>SUCCESS!</strong> Your entry has been submitted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>ERROR!</strong> We are facing some technical issues. Your entry was not submitted successfully! We regret the inconvenience caused!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }

        // Close the database connection
        $conn->close();
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


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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


    <?php
    //$_POST lai super global vaninxa yo taba set hunxa jaba hami kunai pani post paramter yo page
    // ma submit garxam
    if  ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $desc = $_POST['desc'];

        //Connecting to the Database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "contactus";

        // Create a connection 
        $conn = mysqli_connect($servername,$username,$password,$database);

            
        //Die if connection was not successful
        if(!$conn){
            die("Sorry we failed to connect: ".mysqli_connect_error());
        }
        else{
        
            // Submit these to database
            //sql query to be executed
            $sql = "INSERT INTO `contactus` (`name`, `email`, `concern`, `dt`) 
            VALUES ('$name', '$email', '$desc', current_timestamp())"; // primary key removed because it auto updates
            $result = mysqli_query($conn, $sql); //execution
            // Add a new trip to the Trip table in the database
                if($result){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>SUCCESS!</strong> Your entry has been submitted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }
                else{
                    //echo "the record was not inserted successfully beacuse of this error ---> ".mysqli_error($conn);
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>ERROR!</strong>We are facing some technical issues. Your entry was 
                    not submitted successfully! We regret the inconvenience caused!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    }
        }
        
    }
    
     
?>

    <div class="container mt-3">
        <h1>Contact Us for your concerns</h1>
        <form action ="/Notera/contact.php" method="post"> <!-- afaile afaima submit garaune i.e FORM.php ma submit -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp" value="<?php echo $_SESSION['name']; ?>" disabled>
        </div>

            <div class="mb-3">
               <label for="email" class="form-label">Email</label>
               <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" value="<?php echo $_SESSION['email']; ?>" disabled>
            </div>

    <div class="mb-3">
        <label for="desc" class="form-label">Description</label>
        <textarea class="form-control" name="desc" id="desc" cols="30" rows="10"></textarea>
        
    </div>
    
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>