<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="user_dashboard.php">Notera</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
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
                    <a class="nav-link active" href="listofBooks.php">List of Notes</a>
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
                        <li><a class="dropdown-item" href="uploadpdf.php">Upload Notes</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" role="button" href="logout.php">Logout</a>
                </li>
            </ul>
            <!-- Search bar -->
            <form class="d-flex" action="listofBooks.php" method="post">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query" value="<?php echo isset($search_query) ? $search_query : ''; ?>">
                <select class="form-control me-2" name="min_rating">
                    <option value="">Select Minimum Rating</option>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
