<?php
// Database connection details
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "campaign_feedback"; // Replace with your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination logic
$records_per_page = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch total number of records
$total_records = $conn->query("SELECT COUNT(*) as total FROM feedback")->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch data for the current page
$sql = "SELECT * FROM feedback ORDER BY submission_date DESC LIMIT $offset, $records_per_page";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #28a745;
            color: #fff;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .rating {
            color: #ffcc00;
            font-weight: bold;
        }
        .no-feedback {
            text-align: center;
            color: #777;
            font-style: italic;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            text-decoration: none;
            color: #28a745;
            border: 1px solid #ddd;
            margin: 0 4px;
        }
        .pagination a.active {
            background-color: #28a745;
            color: #fff;
            border: 1px solid #28a745;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h1>Feedback Submissions</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Feedback</th>
                    <th>Rating</th>
                    <th>Submission Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['feedback']); ?></td>
                        <td class="rating"><?php echo $row['rating']; ?> ★</td>
                        <td><?php echo $row['submission_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="view_feedback.php?page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="view_feedback.php?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="view_feedback.php?page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="no-feedback">No feedback submissions found.</p>
    <?php endif; ?>
</body>
</html>