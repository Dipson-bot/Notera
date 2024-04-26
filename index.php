<?php
session_start();

// Always show "Forgot Password?" link
$_SESSION['show_forgot_password'] = true;

if (isset($_SESSION['id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!doctype html>
<html lang="en">

    <head>
        <title>Index</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="styles/login.css"> 
        <!-- <link rel="stylesheet" href="styles/universe.css">  -->
        <!-- <script type="text/javascript" src="javascript/login.js"></script> -->

    </head>
        <style>
            .btn-secondary {
            background-color: #6c757d;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
        </style>

<body>

  <!-- <canvas id="demo-cnv"></canvas> -->

   

    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    
                        <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
                        
                        <form action="" method="post">
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log" />
                        <label for="reg-log"></label>
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Log In</h4>
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-style" placeholder="Email">
                                                <i class="input-icon uil uil-at"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" name="password" class="form-style" placeholder="Password">
                                                <i class="input-icon uil uil-lock-alt"></i>
                                            </div>
                                            <button type="submit" name="login" class="btn mt-4">Login</button>
                                            <?php
                                                if (isset($_SESSION['show_forgot_password']) && $_SESSION['show_forgot_password'] === true) {
                                                echo'<p class="mb-0 mt-4 text-center"><a href="forgot_password.php" class="link">Forgot your password?</a></p>';
                                                // echo'<p class="mb-0 mt-4 text-center"><a href="unregisteredpage.php" class="link">Explore as Guest</a></p>';
                                                }
                                            ?>
                                            </form>

                                        <?php
                                                if (isset($_POST['login'])) {
                                                    $connection = mysqli_connect("localhost", "root", "");
                                                    $db = mysqli_select_db($connection, "pdfupload");
                                                
                                                    // Check in the users table
                                                    $user_query = "SELECT * FROM lms.users WHERE email = '$_POST[email]'";
                                                    $user_query_run = mysqli_query($connection, $user_query);

                                                    if (mysqli_num_rows($user_query_run) > 0) {
                                                        $row = mysqli_fetch_assoc($user_query_run);
                                                        if (password_verify($_POST['password'], $row['password'])) {
                                                            $_SESSION['name'] = $row['name'];
                                                            $_SESSION['email'] = $row['email'];
                                                            $_SESSION['id'] = $row['id'];
                                                            $_SESSION['role'] = $row['role']; // Get the role from the database
                                                            if ($_SESSION['role'] === 'user') {
                                                                header("Location: user_dashboard.php");
                                                                exit();
                                                            } elseif ($_SESSION['role'] === 'admin') {
                                                                header("Location: admin/admin_dashboard.php");
                                                                exit();
                                                            }
                                                        } else {
                                                            $_SESSION['show_forgot_password'] = true; // Show "Forgot Password?" link
                                                            echo '<br><br><center><span class="alert-danger">Wrong Password !!</span></center>';
                                                        }
                                                    } else {
                                                        $_SESSION['show_forgot_password'] = true; // Show "Forgot Password?" link
                                                        echo '<br><br><center><span class="alert-danger">User not found !!</span></center>';
                                                    }

                                                }
                                            ?>



                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-3 pb-3">Sign Up</h4>
                                            <form action="" method="post">
                                            <div class="form-group">
                                                <input type="text" name="name" class="form-style" placeholder="Full Name" required>
                                                <i class="input-icon uil uil-user"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="email" name="email" class="form-style" placeholder="Email" required>
                                                <i class="input-icon uil uil-at"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" name="password" class="form-style" placeholder="Password" required>
                                                <i class="input-icon uil uil-lock-alt"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="text" name="mobile" class="form-style" placeholder="Mobile Number" required>
                                                <i class="input-icon uil uil-phone"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="text" name="address" class="form-style" placeholder="address" required>
                                                <i class="input-icon uil uil-home"></i>
                                            </div>
                                            <button type="submit" name="register" class="btn mt-4">Register</button>
                                            </form>
                                            <?php
                                                if (isset($_POST['register'])) {
                                                    $connection = mysqli_connect("localhost", "root", "");
                                                    $db = mysqli_select_db($connection, "lms");
                                                    $name = $_POST['name'];
                                                    $email = $_POST['email'];
                                                    $mobile = $_POST['mobile'];
                                                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

                                                    // Check if email or mobile already exist in the database
                                                    $check_email_query = "SELECT * FROM lms.users WHERE email='$email'";
                                                    $check_email_result = mysqli_query($connection, $check_email_query);

                                                    $check_mobile_query = "SELECT * FROM lms.users WHERE mobile='$mobile'";
                                                    $check_mobile_result = mysqli_query($connection, $check_mobile_query);
                                                    if (!preg_match("/^[a-zA-Z ]{3,}$/", $name)) {
                                                        echo '<script type="text/javascript">alert("Name must contain only letters and spaces and it must be at least 3 letters")</script>';
                                                    }  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                        echo '<script type="text/javascript">alert("Invalid email format. Please provide a valid email address with both \'@\' and \'.\'.")</script>';
                                                    }  elseif (strlen($_POST['password']) < 8) {
                                                        echo '<script type="text/javascript">alert("Password must be at least 8 characters long.")</script>';
                                                    } elseif (!preg_match("/^[0-9]{10}$/", $mobile) || empty($mobile)) {
                                                        echo '<script type="text/javascript">alert("Invalid mobile number. Please provide a 10-digit mobile number.")</script>';
                                                    } elseif (mysqli_num_rows($check_email_result) > 0) {
                                                        echo '<script type="text/javascript">alert("This email is already registered.")</script>';
                                                    } elseif (mysqli_num_rows($check_mobile_result) > 0) {
                                                        echo '<script type="text/javascript">alert("This mobile number is already registered.")</script>';
                                                    } else {
                                                        $query = "INSERT INTO users (name, email, password, mobile, address) VALUES ('$name', '$email', '$password', '$mobile', '$_POST[address]')";
                                                        $query_run = mysqli_query($connection, $query);
                                                        if ($query_run) {
                                                            echo '<script type="text/javascript">alert("Registration successful. You can now log in.")</script>';
                                                        } else {
                                                            echo '<script type="text/javascript">alert("Registration failed: ' . mysqli_error($connection) . '")</script>';
                                                        }
                                                        
                                                    }
                                                }
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div> <!--background ko div -->

</body>

</html>
