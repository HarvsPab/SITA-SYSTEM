<?php
require_once "../database/db_connect.php";
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Get the current user's username from session
$username = $_SESSION['username'];

// Fetch unread notifications count
$unread_count = 0;
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$unread_query = $data->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = $user_id AND is_read = 0");
if ($unread_query->num_rows > 0) {
    $unread_row = $unread_query->fetch_assoc();
    $unread_count = $unread_row['unread_count'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notificationMessage = $_POST['notificationMessage'];

    // Insert notification into the database
    $stmt = $data->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $_SESSION['user_id'], $notificationMessage); // Assuming user_id is stored in session
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to prevent duplicate submission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch notifications from the database
$notifications = [];
$result = $data->query("SELECT username, message, timestamp FROM notifications n JOIN users u ON n.user_id = u.id ORDER BY timestamp DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}

$data->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admindashb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    :root {
        --background-color: #ffffff;
        --text-color: #000000;
        --sidebar-background: #1a73e8;
        --sidebar-text-color: #ffffff;
        --sidebar-hover-background: #1558b1;
        --notification-item-background: #f1f1f1;
        --notification-item-text: #333333;
        --textarea-background: #ffffff;
        --textarea-text: #000000;
        --textarea-border: #ccc;
        --header-background: #E8E9ED;
        --header-text-color: #333333;
    }

    .dark-mode {
        --background-color: #121212;
        --text-color: #e0e0e0;
        --sidebar-background: #1e1e1e; /* Sidebar remains unchanged */
        --sidebar-text-color: #ffffff;
        --sidebar-hover-background: #2c2c2c;
        --notification-item-background: #2d2d2d;
        --notification-item-text: #e0e0e0;
        --textarea-background: #2d2d2d;
        --textarea-text: #e0e0e0;
        --textarea-border: #444;
        --header-background: #121212;
        --header-text-color: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        display: flex;
        background-color: var(--background-color);
        color: var(--text-color);
        transition: background-color 0.3s, color 0.3s;
    }

    .dark-mode .main-content {
        background-color: var(--background-color);
    }

    .dark-mode .header {
        background-color: var(--header-background);
    }

    .dark-mode h1, .dark-mode h2 {
        color: var(--header-text-color) !important;
    }

    .sidebar {
        position: fixed; /* Make the sidebar fixed */
        top: 0; /* Align it to the top */
        left: 0; /* Align it to the left */
        width: 200px; /* Set a width for the sidebar */
        height: 100%; /* Make it full height */
        background-color: var(--sidebar-background);
        color: var(--sidebar-text-color);
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        overflow-y: auto; /* Allow scrolling if content overflows */
    }

    .main-content {
        margin-left: 220px;
        padding: 20px;
        width: 100%;
    }

    .header {
        margin-bottom: 20px;
        padding: 10px 0;
        background-color: var(--header-background);
    }

    .notification-form {
        margin: 20px 0;
    }

    .notification-form textarea {
        width: 100%;
        height: 100px;
        margin-bottom: 10px;
        padding: 10px;
        background-color: var(--textarea-background);
        color: var(--textarea-text);
        border: 1px solid var(--textarea-border);
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s, border 0.3s; /* Smooth transition */
    }

    .notification-form button {
        padding: 10px 15px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .notifications {
        margin-top: 20px;
    }

    .notifications ul {
        list-style-type: none;
        padding: 0;
    }

    .notifications li {
        background: var(--notification-item-background);
        color: var(--notification-item-text);
        margin: 5px 0;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
    }

    .theme-switch-wrapper {
        display: flex;
        align-items: center;
        position: absolute;
        right: 25px;
        top: 20px;
    }

    .theme-switch {
        display: inline-block;
        height: 34px;
        position: relative;
        width: 60px;
    }

    .theme-switch input {
        display: none;
    }

    .slider {
        background-color: #ccc;
        bottom: 0;
        cursor: pointer;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        transition: .4s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 5px;
    }

    .slider .fa-sun {
        color: #f39c12;
        font-size: 14px;
        margin-left: 4px;
    }

    .slider .fa-moon {
        color: #f1c40f;
        font-size: 14px;
        margin-right: 4px;
    }

    .slider:before {
        background-color: white;
        bottom: 4px;
        content: "";
        height: 26px;
        left: 4px;
        position: absolute;
        transition: .4s;
        width: 26px;
        z-index: 1;
    }

    input:checked + .slider {
        background-color: #2c3e50;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    .dark-mode .logged-in-text {
    color: var(--text-color) !important;
}

.logged-in-text {
    color: #333; /* Default light mode color */
}
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>S.I.T.A.</h2>
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="ordinance.php"><i class="fas fa-gavel"></i> Ordinances</a>
        <a href="violations.php"><i class="fas fa-exclamation-triangle"></i> Violations</a>
        <a href="report.php"><i class="fas fa-file-alt"></i> Reports</a>
        <a href="tracking.php"><i class="fas fa-map-marker-alt"></i> Tracking</a>
        <a href="#" class="logoutButton">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 style="color: #121212">Message Section</h1>
        </div>
        <p class="logged-in-text">Logged in as: <strong><?php echo htmlspecialchars($username); ?></strong></p>

        <div class="theme-switch-wrapper">
            <label class="theme-switch" for="checkbox">
                <input type="checkbox" id="checkbox" />
                <div class="slider round">
                    <i class="fas fa-sun"></i>
                    <i class="fas fa-moon"></i>
                </div>
            </label>
        </div>

        <div class="notification-form">
            <form id="notificationForm" method="POST" action="">
                <textarea id="notificationMessage" name="notificationMessage" placeholder="Type your notification here..." required></textarea>
                <button type="submit">Send Notification</button>
            </form>
        </div>

        <div class="notifications">
            <h2 style="color: #121212">Recent Notifications</h2>
            <ul id="notificationList">
                <?php foreach ($notifications as $notification): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($notification['username']); ?></strong> 
                        <em>(<?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($notification['timestamp']))); ?>)</em>: 
                        <?php echo htmlspecialchars($notification['message']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        const checkbox = document.getElementById('checkbox');

        // Check for saved user preference, if any, and apply it
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            checkbox.checked = true;
        }

        checkbox.addEventListener('change', () => {
            document.body.classList.toggle('dark-mode');
            // Save the user's preference in local storage
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Select the logout button
            let logoutButton = document.querySelector(".logoutButton");

            // Check if the button exists
            if (logoutButton) {
                logoutButton.addEventListener("click", function (event) {
                    event.preventDefault(); // Stop the default action

                    let confirmAction = confirm("Are you sure you want to log out?");
                    if (confirmAction) {
                        window.location.href = "../logout.php"; // Redirect to logout
                    }
                });
            }
        });
    </script>
</body>
</html>