<?php
session_start();

// Check if the user is not logged in, redirect to index.php
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
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
    $stmt = $conn->prepare("INSERT INTO contactus (name, email, concern, dt) VALUES (?, ?, ?, current_timestamp())");
    $stmt->bind_param("sss", $name, $email, $desc);

    // Execute SQL query
    if ($stmt->execute()) {
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

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Handle Notera search
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
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #343a40;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h1>Contact Us for your concerns</h1>
    <form action="contact.php" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" disabled>
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
