
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container-fluid">
		<a class="navbar-brand" href="user_dashboard.php">Notera</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<?php
					// Check if the user is an admin
					if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
						echo '<li class="nav-item">';
						echo '<a class="nav-link active" href="admin/admin_dashboard.php">Admin</a>';
						echo '</li>';
					}
				?>
				<li class="nav-item">
					<a class="nav-link active" href="listofBooks.php">List of Books</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="contact.php">Contact Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="aboutus.php">About Us</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">My Profile</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
						<li class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
						<li class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
						<li class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="uploadpdf.php">Upload Books</a></li>
					</ul>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="button" href="logout.php">Logout</a>
				</li>
			</ul>
			<!-- Search bar -->
			<form class="d-flex" action="listofBooks.php" method="post">
				<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query" value="<?php echo isset($search_query) ? $search_query : ''; ?>">
				<button class="btn btn-outline-success" type="submit">Search</button>
			</form>
		</div>
	</div>
</nav>
