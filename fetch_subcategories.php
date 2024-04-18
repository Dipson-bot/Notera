<?php
// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "pdfupload");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch subcategories based on the selected category ID
if (isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];

    // Prepare and execute SQL query to fetch subcategories
    $stmt = $conn->prepare("SELECT subcat_id, subcat_name FROM subcategory WHERE cat_id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch results into an associative array
    $subcategories = [];
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row;
    }

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Output the subcategories as JSON
    echo json_encode($subcategories);

    // Close the database connection
    $stmt->close();
} else {
    // Handle the case where no category ID is provided
    echo json_encode([]);
}

// Close the database connection
mysqli_close($conn);
?>
