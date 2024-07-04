<?php
session_start();

// Database connection for pdfupload database
$conn_pdfupload = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$conn_pdfupload) {
    die("Connection to pdfupload database failed: " . mysqli_connect_error());
}

// Database connection for lms database
$conn_lms = mysqli_connect("localhost", "root", "", "lms");
if (!$conn_lms) {
    die("Connection to lms database failed: " . mysqli_connect_error());
}

// Fetch user details based on user_id from URL parameter
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query to fetch user details from lms.users table
    $query = "SELECT * FROM users WHERE id = $user_id";
    $query_run = mysqli_query($conn_lms, $query);

    if ($query_run) {
        $user_data = mysqli_fetch_assoc($query_run);
        if (!$user_data) {
            die("User not found");
        }
    } else {
        die("Query failed: " . mysqli_error($conn_lms));
    }
} else {
    die("User ID parameter not provided");
}

// Query to fetch books uploaded by the user from pdfupload.images table
$sql = "SELECT * FROM images WHERE uploaded_by = $user_id";
$result = mysqli_query($conn_pdfupload, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn_pdfupload));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - <?php echo htmlspecialchars($user_data['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-picture {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-picture img {
            max-width: 200px;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
            font-size: 16px;
            color: #FFD700; /* Golden color for stars */
        }
        .btn-group {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="profile-container">
    <div class="profile-picture">
        <img src="<?php echo !empty($user_data['profile_picture']) ? htmlspecialchars($user_data['profile_picture']) : 'uploads/path_to_default_image.jpg'; ?>" class="rounded-circle" alt="Profile Picture">
    </div>
    <div class="user-info">
        <h3 class="text-center mb-4"> <?php echo htmlspecialchars($user_data['name']); ?></h3>
        <h5>Email:</h5>
        <p><?php echo htmlspecialchars($user_data['email']); ?></p>
        <h5>Address:</h5>
        <p><?php echo htmlspecialchars($user_data['address']); ?></p>
    </div>

    <div class="mt-5">
        <h3 class="text-center mb-4">Notes Uploaded by <?php echo htmlspecialchars($user_data['name']); ?></h3>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-6">
                <div class="book-tile">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="admin/book_covers/<?php echo htmlspecialchars($row['book_cover']); ?>" alt="Book Cover">
                        </div>
                        <div class="col-md-9">
                            <h4><?php echo htmlspecialchars($row['book_name']); ?></h4>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author_name']); ?></p>
                            <p><strong>Uploaded Date:</strong> <?php echo htmlspecialchars($row['published_date']); ?></p>
                            <!-- Additional details as needed -->
                        </div>
                    </div>
                    <div class="btn-group mt-3" role="group" aria-label="Book Actions">
                        <a href="admin/pdf/<?php echo htmlspecialchars($row['pdf']); ?>" target="_blank" class="btn btn-primary">View</a>
                        <!-- Other actions as needed -->
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
