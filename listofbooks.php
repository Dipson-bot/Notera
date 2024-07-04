<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$lms_conn = mysqli_connect("localhost", "root", "", "lms");
if (!$lms_conn) {
    die("Connection to LMS database failed: " . mysqli_connect_error());
}

$search_query = "";
$min_rating = "";

if (isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];
    $min_rating = isset($_POST['min_rating']) ? (int)$_POST['min_rating'] : 0;
    
    $sql = "SELECT images.*, lms_users.name AS uploaded_by_name, lms_users.id AS uploaded_by_id, category.cat_name, subcategory.subcat_name, AVG(reviews.rating) AS avg_rating
            FROM images
            LEFT JOIN lms.users AS lms_users ON images.uploaded_by = lms_users.id
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            LEFT JOIN reviews ON images.id = reviews.book_id
            WHERE (images.book_name LIKE '%$search_query%'
            OR category.cat_name LIKE '%$search_query%'
            OR subcategory.subcat_name LIKE '%$search_query%'
            OR images.author_name LIKE '%$search_query%'
            OR lms_users.name LIKE '%$search_query%')
            GROUP BY images.id
            HAVING avg_rating >= $min_rating
            ORDER BY avg_rating DESC, images.date_added DESC";
} else if (isset($_GET['category'])) {
    $selectedCategory = urldecode($_GET['category']);
    $sql = "SELECT images.*, lms_users.name AS uploaded_by_name, lms_users.id AS uploaded_by_id, category.cat_name, subcategory.subcat_name, AVG(reviews.rating) AS avg_rating
            FROM images
            LEFT JOIN lms.users AS lms_users ON images.uploaded_by = lms_users.id
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            LEFT JOIN reviews ON images.id = reviews.book_id
            WHERE images.cat_id = '$selectedCategory'
            GROUP BY images.id
            ORDER BY avg_rating DESC, images.date_added DESC";
} else {
    $sql = "SELECT images.*, lms_users.name AS uploaded_by_name, lms_users.id AS uploaded_by_id, category.cat_name, subcategory.subcat_name, AVG(reviews.rating) AS avg_rating
            FROM images
            LEFT JOIN lms.users AS lms_users ON images.uploaded_by = lms_users.id
            LEFT JOIN category ON images.cat_id = category.cat_id
            LEFT JOIN subcategory ON images.subcat_id = subcategory.subcat_id
            LEFT JOIN reviews ON images.id = reviews.book_id
            GROUP BY images.id
            ORDER BY avg_rating DESC, images.date_added DESC";
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
    <title>List of Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
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
        .uploaded-by-link {
            color: #007bff; /* Blue color for links */
            text-decoration: none; /* Remove underline */
        }
        .uploaded-by-link:hover {
            text-decoration: underline; /* Underline on hover */
        }
        .btn-group-justified {
            display: flex;
            justify-content: space-between;
        }
        .btn-group-justified .btn {
            flex: 1;
            margin: 0 5px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container col-12 m-5">
    <div class="col-12 m-auto">
        <h2 class="text-center">List of Notes</h2>
        
        <div class="row mt-4">
            <?php while ($row = mysqli_fetch_assoc($result)) {
                $book_id = $row['id'];
                $average_rating = $row['avg_rating'];
            ?>
            <div class="col-md-6">
                <div class="book-tile">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="admin/book_covers/<?php echo htmlspecialchars($row['book_cover'] ?? ''); ?>" alt="Book Cover">
                        </div>
                        <div class="col-md-9">
                            <h4><?php echo htmlspecialchars($row['book_name'] ?? ''); ?></h4>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author_name'] ?? ''); ?></p>
                            <p><strong>Uploaded Date:</strong> <?php echo htmlspecialchars($row['published_date'] ?? ''); ?></p>
                            <p><strong>Semester:</strong> <?php echo htmlspecialchars($row['cat_name'] ?? ''); ?></p>
                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($row['subcat_name'] ?? ''); ?></p>
                            <p><strong>Uploaded By:</strong> <a href="view_uploader_profile.php?user_id=<?php echo htmlspecialchars($row['uploaded_by_id'] ?? ''); ?>" class="uploaded-by-link"><?php echo htmlspecialchars($row['uploaded_by_name'] ?? ''); ?></a></p>
                            <div class="book-rating">
                                <?php
                                if ($average_rating !== null) {
                                    $average_rating = round($average_rating, 1);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $average_rating) ? '★' : '☆';
                                    }
                                    echo " ($average_rating)";
                                } else {
                                    echo "No ratings yet";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group btn-group-justified mt-3" role="group" aria-label="Book Actions">
                                <a href="admin/pdf/<?php echo htmlspecialchars($row['pdf'] ?? ''); ?>" target="_blank" class="btn btn-primary">View</a>
                                <a href="downloads.php?book_id=<?php echo htmlspecialchars($row['id']); ?>&pdf=<?php echo urlencode($row['pdf'] ?? ''); ?>" class="btn btn-success">Download</a>
                                <a href="rate_review.php?book_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning">Rate</a>
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
