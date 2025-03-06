<?php
// Assuming you're connected to your database
session_start();
include('../database/db_connect.php'); // Replace with your DB connection script
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
  header('Location: /thesis_web/login.php');
  exit();
}

// Query to fetch hourly violations for the current day
$queryDaily = "SELECT HOUR(datetime) as violation_hour, COUNT(*) as violation_count 
               FROM violations 
               WHERE DATE(datetime) = CURDATE()
               GROUP BY violation_hour 
               ORDER BY violation_hour ASC";
$resultDaily = mysqli_query($data, $queryDaily);

// Fetch hourly data
$dailyViolations = []; 
while ($row = mysqli_fetch_assoc($resultDaily)) {
    $dailyViolations[] = $row;
}

// Query to fetch daily violations with offender details
// Query to fetch daily violations with offender details
$queryDailyDetails = "SELECT violation_id, ticket_num,
                        datetime, 
                        CONCAT('<b>Name: </b>', offender_name, '<br><b>Address: </b>', offender_address, '<br><b>License No.: </b>', license_no, '<br><b>Birthdate: </b>', birthdate, '<br><b>Violation Place: </b>', violation_place) AS offender_details, 
                        CONCAT('<b>Type: </b>', vehicle_type, '<br><b>Plate No.: </b>', plate_no, '<br><b>Registration: </b>', vehicle_registration) AS vehicle_details, 
                        CONCAT('<b>Name: </b>', apprehending_officer, '<br><b>Rank: </b>', rank, '<br><b>Badge No.: </b>', badge_no) AS apprehending_officer_details, 
                        status, 
                        fines, 
                        violation_name, 
                        offense_count 
                      FROM violations 
                      WHERE DATE(datetime) = CURDATE() 
                      ORDER BY datetime ASC";
$resultDailyDetails = mysqli_query($data, $queryDailyDetails);

// Fetch daily violation details
$dailyViolationDetails = [];
while ($row = mysqli_fetch_assoc($resultDailyDetails)) {
    $dailyViolationDetails[] = $row;
}

// Query to fetch violations by day of week
$queryWeekly = "SELECT DAYOFWEEK(datetime) as day_of_week, COUNT(*) as violation_count 
                FROM violations 
                GROUP BY day_of_week 
                ORDER BY day_of_week ASC";
$resultWeekly = mysqli_query($data, $queryWeekly);

// Fetch weekly data
$weeklyViolations = [];
while ($row = mysqli_fetch_assoc($resultWeekly)) {
    $weeklyViolations[] = $row;
}

// Query to fetch daily violations for the current month
$queryMonthly = "SELECT DAY(datetime) as day_of_month, COUNT(*) as violation_count 
                 FROM violations 
                 WHERE MONTH(datetime) = MONTH(CURDATE()) 
                 AND YEAR(datetime) = YEAR(CURDATE())
                 GROUP BY day_of_month 
                 ORDER BY day_of_month ASC";
$resultMonthly = mysqli_query($data, $queryMonthly);

// Fetch monthly data
$monthlyViolations = [];
while ($row = mysqli_fetch_assoc($resultMonthly)) {
    $monthlyViolations[] = $row;
}
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <style>
    button {
      bottom: 20px;
      left: 20px;
      background-color: rgb(28, 88, 209);
      color: white;
      border: none;
      cursor: pointer;
      padding: 10px;
    }
    button:hover {
      background-color: rgb(4, 134, 255);
    }
    .chart-container {
      width: 800px;
      height: 400px;
      margin: 20px auto;
    }
    #exportPdfButton {
      position: absolute;
      bottom: 20px;
      left: 20px;
      background-color: rgb(28, 88, 209);
      color: white;
      border: none;
      width: 15%;
      cursor: pointer;
      padding: 10px;
    }
    #exportPdfButton:hover {
      background-color: rgb(4, 134, 255);
    }
    .main-content {
      position: relative;
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
      margin: 0;
      padding: 0;
    }
    .sidebar {
      background-color: var(--sidebar-background);
      color: var(--sidebar-text);
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 250px;
      overflow-y: auto;
      z-index: 1000;
    }
    .sidebar a {
      color: var(--sidebar-text);
    }
    .main-content {
      background-color: var(--background-color);
      margin-left: 250px; /* Same as sidebar width */
      padding: 20px;
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
      right: 190px;
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
    .sidebar a:hover {
      background: #34495e;
    }
    .daily-violations-table {
      width: 100%;
      max-width: 1500px;
      margin: 30px auto;
      background-color: var(--card-background);
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px var(--shadow-color);
    }
    .daily-violations-table h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.5rem;
      color: var(--text-color);
    }
    .daily-violations-table table {
      width: 100%;
      border-collapse: collapse;
      font-family: 'Poppins', sans-serif;
    }
    .daily-violations-table th, .daily-violations-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      color: var(--text-color);
    }
    .daily-violations-table th {
      background-color: var(--primary-color);
      color: white;
      font-weight: bold;
    }
    
    .daily-violations-table tr:hover {
      background-color: #ddd;
      cursor: pointer;
    }
    .daily-violations-table td {
      word-wrap: break-word;
    }
    .daily-violations-table td[colspan="4"] {
      text-align: center;
      font-style: italic;
      color: #888;
    }
    @media screen and (max-width: 768px) {
      .daily-violations-table th, .daily-violations-table td {
        padding: 8px 10px;
      }
      .daily-violations-table h2 {
        font-size: 1.25rem;
      }
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
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    <a href="#" class="logoutButton">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>

  </div>

  <div class="main-content">
    <div class="header">
      <h1>Reports</h1>
      <div class="theme-switch-wrapper">
        <label class="theme-switch" for="checkbox">
          <input type="checkbox" id="checkbox" />
          <div class="slider round">
            <i class="fas fa-sun"></i>
            <i class="fas fa-moon"></i>
          </div>
        </label>
      </div>
      <a href="report.php"><button>Most Common Violation</button></a>
    </div>

    <!-- Chart containers for daily, weekly, and monthly -->
    <div class="chart-container">
      <canvas id="dailyViolationsChart"></canvas>
    </div>
    <div class="chart-container">
      <canvas id="weeklyViolationsChart"></canvas>
    </div>
    <div class="chart-container">
      <canvas id="monthlyViolationsChart"></canvas>
    </div>

    <!-- Daily Violations Table -->
    <div class="daily-violations-table">
  <h2>Daily Violations</h2>
  <table>
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
      <?php if (!empty($dailyViolationDetails)): ?>
        <?php foreach ($dailyViolationDetails as $violation): ?>
          <tr>
            <td><?php echo htmlspecialchars($violation['violation_id']); ?></td>
            <td><?php echo htmlspecialchars($violation['ticket_num']); ?></td>
            <td><?php echo htmlspecialchars_decode($violation['offender_details']); ?></td>
            <td><?php echo htmlspecialchars_decode($violation['vehicle_details']); ?></td>
            <td><?php echo htmlspecialchars_decode($violation['apprehending_officer_details']); ?></td>
            <td><?php echo htmlspecialchars($violation['datetime']); ?></td>
            <td><?php echo htmlspecialchars($violation['status']); ?></td>
            <td><?php echo htmlspecialchars($violation['fines']); ?></td>
            <td><?php echo htmlspecialchars($violation['violation_name']); ?></td>
            <td><?php echo htmlspecialchars($violation['offense_count']); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="10">No violations recorded today.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
    
    <!-- Export to PDF Button -->
    <div>
      <button id="exportPdfButton">Export to PDF</button>
    </div>
  </div>

  <script>
    // Theme Switch Functionality
    document.addEventListener('DOMContentLoaded', function() {
      const toggleSwitch = document.querySelector('#checkbox');
      const currentTheme = localStorage.getItem('theme');

      if (currentTheme) {
        document.documentElement.setAttribute('class', currentTheme);
        if (currentTheme === 'dark-theme') {
          toggleSwitch.checked = true;
        }
      }

      function switchTheme(e) {
        if (e.target.checked) {
          document.documentElement.setAttribute('class', 'dark-theme');
          localStorage.setItem('theme', 'dark-theme');
        } else {
          document.documentElement.setAttribute('class', '');
          localStorage.setItem('theme', '');
        }
      }

      toggleSwitch.addEventListener('change', switchTheme, false);
    });

    // Chart Data
    const dailyLabels = <?php echo json_encode(array_column($dailyViolations, 'violation_hour')); ?>;
    const dailyCounts = <?php echo json_encode(array_column($dailyViolations, 'violation_count')); ?>;
    const weeklyLabels = <?php echo json_encode(array_column($weeklyViolations, 'day_of_week')); ?>;
    const weeklyCounts = <?php echo json_encode(array_column($weeklyViolations, 'violation_count')); ?>;
    const monthlyLabels = <?php echo json_encode(array_column($monthlyViolations, 'day_of_month')); ?>;
    const monthlyCounts = <?php echo json_encode(array_column($monthlyViolations, 'violation_count')); ?>;

    // Format hour labels to be more readable (e.g., "2:00 PM")
    const formattedHourLabels = dailyLabels.map(hour => {
      const formattedHour = hour % 12 || 12;
      const amPm = hour < 12 ? 'AM' : 'PM';
      return `${formattedHour}:00 ${amPm}`;
    });

    // Convert day of week number to day name
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const formattedWeeklyLabels = weeklyLabels.map(dayNum => dayNames[dayNum - 1]);

    // Render Charts
    const dailyCtx = document.getElementById('dailyViolationsChart').getContext('2d');
    new Chart(dailyCtx, {
      type: 'line',
      data: {
        labels: formattedHourLabels,
        datasets: [{
          label: 'Violations by Hour (Today)',
          data: dailyCounts,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
          pointBackgroundColor: 'rgba(75, 192, 192, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 1,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Violations'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Hour of Day'
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Violations by Hour of Day (Today)',
            font: {
              size: 18
            }
          }
        },
        elements: {
          line: {
            tension: 0.3
          }
        }
      }
    });

    const weeklyCtx = document.getElementById('weeklyViolationsChart').getContext('2d');
    new Chart(weeklyCtx, {
      type: 'line',
      data: {
        labels: formattedWeeklyLabels,
        datasets: [{
          label: 'Violations by Day of Week',
          data: weeklyCounts,
          backgroundColor: 'rgba(153, 102, 255, 0.2)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
          pointBackgroundColor: 'rgba(153, 102, 255, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 1,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Violations'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Day of Week'
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Violations by Day of Week',
            font: {
              size: 18
            }
          }
        },
        elements: {
          line: {
            tension: 0.3
          }
        }
      }
    });

    const monthlyCtx = document.getElementById('monthlyViolationsChart').getContext('2d');
    new Chart(monthlyCtx, {
      type: 'line',
      data: {
        labels: monthlyLabels,
        datasets: [{
          label: 'Violations by Day of Month (Current Month)',
          data: monthlyCounts,
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          borderColor: 'rgba(255, 159, 64, 1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
          pointBackgroundColor: 'rgba(255, 159, 64, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 1,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Violations'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Day of Month'
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Violations by Day of Month (Current Month)',
            font: {
              size: 18
            }
          }
        },
        elements: {
          line: {
            tension: 0.3
          }
        }
      }
    });

    // Export to PDF Functionality
    document.getElementById('exportPdfButton').addEventListener('click', function() {
      const chartContainers = document.querySelectorAll('.chart-container');
      const dailyViolationsTable = document.querySelector('.daily-violations-table');
      const pdf = new jspdf.jsPDF('portrait');
      let yPosition = 10;
      const maxHeight = 270;

      const addPageIfNeeded = (height) => {
        if (yPosition + height > maxHeight) {
          pdf.addPage();
          yPosition = 10;
        }
      };

      // Add charts to PDF
      chartContainers.forEach(container => {
        html2canvas(container).then(canvas => {
          const imgData = canvas.toDataURL('image/png');
          const imgWidth = 190;
          const imgHeight = canvas.height * imgWidth / canvas.width;

          addPageIfNeeded(imgHeight);
          pdf.addImage(imgData, 'PNG', 10, yPosition, imgWidth, imgHeight);
          yPosition += imgHeight + 10;
        });
      });

      // Add daily violations table to PDF
      html2canvas(dailyViolationsTable).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = 190;
        const imgHeight = canvas.height * imgWidth / canvas.width;

        addPageIfNeeded(imgHeight);
        pdf.addImage(imgData, 'PNG', 10, yPosition, imgWidth, imgHeight);
        yPosition += imgHeight + 10;
      });


    });
  </script>
  <script>
    document.getElementById('exportPdfButton').addEventListener('click', function() {
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF({
    orientation: 'landscape', // Change to landscape to accommodate more content
    unit: 'mm',
    format: 'a4'
  });

  // Function to add a page header
  const addPageHeader = (doc, title) => {
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.text(title, doc.internal.pageSize.width / 2, 10, { align: 'center' });
    return 20; // Return the y-position after header
  };

  // Export charts first
  const chartContainers = document.querySelectorAll('.chart-container');
  let yPosition = 20;

  chartContainers.forEach((container, index) => {
    html2canvas(container).then(canvas => {
      const imgData = canvas.toDataURL('image/png');
      const imgWidth = pdf.internal.pageSize.width - 20; // Leave margins
      const imgHeight = canvas.height * imgWidth / canvas.width;

      // Add new page if needed
      if (yPosition + imgHeight > pdf.internal.pageSize.height - 20) {
        pdf.addPage();
        yPosition = addPageHeader(pdf, `Violation Charts - Page ${pdf.internal.getNumberOfPages()}`);
      }

      pdf.addImage(imgData, 'PNG', 10, yPosition, imgWidth, imgHeight);
      yPosition += imgHeight + 10;

      // If this is the last chart, proceed to export table
      if (index === chartContainers.length - 1) {
        exportTable();
      }
    });
  });

  // Function to export table
  const exportTable = () => {
    const dailyViolationsTable = document.querySelector('.daily-violations-table table');
    
    pdf.autoTable({
      html: dailyViolationsTable,
      startY: yPosition + 10,
      theme: 'grid',
      styles: {
        fontSize: 8,
        cellPadding: 2,
        overflow: 'linebreak',
      },
      headStyles: {
        fillColor: [78, 115, 223], // Primary color for header
        textColor: [255, 255, 255], // White text for header
        fontStyle: 'bold',
      },
      bodyStyles: {
        textColor: [0, 0, 0], // Black text for body
      },
      alternateRowStyles: {
        fillColor: [245, 245, 245], // Light gray for alternate rows
      },
      columnStyles: {
        0: { cellWidth: 'auto' },
        1: { cellWidth: 'auto' },
        2: { cellWidth: 'auto' },
        3: { cellWidth: 'auto' },
        4: { cellWidth: 'auto' },
        5: { cellWidth: 'auto' },
        6: { cellWidth: 'auto' },
        7: { cellWidth: 'auto' },
        8: { cellWidth: 'auto' },
        9: { cellWidth: 'auto' },
      },
  
    });

    // Save the PDF after processing all content
    pdf.save('violations_report.pdf');
  };
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