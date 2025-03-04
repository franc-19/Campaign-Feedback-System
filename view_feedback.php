<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campaign_feedback";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination logic
$records_per_page = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Retrieve total number of records
$total_records = $conn->query("SELECT COUNT(*) as total FROM feedback")->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Retrieve data for the current page
$sql = "SELECT * FROM feedback LIMIT $offset, $records_per_page";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Submission Date</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["feedback"] . "</td>
                <td>" . $row["rating"] . "</td>
                <td>" . $row["submission_date"] . "</td>
              </tr>";
    }
    echo "</table>";

    // Pagination links
    echo "<div>";
    if ($page > 1) {
        echo "<a href='view_feedback.php?page=" . ($page - 1) . "'>Previous</a> ";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='view_feedback.php?page=$i'>$i</a> ";
    }
    if ($page < $total_pages) {
        echo "<a href='view_feedback.php?page=" . ($page + 1) . "'>Next</a>";
    }
    echo "</div>";
} else {
    echo "No feedback found.";
}

$conn->close();
?>