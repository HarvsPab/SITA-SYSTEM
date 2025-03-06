<?php
session_start();
include('../database/db_connect.php'); // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reset all unread notifications to "read"
    $reset_query = "UPDATE notifications SET is_read = 1";
    if (mysqli_query($data, $reset_query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($data)]);
    }
    exit();
}
?>