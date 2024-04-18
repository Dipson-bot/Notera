<?php
    // delete_contact.php

    if(isset($_POST['sno'])) {
        $sno = $_POST['sno'];

        // Establish a connection to your database (similar to your other PHP files)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "contacts";

        $conn = mysqli_connect($servername, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Perform deletion logic using $sno
        $query = "DELETE FROM `contactus` WHERE `sno` = $sno";
        $result = mysqli_query($conn, $query);

        // Check if deletion was successful
        if($result) {
            echo "Contact deleted successfully!";
        } else {
            echo "Error: Unable to delete contact.";
        }

        // Close the database connection
        mysqli_close($conn);
    } else {
        echo "Invalid request!";
    }
?>
