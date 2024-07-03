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

if (isset($_GET['category'])) {
    $selectedCategory = urldecode($_GET['category']);
    $sql = "SELECT * FROM images WHERE cat_id = '$selectedCategory'";
} else {
    $sql = "SELECT * FROM images";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

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
    <title>List of Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .book-rating {
            font-size: 16px;
            color: #FFD700; /* Golden color for stars */
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<<<<<<< HEAD
<div class="container col-12 m-5">
=======
        <div class="container col-12 m-5">
>>>>>>> f41686a84eb411c07062c27ea52d4accd53e3a1e
    <div class="col-12 m-auto">
        <h2 class="text-center">List of Books</h2>
        <table class="table text-center">
            <thead>
                <tr>
                    <th>Book Cover</th>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Uploaded Date</th>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Action</th>
                    <th>Average Rating</th> <!-- New column for Average Rating -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    // Calculate average rating for the current book
                    $book_id = $row['id'];
                    $average_rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE book_id = $book_id";
                    $average_rating_result = mysqli_query($conn, $average_rating_sql);
                    $average_rating = mysqli_fetch_assoc($average_rating_result)['avg_rating'];
                ?>
                <tr>
                    <td><img src="admin/book_covers/<?php echo $row['book_cover'] ?>" alt="Book Cover" style="max-width: 100px;"></td>
                    <td><?php echo $row['book_name'] ?></td>
                    <td><?php echo $row['author_name'] ?></td>
                    <td><?php echo $row['published_date'] ?></td>
                    <td><?php echo $row['cat_name'] ?></td>
                    <td><?php echo $row['subcat_name'] ?></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Book Actions">
                            <a href="admin/pdf/<?php echo $row['pdf'] ?>" target="_blank" class="btn btn-primary">View</a>
                            <a href="downloads.php?book_id=<?php echo $row['id']; ?>&pdf=<?php echo urlencode($row['pdf']); ?>" class="btn btn-success">Download</a>
                            <a href="rate_review.php?book_id=<?php echo $row['id']; ?>" class="btn btn-warning">Rate</a>
                        </div>
                    </td>
                    <td>
                        <?php
                            if ($average_rating !== null) {
                                printf("%.1f / 5", $average_rating);
                            } else {
                                echo "No ratings yet";
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
