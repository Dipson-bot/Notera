<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

$connection = mysqli_connect("localhost", "root", "", "pdfupload");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    $updateQuery = "UPDATE users SET role = 'admin' WHERE id = $userId";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        $_SESSION['success_message'] = "User has been converted to admin.";
    } else {
        $_SESSION['error_message'] = "Failed to convert user to admin.";
    }

    header("Location: Regusers.php");
    exit();
}

$queryAdmin = "SELECT * FROM users WHERE role = 'admin'";
$queryUser = "SELECT * FROM users WHERE role = 'user'";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>All Reg Users</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
</head>
<body>
<?php include 'admin_navbar.php'; ?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <center><h4>Admins</h4></center>
            <table class="table table-bordered" style="text-align: center">
                <!-- Admin table headers -->
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
                <?php
                $query_run = mysqli_query($connection, $queryAdmin);
                if (!$query_run) {
                    echo "<tr><td colspan='4'>Failed to retrieve admins: " . mysqli_error($connection) . "</td></tr>";
                } else {
                    while ($row = mysqli_fetch_assoc($query_run)) {
                        $name = $row['name'];
                        $email = $row['email'];
                        $mobile = $row['mobile'];
                        $address = $row['address'];
                        echo "<tr><td>$name</td><td>$email</td><td>$mobile</td><td>$address</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <center><h4>Users</h4></center>
            <table class="table table-bordered" style="text-align: center">
                <!-- User table headers -->
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
                <?php
                $query_run = mysqli_query($connection, $queryUser);
                if (!$query_run) {
                    echo "<tr><td colspan='5'>Failed to retrieve users: " . mysqli_error($connection) . "</td></tr>";
                } else {
                    if (mysqli_num_rows($query_run) == 0) {
                        echo "<tr><td colspan='5'>No users found.</td></tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            $userId = $row['id'];
                            $name = $row['name'];
                            $email = $row['email'];
                            $mobile = $row['mobile'];
                            $address = $row['address'];
                            echo "<tr><td>$name</td><td>$email</td><td>$mobile</td><td>$address</td>
                                  <td>
                                      <form method='post'>
                                          <input type='hidden' name='user_id' value='$userId'>
                                          <button type='submit' class='btn btn-primary'>Convert to Admin</button>
                                      </form>
                                  </td>
                                  </tr>";
                        }
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>

<!-- Add this HTML code for the modal at the end of the body tag -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (isset($_SESSION['success_message'])) {
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Add this JavaScript code to handle the modal
    $(document).ready(function(){
        <?php
        if(isset($_SESSION['success_message'])) {
        ?>
            // Show the success modal if there is a success message
            $('#successModal').modal('show');
        <?php
        }
        ?>
    });
</script>

</body>
</html>
