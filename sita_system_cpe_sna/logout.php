<?php
// Start the session
session_start();

// Include the database connection file using an absolute path
require_once __DIR__ . '../database/db_connect.php'; // Corrected path

// Check if the database connection is successful
if (!$data) {
    die("Error: Failed to connect to the database.");
}

// Capture the username from the session
$username = $_SESSION['username'] ?? 'Unknown User'; // Use a default value if not set

// Log the logout activity
$activity = "User  logout";
$log_sql = "INSERT INTO activity_logs (user, action, timestamp) VALUES (?, ?, NOW())";

if ($stmt = $data->prepare($log_sql)) {
    $stmt->bind_param('ss', $username, $activity);
    $stmt->execute();
    $stmt->close();
}

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit;
?>