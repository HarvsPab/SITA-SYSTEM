<?php
session_start();
require_once "../database/db_connect.php";

// Redirect if user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: /thesis_web/login.php');
    exit();
}

// Fetch unread notifications count for both users and admins
$unread_notifications_count_sql = "
    SELECT COUNT(*) AS unread_count 
    FROM notifications 
    WHERE is_read = 0
";
$unread_notifications_count_result = mysqli_query($data, $unread_notifications_count_sql);
$unread_notifications_count_row = mysqli_fetch_assoc($unread_notifications_count_result);
$unread_notifications_count = $unread_notifications_count_row ? $unread_notifications_count_row['unread_count'] : 0; // Default to 0 if null

// Delete an ordinance if the delete_id is set
if (isset($_GET['delete_id'])) {
    $ordinance_title = $_GET['delete_id'];

    // Prepare and execute the delete query
    $query = "DELETE FROM ordinances WHERE ordinance_title = ?";
    $stmt = mysqli_prepare($data, $query);
    mysqli_stmt_bind_param($stmt, 's', $ordinance_title);
    
    if (mysqli_stmt_execute($stmt)) {
        // Set session message for successful deletion
        $_SESSION['message'] = "Ordinance deleted successfully!";
    } else {
        // Set session message for failed deletion
        $_SESSION['message'] = "Failed to delete ordinance.";
    }

    // Redirect to the same page to prevent resubmission on refresh
    header("Location: ordinance.php");
    exit();
}

// Handle Insert or Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        $ordinance_title = $_POST['ordinance_title'];
        $ordinance_desc = $_POST['ordinance_desc'];

        // Insert the ordinance into the database
        $query = "INSERT INTO ordinances (ordinance_title, ordinance_desc) VALUES (?, ?)";
        $stmt = mysqli_prepare($data, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $ordinance_title, $ordinance_desc);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Ordinance added successfully!";
        } else {
            $_SESSION['message'] = "Failed to add ordinance.";
        }
    } elseif (isset($_POST['update'])) {
        $original_title = $_POST['original_title'];
        $ordinance_title = $_POST['ordinance_title'];
        $ordinance_desc = $_POST['ordinance_desc'];

        // Update the ordinance in the database
        $query = "UPDATE ordinances SET ordinance_title = ?, ordinance_desc = ? WHERE ordinance_title = ?";
        $stmt = mysqli_prepare($data, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $ordinance_title, $ordinance_desc, $original_title);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Ordinance updated successfully!";
        } else {
            $_SESSION['message'] = "Failed to update ordinance.";
        }
    }

    // Redirect to prevent resubmission on refresh
    header("Location: ordinance.php");
    exit();
}

// Fetch ordinances from the database
$query = "SELECT * FROM ordinances";
$result = mysqli_query($data, $query);
$ordinances = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
        /* Base theme variables */
        :root {
            --primary-color: #4e73df;
            --background-color: #f8f9fc;
            --text-color: #333;
            --card-background: #fff;
            --sidebar-background: #1a73e8;
            --sidebar-text: #fff;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --table-header-bg: #0071ff;
            --table-header-text: white;
            --table-border: #ddd;
            --input-border: #ddd;
            --button-edit: #0071ff;
            --button-delete: red;
        }
/* Updated dark theme variables */
.dark-theme {
    --primary-color: #375bd2;
    --background-color: #121418; /* Darker background for full page */
    --text-color: #e0e0e0; /* Lighter text for better readability */
    --card-background: #1e2229; /* Darker card background */
    --sidebar-background: #1a73e8; /* Keeping sidebar as is */
    --sidebar-text: #ffffff;
    --shadow-color: rgba(0, 0, 0, 0.4);
    --table-header-bg: #2a4d69;
    --table-header-text: #f8f9fc;
    --table-border: #394b59;
    --input-border: #394b59;
    --button-edit: #375bd2;
    --button-delete: #e53e3e;
}

/* Additional dark mode adjustments */
.dark-theme .main-content {
    background-color: var(--background-color);
}

.dark-theme .header h1 {
    color: var(--text-color);
}

.dark-theme #searchInput {
    background-color: var(--card-background);
    color: var(--text-color);
    border-color: var(--input-border);
}

.dark-theme .add-ordinance {
    background-color: var(--card-background);
    color: var(--text-color);
}

.dark-theme .add-ordinance input, 
.dark-theme .add-ordinance textarea {
    background-color: var(--background-color);
    color: var(--text-color);
    border-color: var(--input-border);
}

.dark-theme .alert {
    background-color: var(--primary-color);
    color: white;
}

.dark-theme .notification-icon {
    color: var(--text-color);
}

.dark-theme .highlight {
    background-color: #5c2b2b;
    color: #ff6b6b;

    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--background-color);
        color: var(--text-color);
        margin: 0;
        padding: 0;
        transition: all 0.3s ease;
    }

    /* Sidebar styles - kept unchanged as requested */
    .sidebar {
        background-color: var(--sidebar-background);
        color: var(--sidebar-text);
        padding: 20px;
        width: 200px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
        z-index: 10;
    }

    .sidebar h2 {
        margin-bottom: 20px;
        text-align: center;
    }

    .sidebar a {
        color: var(--sidebar-text);
        text-decoration: none;
        display: block;
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .sidebar a:hover {
        background: #34495e;
    }

    .sidebar a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    /* Main content area */
    .main-content {
        margin-left: 200px;
        padding: 20px;
        transition: all 0.3s ease;
    }
    .header h1 {
    color: #333; /* Set a fixed color for the h1 */
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
    }

    /* Theme Switch Styles */
    .theme-switch-wrapper {
        display: flex;
        align-items: center;
        position: absolute;
        right: 25px;
        top: 0;
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
        z-index: 1;
    }

    .slider .fa-moon {
        color: #f1c40f;
        font-size: 14px;
        margin-right: 4px;
        z-index: 1;
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
        z-index: 2;
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

    /* Notification styles */
    .notification-wrapper {
        position: relative;
        margin-right: 100px;
    }

    .notification-icon {
        font-size: 25px;
        cursor: pointer;
        color: #333;;
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

    /* Search input */
    #searchInput {
        width: 50%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid var(--input-border);
        border-radius: 4px;
        font-size: 16px;
        background-color: var(--card-background);
        color: var(--text-color);
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: var(--card-background);
        box-shadow: 0 2px 5px var(--shadow-color);
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid var(--table-border);
    }

    th {
        background-color: var(--table-header-bg);
        color: var(--table-header-text);
        font-weight: bold;
    }

    tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .dark-theme tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Action buttons */
    .edit-btn, .delete-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        margin-right: 5px;
        transition: all 0.3s ease;
    }

    .edit-btn {
        background-color: var(--button-edit);
        color: white;
    }

    .delete-btn {
        background-color: var(--button-delete);
        color: white;
    }

    .edit-btn:hover, .delete-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* Form styles */
    .add-ordinance {
        background-color: var(--card-background);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px var(--shadow-color);
        margin-top: 30px;
    }

    .add-ordinance h2 {
        margin-top: 0;
        margin-bottom: 15px;
        color: var(--text-color);
    }

    .add-ordinance input, 
    .add-ordinance textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid var(--input-border);
        border-radius: 4px;
        background-color: var(--card-background);
        color: var(--text-color);
        box-sizing: border-box;
    }

    .add-ordinance textarea {
        min-height: 100px;
        resize: vertical;
    }

    .add-ordinance button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 15px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .add-ordinance button:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    .highlight {
      background-color: #ffcccc;
      color: red; /* Change the color to red for highlighted text */
      font-weight: bold; /* Optional: make the highlighted text bold *//* Optional: make the highlighted text bold */
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
        <a href="settings.php"><i class="fas fa-cog"></i>Settings</a>
        <a href="#" class="logoutButton">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Ordinances</h1>
            <div class="notification-wrapper">
        <a href="notifications.php" id="notificationLink">
          <i class="fas fa-bell notification-icon"></i>
          <span class="notification-badge" id="notificationCount"><?php echo $unread_notifications_count; ?></span>
        </a>
      </div>
            <div class="theme-switch-wrapper">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round">
                        <i class="fas fa-sun"></i>
                        <i class="fas fa-moon"></i>
                    </div>
                </label>
            </div>
        </div>

        <!-- Session message display if set -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert" style="padding: 10px; background-color: var(--primary-color); color: white; border-radius: 4px; margin-bottom: 15px;">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); // Clear the message after displaying it
                ?>
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for ordinances...">

        <!-- Ordinances Table -->
        <table id="ordinanceTable">
            <thead>
                <tr>
                    <th>Ordinance Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordinances as $ordinance): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ordinance['ordinance_title']); ?></td>
                        <td><?php echo htmlspecialchars($ordinance['ordinance_desc']); ?></td>
                        <td>
                            <!-- Edit button -->
                            <button class="edit-btn" onclick="editOrdinance('<?php echo htmlspecialchars(addslashes($ordinance['ordinance_title'])); ?>', '<?php echo htmlspecialchars(addslashes($ordinance['ordinance_desc'])); ?>')">Edit</button>
                            <!-- Delete button with confirmation dialog -->
                            <button class="delete-btn" onclick="confirmDelete('<?php echo htmlspecialchars($ordinance['ordinance_title']); ?>')">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add or Update Ordinance Form -->
        <div class="add-ordinance">
            <h2>Add or Update Ordinance</h2>
            <form method="POST">
                <input type="hidden" name="original_title" id="original_title">
                <input type="text" name="ordinance_title" id="ordinance_title" placeholder="Ordinance Title" required>
                <textarea name="ordinance_desc" id="ordinance_desc" placeholder="Ordinance Description" required></textarea>
                <button type="submit" id="submitButton" name="submit">Add Ordinance</button>
            </form>
        </div>
    </div>

    <script>
      // Function to filter the table rows based on the search input
function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("ordinanceTable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let row = rows[i];
        let cells = row.getElementsByTagName("td");
        let title = cells[0].textContent.toLowerCase();
        let description = cells[1].textContent.toLowerCase();

        // Reset the inner HTML to remove previous highlights
        cells[0].innerHTML = cells[0].textContent;
        cells[1].innerHTML = cells[1].textContent;

        if (title.includes(input) || description.includes(input)) {
            row.style.display = "";
            // Highlight matching text
            if (title.includes(input)) {
                cells[0].innerHTML = title.replace(new RegExp(input, 'gi'), match => `<span class="highlight">${match}</span>`);
            }
            if (description.includes(input)) {
                cells[1].innerHTML = description.replace(new RegExp(input, 'gi'), match => `<span class="highlight">${match}</span>`);
            }
        } else {
            row.style.display = "none";
        }
    }
}

        // Function to handle row click and auto-select ordinance for editing
        function editOrdinance(title, desc) {
            document.getElementById('ordinance_title').value = title;
            document.getElementById('ordinance_desc').value = desc;
            document.getElementById('original_title').value = title;

            const submitButton = document.getElementById('submitButton');
            submitButton.innerText = 'Update Ordinance';
            submitButton.name = 'update';
            
            // Scroll to the form
            document.querySelector('.add-ordinance').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Function to confirm deletion
        function confirmDelete(title) {
            if (confirm('Are you sure you want to delete this ordinance?')) {
                window.location.href = '?delete_id=' + encodeURIComponent(title);
            }
        }

        // Theme switcher functionality
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