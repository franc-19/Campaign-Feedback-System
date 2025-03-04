<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default username for XAMPP/WAMP
$password = ""; // Default password for XAMPP/WAMP
$dbname = "campaign_feedback";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate form inputs
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $feedback = trim($_POST['feedback']);
    $rating = trim($_POST['rating']);

    // Basic validation
    if (empty($name) || empty($email) || empty($feedback) || empty($rating)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        die("Rating must be a number between 1 and 5.");
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, feedback, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $feedback, $rating);

    if ($stmt->execute()) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>
