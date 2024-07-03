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

$query = "SELECT * FROM users WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($lms_connection, $query);
while ($row = mysqli_fetch_assoc($query_run)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $address = $row['address'];
}

// Handle Notera search
$pdfupload_connection = mysqli_connect("localhost", "root", "", "pdfupload");
if (!$pdfupload_connection) {
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

$result = mysqli_query($pdfupload_connection, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($pdfupload_connection));
}

// Fetch user_id from session email
$user_query = "SELECT id FROM users WHERE email = '$_SESSION[email]'";
$user_result = mysqli_query($lms_connection, $user_query);
if ($user_row = mysqli_fetch_assoc($user_result)) {
    $user_id = $user_row['id'];
} else {
    die("User not found");
}

// Fetch uploaded books
$uploaded_books_query = "SELECT images.book_name, images.author_name, images.date_added
                         FROM images
                         WHERE images.id IN (SELECT book_id FROM downloads WHERE user_id = $user_id)";
$uploaded_books_result = mysqli_query($pdfupload_connection, $uploaded_books_query);
if (!$uploaded_books_result) {
    die("Query failed: " . mysqli_error($pdfupload_connection));
}

// Fetch downloaded books
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
<html>
<head>
    <title>View Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .container {
            margin-top: 20px;
        }
        .table-container {
            margin-bottom: 20px;
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
        <div class="col-md-12">
            <h5 class="text-center">Profile Details</h5>
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" value="<?php echo $name; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" value="<?php echo $email; ?>" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">Mobile:</label>
                            <input type="text" value="<?php echo $mobile; ?>" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" value="<?php echo $address; ?>" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

	</br>

    <div class="row">
        <div class="col-md-6">
            <div class="table-container">
			</br><h5 class="text-center">Uploaded Books</h5></br>
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

		</br>
        <div class="col-md-6">
            <div class="table-container">
					</br><h5 class="text-center">Downloaded Books</h5></br>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

