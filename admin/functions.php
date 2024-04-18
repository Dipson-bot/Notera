<?php
	function get_author_count(){
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,"lms");
		$author_count = 0;
		$query = "select count(*) as author_count from authors";
		$query_run = mysqli_query($connection,$query);
		while ($row = mysqli_fetch_assoc($query_run)){
			$author_count = $row['author_count'];
		}
		return($author_count);
	}

	function get_user_count(){
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,"lms");
		$user_count = 0;
		$query = "select count(*) as user_count from users where role <> 'admin'";
		$query_run = mysqli_query($connection,$query);
		while ($row = mysqli_fetch_assoc($query_run)){
			$user_count = $row['user_count'];
		}
		return($user_count);
	}

	// functions.php
	function get_book_count() {
		$conn = mysqli_connect("localhost", "root", "", "pdfupload");

		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}

		$sql = "SELECT COUNT(*) AS book_count FROM images";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			die("Query failed: " . mysqli_error($conn));
		}

		$row = mysqli_fetch_assoc($result);
		return $row['book_count'];
	}


	function get_issue_book_count(){
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,"lms");
		$issue_book_count = 0;
		$query = "select count(*) as issue_book_count from issued_Books";
		$query_run = mysqli_query($connection,$query);
		while ($row = mysqli_fetch_assoc($query_run)){
			$issue_book_count = $row['issue_book_count'];
		}
		return($issue_book_count);
	}

	function get_category_count(){
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,"pdfupload");
		$cat_count = 0;
		$query = "select count(*) as cat_count from category";
		$query_run = mysqli_query($connection,$query);
		while ($row = mysqli_fetch_assoc($query_run)){
			$cat_count = $row['cat_count'];
		}
		return($cat_count);
	}
	function get_downloads_count(){
		$connection = mysqli_connect("localhost", "root", "", "pdfupload");
		$user_id = $_SESSION['id'];
		$downloads_count = 0;
	
		// Check if user is logged in and user ID is set in the session
		if (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
			$query = "SELECT COUNT(*) as downloads_count FROM downloads WHERE user_id = $user_id";
			$query_run = mysqli_query($connection, $query);
	
			if ($query_run) {
				$row = mysqli_fetch_assoc($query_run);
				$downloads_count = $row['downloads_count'];
			} else {
				// Handle the case where the query fails
				// You might want to log the error or handle it accordingly
			}
		}
	
		return $downloads_count;
	}
	
	function get_downloaded_Books(){
		$connection = mysqli_connect("localhost", "root", "", "pdfupload");
	
		// Check if user is logged in and user ID is set in the session
		if (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
			$user_id = $_SESSION['id'];
			$Books = array();
			$query = "SELECT images.id, images.pdf, images.book_name, images.author_name, downloads.download_date 
					  FROM downloads 
					  INNER JOIN images ON downloads.book_id = images.id 
					  WHERE downloads.user_id = $user_id 
					  ORDER BY downloads.download_date DESC";
			$query_run = mysqli_query($connection, $query);
			while ($row = mysqli_fetch_assoc($query_run)) {
				$Books[] = $row;
			}
			return $Books;
		} else {
			// Handle the case where the user is not logged in or session variable is not set
			return array(); // Return an empty array or handle the situation accordingly
		}
	}
	
	
?>