<?php
// Assuming you're connected to your database
include('../database/db_connect.php'); // Replace with your DB connection script
session_start(); // Start the session

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
  header('Location: /thesis_web/login.php');
  exit();
  
}
// Query to fetch violation data
$query = "SELECT violation_name, COUNT(*) as violation_count FROM violations GROUP BY violation_name ORDER BY violation_count DESC";
$result = mysqli_query($data, $query);

// Fetch the data
$violations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $violations[] = $row;
}

// Debugging: Check if data is fetched
// var_dump($violations);

// Find the most common violation
$mostCommonViolationIndex = 0; // Default to the first violation
if (!empty($violations)) {
    $maxCount = $violations[0]['violation_count'];
    foreach ($violations as $index => $violation) {
        if ($violation['violation_count'] > $maxCount) {
            $maxCount = $violation['violation_count'];
            $mostCommonViolationIndex = $index;
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
$unread_notifications_count = $unread_notifications_count_row ? $unread_notifications_count_row['unread_count'] : 0; // Default to 0 if null
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admindashb.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <style>
      button{
/* Absolute positioning */
      bottom: 20px;       /* Distance from the bottom */
      left: 20px;         /* Distance from the left */
      background-color: rgb(28, 88, 209);
      color: white;
      border: none;
      cursor: pointer;
      padding: 10px;   
    }
     button:hover{
        background-color: rgb(4, 134, 255);
     }
    .chart-container {
      width: 800px;
      height: 400px;
      margin: 20px auto;
    }
      #exportPdfButton {
  position: absolute; /* Absolute positioning */
  bottom: 20px;       /* Distance from the bottom */
  left: 20px;         /* Distance from the left */
  background-color: rgb(28, 88, 209);
  color: white;
  border: none;
  width: 15%;
  cursor: pointer;
  padding: 10px;      /* Add padding for better appearance */
}

#exportPdfButton:hover {
  background-color: rgb(4, 134, 255);
}

.main-content {
  position: relative; /* Ensure the button is positioned relative to this container */
}

:root {
      --primary-color: #4e73df;
      --background-color: #f8f9fc;
      --text-color: #333;
      --card-background: #fff;
      --sidebar-background: #1a73e8;
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
    }
    
    .sidebar a {
      color: var(--sidebar-text);
    }
    .sidebar a:hover {
      background: #34495e;
    }
    
    .main-content {
      background-color: var(--background-color);
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
      right: 160px;
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

    .notification-wrapper {
      position: relative;
      margin-right: 20px;
    }

    .notification-icon {
      font-size: 25px;
      cursor: pointer;
      color: var(--text-color);
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
  

    .notification-icon:hover {
      color: var(--primary-color);
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
      <h1>Reports</h1>  
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
      <a href="violations_inAmonth.php"><button>More Options</button></a>
    </div>

    <!-- Bar chart container -->
    <div class="chart-container">
      <canvas id="violationsChart"></canvas>
    </div>

    <!-- Export to PDF Button -->
    <button id="exportPdfButton">Export to PDF</button>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleSwitch = document.querySelector('#checkbox');
      const currentTheme = localStorage.getItem('theme');
      const notificationBadge = document.getElementById('notificationCount');

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

      // Simulate clearing notifications when clicking the bell
      document.querySelector('.notification-icon').addEventListener('click', function() {
        notificationBadge.style.display = 'none'; // Hide notification count when clicked
      });
    });
  </script>
    <script>
      // Pass PHP data to JavaScript
      const violationTypes = <?php echo json_encode(array_column($violations, 'violation_name')); ?>;
      const violationCounts = <?php echo json_encode(array_column($violations, 'violation_count')); ?>;
      const mostCommonViolationIndex = <?php echo $mostCommonViolationIndex; ?>;

      // Debugging: Check if data is passed to JavaScript
      console.log("Violation Types:", violationTypes);
      console.log("Violation Counts:", violationCounts);
      console.log("Most Common Violation Index:", mostCommonViolationIndex);

      // Create an array of colors for the bars
      const backgroundColors = violationTypes.map((_, index) => 
        index === mostCommonViolationIndex ? 'rgba(255, 99, 132, 0.2)' : 'rgba(75, 192, 192, 0.2)'
      );
      const borderColors = violationTypes.map((_, index) => 
        index === mostCommonViolationIndex ? 'rgba(255, 99, 132, 1)' : 'rgba(75, 192, 192, 1)'
      );

      // Render the chart
      const ctx = document.getElementById('violationsChart').getContext('2d');
      const violationsChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: violationTypes,  // Labels for x-axis (violation types)
          datasets: [{
            label: 'Most Common Violations',
            data: violationCounts,  // Data for y-axis (count of violations)
            backgroundColor: backgroundColors, // Bar color
            borderColor: borderColors, // Border color
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed.y !== null) {
                    label += context.parsed.y;
                  }
                  if (context.dataIndex === mostCommonViolationIndex) {
                    label += ' (Most Common)';
                  }
                  return label;
                }
              }
            }
          }
        }
      });

      // Export to PDF functionality
      document.getElementById('exportPdfButton').addEventListener('click', function() {
        const chartContainer = document.querySelector('.chart-container');
        html2canvas(chartContainer).then(canvas => {
          const imgData = canvas.toDataURL('image/png');
          const pdf = new jspdf.jsPDF('landscape');
          const imgWidth = 280; // Width of the image in the PDF
          const imgHeight = canvas.height * imgWidth / canvas.width; // Calculate height to maintain aspect ratio
          pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
          pdf.save('violations_chart.pdf');
        });
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
  </div>

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