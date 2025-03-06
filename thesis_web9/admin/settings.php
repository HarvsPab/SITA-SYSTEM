<?php
// Assuming you're connected to your database
include('../database/db_connect.php'); // Replace with your DB connection script
session_start(); // Start the session

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: /thesis_web/admin/settings.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the input values
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $rank = trim($_POST['rank']);
    $badge_no = trim($_POST['badge_no']);
    $password = $_POST['password'];

    // Validate input
    if (empty($name) || empty($username) || empty($rank) || empty($badge_no) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // First check if the username already exists
        $check_sql = "SELECT username FROM users WHERE username = ?";
        if ($stmt = $data->prepare($check_sql)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $error = "Username already exists. Please choose a different one.";
            } else {
                // The role is always 'user'
                $role = 'user';
                
                // Prepare the SQL query to insert the new user
                $sql = "INSERT INTO users (name, username, rank, badge_no, password, role) VALUES (?, ?, ?, ?, ?, ?)";
                
                if ($stmt = $data->prepare($sql)) {
                    // Bind the parameters ('ssssss' for 6 strings)
                    $stmt->bind_param('ssssss', $name, $username, $rank, $badge_no, $password, $role);
                    
                    // Execute the query
                    if ($stmt->execute()) {
                        $success = true;  // Flag for success
                        
                        // Log the user creation activity
                        $admin_username = $_SESSION['username'] ?? 'admin';
                        $activity = "Admin created new user: $username";
                        $log_sql = "INSERT INTO activity_logs (user, action, timestamp) VALUES (?, ?, NOW())";
                        
                        if ($log_stmt = $data->prepare($log_sql)) {
                            $log_stmt->bind_param('ss', $admin_username, $activity);
                            $log_stmt->execute();
                            $log_stmt->close();
                        }
                    } else {
                        $error = $stmt->error;  // Capture the error
                    }
                    
                    // Close the statement
                    $stmt->close();
                }
            }
        }
    }
}
?>

<?php
// Fetch unread notifications count for both users and admins
$unread_notifications_count_sql = "
SELECT COUNT(*) AS unread_count 
FROM notifications 
WHERE is_read = 0
";
$unread_notifications_count_result = mysqli_query($data, $unread_notifications_count_sql);
$unread_notifications_count_row = mysqli_fetch_assoc($unread_notifications_count_result);
$unread_notifications_count = $unread_notifications_count_row ? $unread_notifications_count_row['unread_count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admindashb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Base styles (light mode) */
        :root {
            --bg-color: #f0f2f5;
            --sidebar-bg: #1a73e8;
            --sidebar-hover: #34495e;
            --text-color: #34495e;
            --header-color: #2c3e50;
            --card-bg: #fff;
            --border-color: #ddd;
            --input-border: #ddd;
            --input-focus: #3498db;
            --button-bg: #3498db;
            --button-hover: #2980b9;
            --table-header-bg: #3498db;
            --table-header-text: #fff;
            --table-border: #ddd;
            --table-hover: #f4f7f6;
            --success-bg: #e8f5e9;
            --success-text: #2e7d32;
            --error-bg: #ffebee;
            --error-text: #c62828;
        }

  /* Dark mode styles */
.dark-theme {
    --bg-color: #121212; /* Deeper dark background */
    --sidebar-bg: #1a73e8; /* Keep sidebar color unchanged */
    --sidebar-hover: #374151;
    --text-color: #e0e0e0; /* Lighter text for better readability */
    --header-color: #ffffff; /* White headers */
    --card-bg: #1e1e1e; /* Dark card backgrounds */
    --border-color: #333333; /* Dark borders */
    --input-border: #404040;
    --input-focus: #4a90e2;
    --button-bg: #2c3e50;
    --button-hover: #34495e;
    --table-header-bg: #2c3e50;
    --table-header-text: #ffffff;
    --table-border: #333333;
    --table-hover: #2a2a2a;
    --success-bg: #1b5e20;
    --success-text: #4caf50;
    --error-bg: #b71c1c;
    --error-text: #ff5252;
}

/* When dark theme is active, apply deep dark backgrounds */
.dark-theme body {
    background-color: #121212 !important;
    color: #e0e0e0 !important;
}

.dark-theme .main-content {
    background-color: #121212 !important;
}

.dark-theme .header h1 {
    color: #ffffff !important;
}

.dark-theme .add-user-form, 
.dark-theme .activity-logs {
    background-color: #1e1e1e !important;
    border: 1px solid #333333;
}

.dark-theme .form-group label {
    color: #e0e0e0 !important;
}

.dark-theme .form-group input {
    background-color: #2a2a2a !important;
    color: #e0e0e0 !important;
    border-color: #404040 !important;
}

.dark-theme .form-group input:focus {
    border-color: #4a90e2 !important;
}

.dark-theme button[type="submit"] {
    background-color: #2c3e50 !important;
    color: #ffffff !important;
}

.dark-theme button[type="submit"]:hover {
    background-color: #34495e !important;
}

.dark-theme .notification-icon {
    color: #e0e0e0 !important;
}

.dark-theme table th {
    background-color: #2c3e50 !important;
    color: #ffffff !important;
}

.dark-theme table td {
    color: #e0e0e0 !important;
    border-bottom-color: #333333 !important;
}

.dark-theme table tr:hover {
    background-color: #2a2a2a !important;
}

        /* Apply variables to elements */
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            position: fixed; /* Make the sidebar fixed */
            top: 0; /* Align it to the top */
            left: 0; /* Align it to the left */
            height: 100%; /* Full height */
            width: 250px; /* Set a width for the sidebar */
            overflow-y: auto; /* Allow scrolling if content overflows */
            z-index: 1000; /* Ensure it stays above other content */
        }

        .main-content {
            margin-left: 250px; /* Same width as the sidebar */
            padding: 20px; /* Add some padding for aesthetics */
        }

        .sidebar a:hover {
            background-color: var(--sidebar-hover);
        }

        .header h1 {
            color: rgb(35, 38, 40);
        }

        .add-user-form, .activity-logs {
            background-color: var(--card-bg);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .add-user-form h2, .activity-logs h2 {
            color: var(--header-color);
        }

        .form-group label {
            color: var(--text-color);
        }

        .form-group input {
            border-color: var(--input-border);
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        .form-group input:focus {
            border-color: var(--input-focus);
        }

        .form-group small {
            color: var(--text-color);
            opacity: 0.7;
        }

        button[type="submit"] {
            background-color: var(--button-bg);
            color: white;
        }

        button[type="submit"]:hover {
            background-color: var(--button-hover);
        }

        .success-message {
            background-color: var(--success-bg);
            color: var(--success-text);
        }

        .error-message {
            background-color: var(--error-bg);
            color: var(--error-text);
        }

        table th {
            background-color: var(--table-header-bg);
            color: var(--table-header-text);
        }

        table td {
            border-bottom: 1px solid var(--table-border);
            color: var(--text-color);
        }

        table tr:hover {
            background-color: var(--table-hover);
        }

        /* Form Styling */
        .add-user-form {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .add-user-form h2 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: var(--header-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--input-border);
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: var(--input-focus);
            outline: none;
        }

        .form-group small {
            display: block;
            margin-top: 0.5rem;
            color: var(--text-color);
            opacity: 0.7;
        }

        button[type="submit"] {
            background: var(--button-bg);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        button[type="submit"]:hover {
            background: var(--button-hover);
        }

        /* Error and Success Messages */
        .error-message, .success-message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .error-message {
            background: var(--error-bg);
            color: var(--error-text);
        }

        .success-message {
            background: var(--success-bg);
            color: var(--success-text);
        }

        /* Table Styling */
        .activity-logs {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .activity-logs h2 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: var(--header-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 1rem;
            text-align: left;
        }

        table th {
            background: var(--table-header-bg);
            color: var(--table-header-text);
            font-weight: 600;
        }

        table td {
            border-bottom: 1px solid var(--table-border);
            color: var(--text-color);
        }

        table tr:hover {
            background: var(--table-hover);
        }

        .notification-wrapper {
            position: relative;
            margin-right: 20px;
        }

        .notification-icon {
            font-size: 25px;
            cursor: pointer;
            color: var(--text-color);
        }

        .notification-icon:hover {
            color: var(--primary-color);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74a3b;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .theme-switch-wrapper {
            display: flex;
            align-items: center;
            position: absolute;
            right: 20px;
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
        <a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a>
        <a href="#" class="logoutButton">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Settings</h1>
            <div class="theme-switch-wrapper">
                <div class="notification-wrapper">
                    <a href="notifications.php" id="notificationLink">
                        <i class="fas fa-bell notification-icon"></i>
                        <span class="notification-badge" id="notificationCount"><?php echo $unread_notifications_count; ?></span>
                    </a>
                </div>
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round">
                        <i class="fas fa-sun"></i>
                        <i class="fas fa-moon"></i>
                    </div>
                </label>
            </div>
        </div>

        <!-- User Creation Form -->
        <div class="add-user-form">
            <h2>Add User Account</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($success) && $success): ?>
                <div class="success-message">User  added successfully!</div>
            <?php endif; ?>
            <form action="settings.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="rank">Rank</label>
                    <input type="text" id="rank" name="rank" required>
                </div>
                <div class="form-group">
                    <label for="badge_no">Badge No.</label>
                    <input type="text" id="badge_no" name="badge_no" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                    <small>Password must be at least 8 characters long</small>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Add User</button>
            </form>
        </div>

        <!-- Activity Logs Table -->
        <div class="activity-logs">
            <h2>Activity Logs</h2>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query to get recent activity logs
                    $log_query = "SELECT user, action, timestamp FROM activity_logs ORDER BY timestamp DESC LIMIT 20";
                    if ($result = $data->query($log_query)) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['user']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['action']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
                            echo "</tr>";
                        }
                        $result->free();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSwitch = document.querySelector('#checkbox');
            const currentTheme = localStorage.getItem('theme');

            // Check for saved theme preference
            if (currentTheme) {
                document.documentElement.setAttribute('class', currentTheme);
                if (currentTheme === 'dark-theme') {
                    toggleSwitch.checked = true;
                }
            }

            // Function to switch theme
            function switchTheme(e) {
                if (e.target.checked) {
                    document.documentElement.setAttribute('class', 'dark-theme');
                    localStorage.setItem('theme', 'dark-theme');
                } else {
                    document.documentElement.setAttribute('class', '');
                    localStorage.setItem('theme', '');
                }
            }

            // Event listener for theme switch
            toggleSwitch.addEventListener('change', switchTheme, false);
        });

        // Client-side validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            form.addEventListener('submit', function(event) {
                if (password.value !== confirmPassword.value) {
                    event.preventDefault();
                    alert('Passwords do not match!');
                }
            });
        });

        // Success notification with fade out
        <?php if (isset($success) && $success): ?>
            setTimeout(function() {
                const successMessage = document.querySelector('.success-message');
                if (successMessage) {
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.style.display = 'none';
                    }, 1000);
                }
            }, 3000);
        <?php endif; ?>
    </script>
    <script>
       document.addEventListener('DOMContentLoaded', function() {
            const notificationLink = document.getElementById('notificationLink');
            const notificationCount = document.getElementById('notificationCount');

            // Function to update notification count
            function updateNotificationCount() {
                fetch('get_unread_count.php') // Create this file to fetch the unread count
                    .then(response => response.json())
                    .then(data => {
                        notificationCount.textContent = data.unread_count;
                    })
                    .catch(error => console.error('Error fetching notification count:', error));
            }

            // Reset notifications when the notification link is clicked
            notificationLink.addEventListener('click', function(e) {
                fetch('reset_notifications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationCount.textContent = '0'; // Reset the count to 0
                    }
                })
                .catch(error => console.error('Error resetting notifications:', error));
            });

            // Update the notification count every 30 seconds
            setInterval(updateNotificationCount, 30000);

            // Initial call to update the count
            updateNotificationCount();
        });
    </script>
    <script src="/js/admindashb.js"></script>
</body>
</html>

<?php
// Close the database connection at the end of the script
$data->close();
?>
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