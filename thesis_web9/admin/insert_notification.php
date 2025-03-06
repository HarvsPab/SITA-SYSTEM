// admin_send_notification.php
<?php
include '../database/db_connect.php'; // Include your database connection file

$userId = $_POST['user_id'];
$message = $_POST['message'];

// Insert the notification
$query = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
$stmt = $data->prepare($query);
$stmt->bind_param("is", $userId, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
?>