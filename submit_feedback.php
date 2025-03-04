<?php
// Database connection details
$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "campaign_feedback"; 

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $feedback = htmlspecialchars(trim($_POST['feedback']));
    $rating = intval($_POST['rating']); // Ensure rating is an integer

    // Validate form data (basic validation)
    if (empty($name) || empty($email) || empty($feedback) || empty($rating)) {
        die("All fields are required.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Validate rating (must be between 1 and 5)
    if ($rating < 1 || $rating > 5) {
        die("Invalid rating.");
    }

    // Prepare SQL query to insert data into the database
    $sql = "INSERT INTO feedback (name, email, feedback, rating) VALUES (?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters to the statement
    $stmt->bind_param("sssi", $name, $email, $feedback, $rating);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to a thank-you page
        header("Location: thank_you.html");
        exit();
    } else {
        // Log the error and display a user-friendly message
        error_log("Error submitting feedback: " . $stmt->error);
        echo "An error occurred while submitting your feedback. Please try again later.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the form was not submitted, show an error
    echo "Invalid request method.";
}
?>