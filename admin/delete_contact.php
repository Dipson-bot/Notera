<?php
    // delete_contact.php

    if(isset($_POST['sno'])) {
        $sno = $_POST['sno'];

        // Establish a connection to your database (similar to your other PHP files)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "pdfupload"; // Ensure the database name is correct

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            http_response_code(500);
            echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
            exit();
        }

        // Prepare and bind
        $stmt = $conn->prepare("DELETE FROM `contactus` WHERE `sno` = ?");
        $stmt->bind_param("i", $sno);

        // Execute the statement
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Contact deleted successfully!"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error: Unable to delete contact."]);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid request!"]);
    }
?>
