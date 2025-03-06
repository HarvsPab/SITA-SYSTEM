// get_unread_count.php
<?php
session_start();
include('../database/db_connect.php'); // Include your database connection script

// Fetch unread notifications count for both users and admins
$unread_notifications_count_sql = "
    SELECT COUNT(*) AS unread_count 
    FROM notifications 
    WHERE is_read = 0
";
$unread_notifications_count_result = mysqli_query($data, $unread_notifications_count_sql);
$unread_notifications_count_row = mysqli_fetch_assoc($unread_notifications_count_result);
$unread_notifications_count = $unread_notifications_count_row ? $unread_notifications_count_row['unread_count'] : 0;

// Return the count as JSON
echo json_encode(['unread_count' => $unread_notifications_count]);
?>