<?php
// Start session at the beginning of the script
session_start();
require_once "../database/db_connect.php";
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: /thesis_web/login.php');
    exit();
}

// Fetch violations for display
$sql = "SELECT violation_id, ticket_num, 
            CONCAT('<b>Name: </b>', offender_name, '<br><b>Address: </b>', offender_address, '<br><b>License No.: </b>', license_no, '<br><b>Birthdate: </b>', birthdate, '<br><b>Violation Place: </b>', violation_place) AS offender_details, 
            CONCAT('<b>Type: </b>', vehicle_type, '<br><b>Plate No.: </b>', plate_no, '<br><b>Registration: </b>', vehicle_registration) AS vehicle_details, 
            CONCAT('<b>Name: </b>', apprehending_officer, '<br><b>Rank: </b>', rank, '<br><b>Badge No.: </b>', badge_no) AS apprehending_officer, 
            datetime, status, fines, violation_name, offense_count, 
            violation_place
        FROM violations 
        ORDER BY violation_id DESC";
$result = mysqli_query($data, $sql);

if (!$result) {
    die('Invalid query: ' . mysqli_error($data));
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
$unread_notifications_count = $unread_notifications_count_row ? $unread_notifications_count_row['unread_count'] : 0; // Default to 0 if null
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violations</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/userdashb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add the highlight class for matched text */
        .highlight {
            background-color: #ffcccc; /* Light red background */
            color: #ff0000; /* Red text */
            font-weight: bold; /* Bold text */
        }
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
            top: 0; /* Align it to the top */
            left: 0; /* Align it to the left */
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
            margin-left: 250px; /* Same width as the sidebar */
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
            position:
            relative;
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

        .violations-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            font-size: 14px;
        }

        .violations-table th, .violations-table td {
            padding: 5px 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .violations-table th {
            background-color: #1a73e8;
            font-weight: bold;
        }

        .violations-table td {
            word-wrap: break-word;
        }

        /* Styling for the search bar */
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: absolute;
            left: 330px;
            top: 55px;
        }

        .search-container input {
            padding: 8px;
            font-size: 14px;
            width: 250px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-wrapper {
            position: relative;
            margin-right: 15px; /* Space between theme switch and notification icon */
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
            <h1>Violations</h1>
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
        <div>
            <input type="text" id="searchInput" placeholder="Search Violation Details" onkeyup="searchTable()" style="margin: 20px 0; padding: 8px; width: 200px;">&nbsp; Search
        </div>

        <!-- Violations Table -->
        <table class="violations-table">
            <thead>
                <tr>
                    <th>VIOLATION ID</th>
                    <th>TICKET NO.</th>
                    <th>OFFENDER DETAILS</th>
                    <th>VEHICLE DETAILS</th>
                    <th>APPREHENDING OFFICER</th>
                    <th>DATETIME</th>
                    <th>STATUS</th>
                    <th>FINES</th>
                    <th>VIOLATION NAME</th>
                    <th>OFFENSE COUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row['violation_id']; ?></td>
                        <td><?php echo $row['ticket_num']; ?></td>
                        <td><?php echo $row['offender_details']; ?></td>
                        <td><?php echo $row['vehicle_details']; ?></td>
                        <td><?php echo $row['apprehending_officer']; ?></td>
                        <td><?php echo $row['datetime']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['fines']; ?></td>
                        <td><?php echo $row['violation_name']; ?></td>
                        <td><?php echo $row['offense_count']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.querySelector('.violations-table');
            tr = table.getElementsByTagName('tr');

            if (filter === "") {
                // If the input is empty, show all rows and clear highlights
                for (i = 1; i < tr.length; i++) {
                    tr[i].style.display = '';  // Show all rows
                    td = tr[i].getElementsByTagName('td');
                    for (j = 0; j < td.length; j++) {
                        if (td[j]) {
                            // Clear previous highlights
                            td[j].innerHTML = td[j].textContent; 
                        }
                    }
                }
                return; // Exit the function early
            }

            for (i = 1; i < tr.length; i++) {  // Start from 1 to skip the header row
                tr[i].style.display = 'none';  // Initially hide the row
                td = tr[i].getElementsByTagName('td');

                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        // Clear previous highlights
                        td[j].innerHTML = td[j].textContent; 

                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = '';  // Show row if match is found
                            // Highlight matching text
                            var highlightedText = txtValue.replace(new RegExp(filter, "gi"), function(match) {
                                return "<span class='highlight'>" + match + "</span>";
                            });
                            td[j].innerHTML = highlightedText; // Set the highlighted text
                            break;
                        }
                    }
                }
            }
        }
    </script>

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