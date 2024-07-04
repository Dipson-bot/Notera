<?php
	session_start();
	//HandlNotera search
  $connection = mysqli_connect("localhost", "root", "", "pdfupload");
  if (!$connection) {
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

  $result = mysqli_query($connection, $sql);

  if (!$result) {
      die("Query failed: " . mysqli_error($connection));
  }
?>
<!DOCTYPE html>
<html ldata-darkreader-mode="dynamic" data-darkreader-scheme="dark">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
    body {
      
      margin: 0;
    }

    html {
      box-sizing: border-box;
    }

    *,
    *:before,
    *:after {
      box-sizing: inherit;
    }

    .column {
      float: left;
      width: 33.3%;
      margin-bottom: 16px;
      padding: 0 8px;
    }

    .card {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      margin: 8px;
    }

    .about-section {
      padding: 50px;
      text-align: center;
      background-color: #474e5d;
      color: white;
    }

    .dark-mode {
      background-color: rgb(0, 1, 0);
      color: white;
    }

    .container {
      padding: 0 16px;
    }

    .container::after,
    .row::after {
      content: "";
      clear: both;
      display: table;
    }

    .title {
      color: grey;
    }

    .button {
      border: none;
      outline: 0;
      display: inline-block;
      padding: 8px;
      color: white;
      background-color: #000;
      text-align: center;
      cursor: pointer;
      width: 100%;
    }

    .button:hover {
      background-color: #555;
    }

    @media screen and (max-width: 650px) {
      .column {
        width: 100%;
        display: block;
      }

    }
  </style>
  
</head>

<body>



<?php include 'navbar.php'; ?>
      <div class="container">
        <div class="about-section">
          <h1>Our Mission and Values</h1>
          <p>We understand the challenges faced by authors, publishers, and readers in 
            organizing and accessing their digital library, and we are here to simplify the process.</p>
          <p>At Notera Management System, we believe in fostering a vibrant community of authors,
            publishers, and readers. Through our platform, we aim to connect individuals who 
            share a passion for Notera, facilitating meaningful interactions, and promoting collaboration.</p>
            <p>Join us on this exciting journey of transforming the way Notera are managed and enjoyed. 
              Whether you are an author, publisher, or avid reader, Notera Management System is here to simplify 
              your digital publishing experience.</p>
        </div>

</div>

  <br><h2 style="text-align:center">Our Team</h2><br>
  <div class="row">


    <div class="column">
      <div class="card">
        
        <div class="container">
        <br>
          <h2 style="text-align:center">Raj Kumar Khadka</h2>
          <p class="title">Student</p>
          <p>Second year computer student at Nepal Engineering College</p>
          <p>rajkumarkd01@gmail.com</p>
          <p><a href="contact.php" button class="button" >Contact</a></p>
        </div>
      </div>
    </div>

    <div class="column">
      <div class="card">
      
        <div class="container">
        <br>
          <h2 style="text-align:center">Dipson Thapa</h2>
          <p class="title">Student</p>
          <p>Second year computer student at Nepal Engineering College</p>
          <p>dipsont020313@nec.edu.np</p>
          <p><a href="contact.php" button class="button" >Contact</a></p>
        </div>
      </div>
    </div>

    <div class="column">
      <div class="card">
        
        <div class="container">
        <br>
          <h2 style="text-align:center">Asim Sonju Shrestha</h2>
          <p class="title">Student</p>
          <p>Second year computer student at Nepal Engineering College</p>
          <p>asimsonju.az@gmail.com</p>
          <p><a href="contact.php" button class="button" >Contact</a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
</body>

</html>
