<?php
// Assuming you're connected to your database
include('../database/db_connect.php'); // Replace with your DB connection script
session_start(); // Start the session

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: thesis_web/login.php');
    exit();
}

// Initialize variables for position
$position = isset($_SESSION['position']) ? $_SESSION['position'] : "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position = $_POST['position'];
    
    // Store in session for persistence
    $_SESSION['position'] = $position;
}

// Fetch admin role information
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$admin_sql = "SELECT username, role FROM users WHERE role = 'admin'";
$admin_result = mysqli_query($data, $admin_sql);
$admin_info = mysqli_fetch_assoc($admin_result);
$admin_username = $admin_info ? $admin_info['username'] : 'No admin found';

// Fetch unread notifications count for both users and admins
$unread_notifications_count_sql = "
    SELECT COUNT(*) AS unread_count 
    FROM notifications 
    WHERE is_read = 0
";
$unread_notifications_count_result = mysqli_query($data, $unread_notifications_count_sql);
$unread_notifications_count_row = mysqli_fetch_assoc($unread_notifications_count_result);
$unread_notifications_count = $unread_notifications_count_row ? $unread_notifications_count_row['unread_count'] : 0; // Default to 0 if null

// 1. Total Violations
$total_violations_sql = "SELECT COUNT(*) AS total FROM violations";
$total_violations_result = mysqli_query($data, $total_violations_sql);
$total_violations_row = mysqli_fetch_assoc($total_violations_result);
$total_violations = $total_violations_row ? $total_violations_row['total'] : 0; // Default to 0 if null

// 2. Unresolved Violations
$unresolved_violations_sql = "SELECT COUNT(*) AS unresolved FROM violations WHERE status IN ('Pending', 'Status', 'Disputed')";
$unresolved_violations_result = mysqli_query($data, $unresolved_violations_sql);
$unresolved_violations_row = mysqli_fetch_assoc($unresolved_violations_result);
$unresolved_violations = $unresolved_violations_row ? $unresolved_violations_row['unresolved'] : 0; // Default to 0 if null

// 3. Most Common Violations
$most_common_violations_sql = "SELECT violation_name, COUNT(*) AS count FROM violations GROUP BY violation_name ORDER BY count DESC LIMIT 1";
$most_common_violations_result = mysqli_query($data, $most_common_violations_sql);
$most_common_violations_row = mysqli_fetch_assoc($most_common_violations_result);
$most_common_violations = $most_common_violations_row ? $most_common_violations_row['violation_name'] : 'None'; // Default to 'None' if null

// 4. Daily Violations (violations for today's date)
$current_date = date('Y-m-d');
$daily_violations_sql = "SELECT COUNT(*) AS daily FROM violations WHERE DATE(datetime) = '$current_date'";
$daily_violations_result = mysqli_query($data, $daily_violations_sql);
$daily_violations_row = mysqli_fetch_assoc($daily_violations_result);
$daily_violations = $daily_violations_row ? $daily_violations_row['daily'] : 0; // Default to 0 if null

// 5. Active Users
$active_users_sql = "SELECT COUNT(*) AS active FROM users WHERE status = 'active' AND role != 'admin'";
$active_users_result = mysqli_query($data, $active_users_sql);
$active_users_row = mysqli_fetch_assoc($active_users_result);
$active_users = $active_users_row ? $active_users_row['active'] : 0; // Default to 0 if null

// 6. Inactive Users
$inactive_users_sql = "SELECT COUNT(*) AS inactive FROM users WHERE status = 'inactive' AND role != 'admin'";
$inactive_users_result = mysqli_query($data, $inactive_users_sql);
$inactive_users_row = mysqli_fetch_assoc($inactive_users_result);
$inactive_users = $inactive_users_row ? $inactive_users_row['inactive'] : 0; // Default to 0 if null

// 7. Fetch all non-admin users
$users_sql = "SELECT username, status FROM users WHERE role != 'admin'";
$users_result = mysqli_query($data, $users_sql);

// 8. Update the active users query to exclude admins and join with users table to get status
$active_users_logs_sql = "SELECT al.user, 
                          u.status,
                          MAX(al.timestamp) as last_activity,
                          (SELECT action FROM activity_logs 
                           WHERE user = al.user 
                           ORDER BY timestamp DESC LIMIT 1) as last_action
                         FROM activity_logs al 
                         JOIN users u ON al.user = u.username
                         WHERE u.role != 'admin'
                         GROUP BY al.user 
                         ORDER BY last_activity DESC";
$active_users_logs_result = mysqli_query($data, $active_users_logs_sql);

// If there are no activity logs for some users, we still want to display them
// So let's get all non-admin users that don't have activity logs
$users_without_logs_sql = "SELECT u.username, u.status 
                          FROM users u 
                          LEFT JOIN (
                              SELECT DISTINCT user FROM activity_logs
                          ) al ON u.username = al.user
                          WHERE u.role != 'admin' AND al.user IS NULL";
$users_without_logs_result = mysqli_query($data, $users_without_logs_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admindashb.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* User Profile Styles */
    :root {
      --primary-color: #4e73df;
      --background-color: #f8f9fc;
      --text-color: #333;
      --card-background:  #fff;
      --sidebar-background:  #1a73e8;
      --sidebar-text: #fff;
      --shadow-color: rgba(0, 0, 0, 0.1);
    }
    
    .dark-theme {
      --primary-color: #375bd2;
      --background-color: #1a1c24;
      --text-color: #f8f9fc;
      --card-background: #2c2f3f;
      --sidebar-background: #1a73e8;
      --sidebar-text: #ffffff;
      --shadow-color: rgba(0, 0, 0, 0.3);
    }
    
    body {
      background-color: var(--background-color);
      color: var(--text-color);
      transition: all 0.3s ease;
    }
    
    .sidebar {
      background-color: var(--sidebar-background);
      color: var(--sidebar-text);
      position: fixed; /* Make the sidebar fixed */
      top: 0; /* Align to the top */
      left: 0; /* Align to the left */
      height: 100%; /* Full height */
      width: 250px; /* Set a width for the sidebar */
      overflow-y: auto; /* Allow scrolling if content overflows */
      padding: 20px; /* Add some padding */
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Optional shadow for better visibility */
    }
    
    .sidebar a {
      color: var(--sidebar-text);
    }
    
    .main-content {
      background-color: var(--background-color);
      margin-left: 250px; /* Add margin to the left equal to the sidebar width */
      padding: 20px; /* Add some padding */
    }
    
    .card {
      background-color: var(--card-background);
      color: var(--text-color);
      box-shadow: 0 4px 8px var(--shadow-color);
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
    .sidebar a:hover {
      background: #34495e;
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

    .user-profile {
      background-color: var(--card-background);
      border-radius: 10px;
      padding: 20px;
      display: flex;
      align-items: center;
      margin-bottom: 25px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border-left: 5px solid #4e73df;
    }

    .user-profile:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .user-avatar {
      width: 70px;
      height: 70px;
      background-color: #4e73df;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-right: 20px;
      font-size: 28px;
      color: white;
      border: 3px solid #e8eaf6;
    }

    .user-info {
      flex: 1;
    }

    .user-info h3 {
      margin: 0 0 5px 0;
      color: #5a5c69;
      font-weight: 600;
      font-size: 18px;
    }

    .user-info p {
      margin: 0;
      color: #858796;
      font-size: 14px;
    }

    .user-meta {
      display: flex;
      margin-top: 10px;
      font-size: 12px;
      color: #858796;
    }

    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    table th {
      background-color: #f8f9fc;
      color: #5a5c69;
    }
    
    /* Active user indicator */
    .status-indicator {
      display: inline-block;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 5px;
    }
    
    .status-active {
      background-color: #2ecc71;
    }
    
    .status-inactive {
      background-color: #e74c3c;
    }
    
    .table-container {
      margin-top: 30px;
      background-color: var(--card-background);
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px var(--shadow-color);
    }
    
    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    
    .table-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--text-color);
    }

    /* Timestamp formatting */
    .timestamp {
      font-size: 12px;
      color: #858796;
    }
    
    .notification-wrapper {
      position: relative;
      margin-right: 80px; /* Space between theme switch and notification icon */
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

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between; /* Ensure space between items */
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
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    <a href="#" class="logoutButton">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
  </div>

  <div class="main-content">
    <div class="header">
      <h1>Admin Dashboard</h1>
      <div class="theme-switch-wrapper">
        <label class="theme-switch" for="checkbox">
          <input type="checkbox" id="checkbox" />
          <div class="slider round">
            <i class="fas fa-sun"></i>
            <i class="fas fa-moon"></i>
          </div>
        </label>
      </div>
      <div class="notification-wrapper">
        <a href="notifications.php" id="notificationLink">
          <i class="fas fa-bell notification-icon"></i>
          <span class="notification-badge" id="notificationCount"><?php echo $unread_notifications_count; ?></span>
        </a>
      </div>
    </div>

    <div class="user-profile">
      <div class="user-avatar">
        <i class="fas fa-user"></i>
      </div>
      <div class="user-info">
        <h3><?php echo htmlspecialchars($admin_username); ?></h3>
        <p>Administrator</p>
      </div>
    </div>

    <div class="dashboard-cards">
      <div class="card">
        <h3>Total Violations</h3>
        <p><?php echo $total_violations; ?></p>
      </div>
      <div class="card">
        <h3>Unresolved Violations</h3>
        <p><?php echo $unresolved_violations; ?></p>
      </div>
      <div class="card">
        <h3>Daily Violations</h3>
        <p><?php echo $daily_violations; ?></p>
      </div>
      <div class="card">
        <h3>Most Common Violations</h3>
        <p><?php echo $most_common_violations; ?></p>
      </div>
    </div>
    
    <!-- Table for displaying non-admin users with active/inactive status -->
    <div class="table-container">
      <div class="table-header">
        <div class="table-title">System Users</div>
      </div>
      
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Last Activity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          // Display users with activity logs first
          while ($user = mysqli_fetch_assoc($active_users_logs_result)): 
          ?>
          <tr>
            <td><?php echo htmlspecialchars($user['user']); ?></td>
            <td class="timestamp">
              <?php 
              if (!empty($user['last_activity'])) {
                echo date('M d, Y h:i A', strtotime($user['last_activity']));
              } else {
                echo 'No activity recorded';
              }
              ?>
            </td>
            <td>
              <?php if (strpos($user['last_action'], 'login') !== false && $user['last_action'] !== 'user logout'): ?>
                <span class="status-indicator status-active"></span>
                Active
              <?php else: ?>
                <span class="status-indicator status-inactive"></span>
                Inactive
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
          
          <?php 
          // Then display users without activity logs
          while ($user = mysqli_fetch_assoc($users_without_logs_result)): 
          ?>
          <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td class="timestamp">No activity recorded</td>
            <td>
              <?php if ($user['status'] == 'active'): ?>
                <span class="status-indicator status-active"></span>
                Active
              <?php else: ?>
                <span class="status-indicator status-inactive"></span>
                Inactive
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
          
          <?php 
          // Display message if no users found
          if (mysqli_num_rows($active_users_logs_result) == 0 && mysqli_num_rows($users_without_logs_result) == 0): 
          ?>
          <tr>
            <td colspan="3" style="text-align: center;">No users found</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const notificationLink = document.getElementById('notificationLink');
      const notificationCount = document.getElementById('notificationCount');

      // Function to update notification count
      function updateNotificationCount() {
          fetch('get_unread_count.php')
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
  <script>
    // Theme switch functionality
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
  </script>
  <script src="/js/admindashb.js"></script>
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