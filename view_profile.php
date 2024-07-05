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

// Fetch user details from the LMS database
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
$uploaded_books_query = "SELECT images.*, AVG(reviews.rating) AS avg_rating
                         FROM images
                         LEFT JOIN reviews ON images.id = reviews.book_id
                         WHERE images.uploaded_by = $user_id
                         GROUP BY images.id";
$uploaded_books_result = mysqli_query($pdfupload_connection, $uploaded_books_query);
if (!$uploaded_books_result) {
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
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .user-info {
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .user-info h5 {
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }
        .user-info .info-tile {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .user-info .info-tile h5 {
            margin-bottom: 5px;
            font-size: 16px;
        }
        .user-info .info-tile p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .book-tile {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 15px;
            transition: all 0.3s ease;
        }
        .book-tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
        .book-tile img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .book-details {
            margin-top: 10px;
        }
        .book-rating {
            font-size: 20px;
            color: #FFD700; /* Golden color for stars */
        }
        .btn-group {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .btn-group .btn {
            flex: 1;
            margin-right: 5px;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" class="rounded-circle" alt="Profile Picture">
            </div>
        </div>
        <div class="col-md-8">
            <div class="profile-details">
                <div class="user-info">
                    <h5 class="text-center">Profile Details</h5>
                    <div class="info-tile">
                        <h5>Name:</h5>
                        <p><?php echo htmlspecialchars($name); ?></p>
                    </div>
                    <div class="info-tile">
                        <h5>Email:</h5>
                        <p><?php echo htmlspecialchars($email); ?></p>
                    </div>
                    <div class="info-tile">
                        <h5>Mobile:</h5>
                        <p><?php echo htmlspecialchars($mobile); ?></p>
                    </div>
                    <div class="info-tile">
                        <h5>Address:</h5>
                        <p><?php echo htmlspecialchars($address); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h3 class="text-center mb-4">Notes Uploaded by <?php echo htmlspecialchars($name); ?></h3>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($uploaded_books_result)) { 
                $average_rating = $row['avg_rating'] !== null ? round($row['avg_rating'], 1) : 'No ratings yet'; 
            ?>
            <div class="col-md-6">
                <div class="book-tile">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="admin/book_covers/<?php echo htmlspecialchars($row['book_cover']); ?>" alt="Book Cover">
                        </div>
                        <div class="col-md-9">
                            <h4><?php echo htmlspecialchars($row['book_name']); ?></h4>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author_name']); ?></p>
                            <p><strong>Uploaded Date:</strong> <?php echo date('F j, Y', strtotime($row['date_added'])); ?></p>
                            <div class="book-rating">
                                <?php
                                if ($row['avg_rating'] !== null) {
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $average_rating) ? '★' : '☆';
                                    }
                                    echo " ($average_rating)";
                                } else {
                                    echo "No ratings yet";
                                }
                                ?>
                            </div>
                            <div class="btn-group" role="group" aria-label="Book Actions">
                                <a href="admin/pdf/<?php echo htmlspecialchars($row['pdf']); ?>" target="_blank" class="btn btn-primary">View</a>
                                <a href="edit_book.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_book.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
