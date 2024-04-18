<?php
$conn = mysqli_connect("localhost", "root", "", "pdfupload");
$sql2 = "SELECT * FROM images WHERE 1";
$result2 = mysqli_query($conn, $sql2);

while($fetch = mysqli_fetch_assoc($result2)) {
    echo "<tr>";
    echo "<td>{$fetch['id']}</td>";
    echo "<td><img src='book_covers/{$fetch['book_cover']}' alt='Book Cover' style='max-width: 100px;'></td>";
    echo "<td>{$fetch['book_name']}</td>";
    echo "<td>{$fetch['author_name']}</td>";
    echo "<td>{$fetch['published_date']}</td>";
    echo "<td>";
    echo "<a href='pdf/{$fetch['pdf']}' target='_blank' class='btn btn-success'>View</a>";
    echo "<a href='#' class='btn btn-outline-danger delete-btn' data-id='{$fetch['id']}'>Delete</a>";
    echo "</td>";
    echo "</tr>";
}
?>
