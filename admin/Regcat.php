<?php
	session_start();
			// Check if the user is not logged in, redirect to index.php
			if (!isset($_SESSION['id'])) {
				header("Location: ../index.php");
				exit();
			}
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"pdfupload");
	$query = "select * from category";
	$cat_name = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Book's Category</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>	
	<style>
        .center-table {
            margin: 0 auto;
            float: none;
        }
    </style>
</head>
<body>
<?php include 'admin_navbar.php'; ?>


		<center><h4>Registered Book's Category</h4><br></center>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form>
					<table class="table-bordered center-table" width="300px" style="text-align: center">
						<tr>
							<th>Category Name</th>
						</tr>
				
					<?php
						$query_run = mysqli_query($connection,$query);
						while ($row = mysqli_fetch_assoc($query_run)){
							?>
							<tr>
							<td><?php echo $row['cat_name'];?></td>
						</tr>

					<?php
						}
					?>	
				</table>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
</body>
</html>
