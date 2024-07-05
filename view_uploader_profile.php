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

    // Query to fetch user details from lms.users table using prepared statement
    $stmt = mysqli_prepare($conn_lms, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $user_data = mysqli_fetch_assoc($result)) {
        // User data fetched successfully
    } else {
        die("User not found or query failed: " . mysqli_error($conn_lms));
    }
} else {
    die("User ID parameter not provided");
}

// Pagination settings
$limit = 6; // Number of notes per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query to fetch books uploaded by the user from pdfupload.images table with pagination and average rating
$stmt = mysqli_prepare($conn_pdfupload, 
    "SELECT images.*, 
            (SELECT AVG(rating) 
             FROM reviews 
             WHERE reviews.book_id = images.id) as avg_rating 
     FROM images 
     WHERE uploaded_by = ? 
     LIMIT ?, ?");
mysqli_stmt_bind_param($stmt, "iii", $user_id, $start, $limit);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Query to count total notes uploaded by the user
$total_stmt = mysqli_prepare($conn_pdfupload, "SELECT COUNT(*) AS total FROM images WHERE uploaded_by = ?");
mysqli_stmt_bind_param($total_stmt, "i", $user_id);
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_notes = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_notes / $limit);
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
        .user-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .user-info h3 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .user-info p {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        .user-info p strong {
            color: #333;
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
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }
        .stars {
            display: inline-block;
        }
        .stars span {
            color: #FFD700; /* Golden color for stars */
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
        <h3><?php echo htmlspecialchars($user_data['name']); ?></h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($user_data['address']); ?></p>
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
                            <p><strong>Uploaded Date:</strong> <?php echo date('F j, Y', strtotime($row['published_date'])); ?></p>
                            <p><strong>Average Rating:</strong> 
                                <span class="book-rating">
                                    <?php 
                                    $avg_rating = $row['avg_rating'];
                                    if ($avg_rating) {
                                        // Display star rating
                                        $full_stars = floor($avg_rating);
                                        $half_star = ($avg_rating - $full_stars) >= 0.5 ? true : false;
                                        $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                                        
                                        for ($i = 0; $i < $full_stars; $i++) {
                                            echo '★';
                                        }
                                        if ($half_star) {
                                            echo '☆';
                                        }
                                        for ($i = 0; $i < $empty_stars; $i++) {
                                            echo '☆';
                                        }
                                    } else {
                                        echo "No ratings yet";
                                    }
                                    ?>
                                </span>
                                <br>
                                <span>
                                    <?php 
                                    if ($avg_rating) {
                                        echo "(" . number_format($avg_rating, 1) . " / 5)";
                                    }
                                    ?>
                                </span>
                            </p>
                            <!-- Additional details as needed -->
                        </div>
                    </div>
                    <div class="btn-group mt-3" role="group" aria-label="Book Actions">
                        <a href="admin/pdf/<?php echo htmlspecialchars($row['pdf']); ?>" target="_blank" class="btn btn-primary">View</a>
                        <a href="admin/pdf/<?php echo htmlspecialchars($row['pdf']); ?>" download class="btn btn-success">Download</a>
                        <!-- Other actions as needed -->
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Pagination Links -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($page<= 1){ echo '#'; } else { echo "?user_id=$user_id&page=" . ($page - 1); } ?>">Previous</a>
                </li>
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if($page == $i){ echo 'active'; } ?>">
                    <a class="page-link" href="?user_id=<?php echo $user_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?user_id=$user_id&page=" . ($page + 1); } ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
