<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "pdfupload");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['new_name'];
    $newMobile = $_POST['new_mobile'];
    $newAddress = $_POST['new_address'];

    // Validate inputs
    $errors = [];

    // Validate Name: should start with letters, and only contain letters, spaces, or apostrophes
    if (!preg_match("/^[a-zA-Z]+(?:[\s'.-][a-zA-Z]+)*$/", $newName)) {
        $errors[] = "Name should start with letters and can contain spaces, apostrophes, dots, or dashes.";
    }

    // Validate Mobile: should be exactly 10 digits
    if (!preg_match("/^[0-9]{10}$/", $newMobile)) {
        $errors[] = "Mobile number should be exactly 10 digits.";
    }

    // If there are validation errors, display them and prevent further processing
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<script type="text/javascript">alert("' . $error . '");</script>';
        }
    } else {
        // Handle profile picture upload
        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $fileName = $_FILES['profile_picture']['name'];
            $fileSize = $_FILES['profile_picture']['size'];
            $fileType = $_FILES['profile_picture']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Validate file type
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($fileExtension, $allowedExtensions)) {
                // Directory where the uploaded files will be saved
                $uploadDir = 'uploads/';
                $uploadPath = $uploadDir . basename($fileName);

                // Move the uploaded file to the specified directory
                if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                    // Update profile picture path in database using prepared statement
                    $updateQuery = "UPDATE users SET name = ?, mobile = ?, address = ?, profile_picture = ? WHERE email = ?";

                    $stmt = mysqli_prepare($connection, $updateQuery);
                    if ($stmt === false) {
                        die('MySQL prepare error: ' . mysqli_error($connection));
                    }

                    mysqli_stmt_bind_param($stmt, 'sssss', $newName, $newMobile, $newAddress, $uploadPath, $_SESSION['email']);
                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script type="text/javascript">alert("Changes have been successfully saved."); window.location.href = "view_profile.php";</script>';
                        exit();
                    } else {
                        echo "Error updating record: " . mysqli_stmt_error($stmt);
                    }
                } else {
                    echo '<script type="text/javascript">alert("There was an error uploading your file. Please try again.");</script>';
                }
            } else {
                echo '<script type="text/javascript">alert("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");</script>';
            }
        } else {
            // Update profile information without uploading a new picture
            $updateQuery = "UPDATE users SET name = ?, mobile = ?, address = ? WHERE email = ?";

            $stmt = mysqli_prepare($connection, $updateQuery);
            if ($stmt === false) {
                die('MySQL prepare error: ' . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, 'ssss', $newName, $newMobile, $newAddress, $_SESSION['email']);
            if (mysqli_stmt_execute($stmt)) {
                echo '<script type="text/javascript">alert("Changes have been successfully saved."); window.location.href = "view_profile.php";</script>';
                exit();
            } else {
                echo "Error updating record: " . mysqli_stmt_error($stmt);
            }
        }
    }
}

$name = "";
$email = "";
$mobile = "";
$address = "";
$profilePicture = ""; // Initialize profile picture variable

$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 's', $_SESSION['email']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $address = $row['address'];
    $profilePicture = isset($row['profile_picture']) && !empty($row['profile_picture']) ? $row['profile_picture'] : 'uploads/path_to_default_image.jpg'; // Ensure profile_picture exists
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .profile-container {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-picture {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-picture img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="profile-container">
                <h4 class="text-center mb-4">Edit Profile</h4>
                <form method="post" enctype="multipart/form-data">
                    <div class="profile-picture">
                        <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="rounded-circle">
                    </div>
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control mt-3" name="profile_picture" id="profile_picture">
                    </div>
                    <div class="mb-3">
                        <label for="new_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="new_name" name="new_name" value="<?php echo $name; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="new_mobile" class="form-label">Mobile</label>
                        <input type="text" class="form-control" id="new_mobile" name="new_mobile" value="<?php echo $mobile; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="new_address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="new_address" name="new_address" value="<?php echo $address; ?>">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
