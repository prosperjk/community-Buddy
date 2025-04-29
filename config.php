<?php
// Database configuration
$servername = "localhost";
$username = "root";      // Default XAMPP username
$password = "";          // Default XAMPP password
$dbname = "community_help";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Clean user input to prevent SQL injection
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Check if user is logged in (simplified version without sessions)
function is_logged_in() {
    // Without sessions, we'll return false by default
    // This will be used to show/hide login/register links
    return false;
}

// Display message to user (modified to use GET parameters instead of session)
function display_message() {
    if (isset($_GET['message']) && isset($_GET['type'])) {
        echo '<div class="' . $_GET['type'] . '-message">' . $_GET['message'] . '</div>';
    }
}

// Function to create message parameters for URLs
function create_message_params($message, $type = 'success') {
    return "message=" . urlencode($message) . "&type=" . urlencode($type);
}
?>