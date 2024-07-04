<?php
session_start();

// Fetch data from 'lms' database
$lms_connection = mysqli_connect("localhost", "root", "", "lms");
if (!$lms_connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$name = "";
$email = "";
$mobile = "";
$address = "";
$profilePicture = ""; // Initialize profile picture variable

$query = "SELECT * FROM users WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($lms_connection, $query);
if ($row = mysqli_fetch_assoc($query_run)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $address = $row['address'];
    $profilePicture = isset($row['profile_picture']) && !empty($row['profile_picture']) ? $row['profile_picture'] : 'uploads/path_to_default_image.jpg'; // Ensure profile_picture exists
}

// Handle 'pdfupload' database
$pdfupload_connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$pdfupload_connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch user_id from session email
$user_query = "SELECT id FROM users WHERE email = '$_SESSION[email]'";
$user_result = mysqli_query($lms_connection, $user_query);
if ($user_row = mysqli_fetch_assoc($user_result)) {
    $user_id = $user_row['id'];
} else {
    die("User not found");
}

// Fetch uploaded books by the user based on 'uploaded_by' column
$uploaded_books_query = "SELECT images.book_name, images.author_name, images.date_added
                         FROM images
                         WHERE images.uploaded_by = $user_id";
$uploaded_books_result = mysqli_query($pdfupload_connection, $uploaded_books_query);
if (!$uploaded_books_result) {
    die("Query failed: " . mysqli_error($pdfupload_connection));
}

// Fetch downloaded books by the user
$downloaded_books_query = "SELECT images.book_name, images.author_name, downloads.download_date
                           FROM downloads
                           JOIN images ON downloads.book_id = images.id
                           WHERE downloads.user_id = $user_id";
$downloaded_books_result = mysqli_query($pdfupload_connection, $downloaded_books_query);
if (!$downloaded_books_result) {
    die("Query failed: " . mysqli_error($pdfupload_connection));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .profile-details {
            margin-bottom: 20px;
        }
        .profile-picture {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-picture img {
            max-width: 300px;
            border-radius: 50%;
            border: 5px solid #fff; /* Add a white border around the image */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Add a slight shadow for better contrast */
        }
        .user-info {
            padding: 20px;
            background-color: #f8f9fa; /* Light gray background */
            border: 1px solid #ddd; /* Light gray border */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Soft shadow */
            margin-bottom: 20px;
        }
        .user-info h5 {
            margin-bottom: 10px;
            font-weight: bold; /* Make text bold */
            color: #333; /* Darken text color */
        }
        .user-info .info-tile {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff; /* White background for tiles */
            border: 1px solid #ccc; /* Light gray border */
            border-radius: 6px; /* Rounded corners */
            box-shadow: 0 0 5px rgba(0,0,0,0.1); /* Soft shadow */
        }
        .user-info .info-tile h5 {
            margin-bottom: 5px;
            font-size: 16px; /* Font size for tile headings */
        }
        .user-info .info-tile p {
            margin: 0;
            font-size: 14px; /* Font size for tile content */
            color: #666; /* Dark gray text color */
        }
        .table-container {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-picture">
                <img src="<?php echo $profilePicture; ?>" class="rounded-circle" alt="Profile Picture">
            </div>
        </div>
        <div class="col-md-8">
            <div class="profile-details">
                <div class="user-info">
                <h5 class="text-center">Profile Details</h5>
                    <div class="info-tile">
                        <h5>Name:</h5>
                        <p><?php echo $name; ?></p>
                    </div>
                    <div class="info-tile">
                        <h5>Email:</h5>
                        <p><?php echo $email; ?></p>
                    </div>
                    <div class="info-tile">
                        <h5>Mobile:</h5>
                        <p><?php echo $mobile; ?></p>
                    </div>
                    <div class="info-tile">
                        <h5>Address:</h5>
                        <p><?php echo $address; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="text-center">Uploaded Books</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Book Name</th>
                            <th>Author</th>
                            <th>Uploaded Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($uploaded_books_result)) {
                            echo '<tr>';
                            echo '<td>' . $row['book_name'] . '</td>';
                            echo '<td>' . $row['author_name'] . '</td>';
                            echo '<td>' . $row['date_added'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="table-container">
                <h5 class="text-center">Downloaded Books</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Book Name</th>
                            <th>Author</th>
                            <th>Downloaded Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($downloaded_books_result)) {
                            echo '<tr>';
                            echo '<td>' . $row['book_name'] . '</td>';
                            echo '<td>' . $row['author_name'] . '</td>';
                            echo '<td>' . $row['download_date'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
