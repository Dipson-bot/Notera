<!DOCTYPE html>
<html lang="en">
<head>
    <title>Password Reset Success</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        // JavaScript function to redirect to index.php
        function redirectToIndex() {
            window.location.href = "index.php";
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Notera Management System</a>
        </div>
        <!-- Add your navigation links here -->
    </div>
</nav><br>

<div class="row justify-content-center">
    <div class="col-md-8">
        <center><h3><u>Password Reset Successful</u></h3></center>
        <div class="alert alert-success" role="alert">Your password has been successfully reset.</div>
        <p>You can now log in using your new password.</p>
        <button class="btn btn-primary" onclick="redirectToIndex()">OK</button>
    </div>
</div>
</body>
</html>
