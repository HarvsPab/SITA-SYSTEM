<?php
session_start();
require_once "../database/db_connect.php";

// Redirect if user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: /thesis_web/login.php');
    exit();
}

// Fetch ordinances from the database
$query = "SELECT * FROM ordinances";
$result = mysqli_query($data, $query);
$ordinances = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch unread notifications count for both users and admins
$unread_notifications_count_sql = "
    SELECT COUNT(*) AS unread_count 
    FROM notifications 
    WHERE is_read = 0
";
$unread_notifications_count_result = mysqli_query($data, $unread_notifications_count_sql);
$unread_notifications_count_row = mysqli_fetch_assoc($unread_notifications_count_result);
$unread_notifications_count = $unread_notifications_count_row ? $unread_notifications_count_row['unread_count'] : 0; // Default to 0 if null
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ordinances</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/Ordinanceadmin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --background-color: #f8f9fc;
      --text-color: #333;
      --card-background: #fff;
      --sidebar-background:  #1a73e8;
      --sidebar-text: #fff;
      --shadow-color: rgba(0, 0, 0, 0.1);
    }

    .dark-theme {
      --primary-color: #375bd2;
      --background-color: #1a1c24;
      --text-color: #f8f9fc;
      --card-background: #2c2f3f;
      --sidebar-background:  #1a73e8;
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
      height: 100%; /* Full height */
      width: 250px; /* Set a width for the sidebar */
      overflow-y: auto; /* Allow scrolling if content overflows */
    }

    .sidebar a {
      color: var(--sidebar-text);
    }
    .sidebar a:hover {
      background: #34495e;
    }

    .main-content {
      background-color: var(--background-color);
      margin-left: 250px; /* Adjust margin to accommodate the sidebar */
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

    .slider .fa-moon {
      color: #f1c40f;
      font-size: 14px;
      margin-right: 4px;
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #0071ff;
      color: white;
    }

    .add-ordinance {
      margin-top: 20px;
    }

    .add-ordinance input, .add-ordinance textarea {
      width: 100%;
      margin: 5px 0;
      padding: 8px;
    }

    .delete-btn {
      background-color: red;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }

    .edit-btn {
      background-color: #0071ff;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }

    #searchInput {
      width: 50%;
      padding: 8px;
      margin-bottom: 15px;
      margin-top: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
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

    .highlight {
      background-color: #ffcccc; /* Light red background */
      color: #ff0000; /* Red text */
      font-weight: bold; /* Bold text */
    }
  </style>

  <script>
    window.onload = function() {
      // Check if there's a session message
      <?php if (isset($_SESSION['message'])): ?>
        alert("<?php echo $_SESSION['message']; ?>");
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>
    };

    function confirmDelete(url) {
      if (confirm('Are you sure you want to delete this ordinance?')) {
        window.location.href = url;
      }
    }

    function editOrdinance(title, desc) {
      document.getElementById('ordinance_title').value = title;
      document.getElementById('ordinance_desc').value = desc;
      document.getElementById('original_title').value = title;

      const submitButton = document.getElementById('submitButton');
      submitButton.innerText = 'Update Ordinance';
      submitButton.name = 'update';
    }

    function searchTable() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let table = document.getElementById("ordinanceTable");
      let rows = table.getElementsByTagName("tr");

      for (let i = 1; i < rows.length; i++) {
        let row = rows[i];
        let cells = row.getElementsByTagName("td");
        let title = cells[0].textContent.toLowerCase();
        let description = cells[1].textContent.toLowerCase();

        // Remove previous highlights
        cells[0].innerHTML = cells[0].textContent;
        cells[1].innerHTML = cells[1].textContent;

        if (title.includes(input) || description.includes(input)) {
          row.style.display = "";

          // Highlight the matched text
          if (input !== "") {
            let regex = new RegExp(input, "gi");
            cells[0].innerHTML = cells[0].textContent.replace(regex, (match) => `<span class="highlight">${match}</span>`);
            cells[1].innerHTML = cells[1].textContent.replace(regex, (match) => `<span class="highlight">${match}</span>`);
          }
        } else {
          row.style.display = "none";
        }
      }
    }

    function selectOrdinance(title, desc) {
      editOrdinance(title, desc);
    }
  </script>
  <script>
    window.onload = function() {
      const checkbox = document.getElementById('checkbox');
      const darkTheme = localStorage.getItem('darkTheme') === 'true';

      checkbox.checked = darkTheme;
      document.body.classList.toggle('dark-theme', darkTheme);

      checkbox.addEventListener('change', function() {
        const isChecked = this.checked;
        document.body.classList.toggle('dark-theme', isChecked);
        localStorage.setItem('darkTheme', isChecked);
      });
    };
  </script>
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
      <h1>Ordinances</h1>
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

    <!-- Search Bar -->
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for ordinances...">

    <table id="ordinanceTable">
      <thead>
        <tr>
          <th>Ordinance Title</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ordinances as $ordinance): ?>
          <tr onclick="selectOrdinance('<?php echo $ordinance['ordinance_title']; ?>', '<?php echo $ordinance['ordinance_desc']; ?>')">
            <td><?php echo htmlspecialchars($ordinance['ordinance_title']); ?></td>
            <td><?php echo htmlspecialchars($ordinance['ordinance_desc']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
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