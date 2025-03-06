<?php


// Start session at the beginning of the script
session_start();
require_once "../database/db_connect.php";

// Add this to your existing PHP code, preferably before the HTML section

function fetchOffenderHistory($license_no, $current_violation_id, $current_offense_count) {
    global $data;
    
    // Validate inputs
    if (empty($license_no) || empty($current_violation_id) || $current_offense_count <= 1) {
        return [];
    }
    
    // Escape inputs to prevent SQL injection
    $license_no = mysqli_real_escape_string($data, $license_no);
    $current_violation_id = mysqli_real_escape_string($data, $current_violation_id);
    $current_offense_count = intval($current_offense_count);
    
    // Only fetch history for offenses below the current offense count
    $history_sql = "SELECT violation_id, datetime, violation_name, license_no, 
                           violation_place, status, offense_count, fines
                    FROM violations 
                    WHERE license_no = '$license_no' 
                      AND violation_id < '$current_violation_id'
                      AND offense_count < '$current_offense_count'
                      AND is_deleted = 0
                    ORDER BY violation_id DESC";
    
    $history_result = mysqli_query($data, $history_sql);
    $history = [];
    
    if ($history_result) {
        while ($row = mysqli_fetch_assoc($history_result)) {
            $history[] = $row;
        }
    }
    
    return $history;
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: /thesis_web/login.php');
    exit();
}

// Fetch current user details
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT rank, name, badge_no FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($data, $user_sql);
$user_data = mysqli_fetch_assoc($user_result);

// Get the user's rank, name, and badge number
$current_user_rank = isset($user_data['rank']) ? $user_data['rank'] : '';
$current_user_name = isset($user_data['name']) ? $user_data['name'] : '';
$current_user_badge = isset($user_data['badge_no']) ? $user_data['badge_no'] : '';

// Insert or Update violation record
if (isset($_POST['submit'])) {
    // Collect and sanitize input data
    $violation_id = isset($_POST['violation_id']) ? mysqli_real_escape_string($data, $_POST['violation_id']) : null;
    $ticket_num = mysqli_real_escape_string($data, $_POST['ticket_num']);
    $offender_name = mysqli_real_escape_string($data, $_POST['offender_name']);
    $offender_address = mysqli_real_escape_string($data, $_POST['offender_address']);
    $license_no = mysqli_real_escape_string($data, $_POST['license_no']);
    $birthdate = mysqli_real_escape_string($data, $_POST['birthdate']);
    $violation_place = mysqli_real_escape_string($data, $_POST['violation_place']);
    $vehicle_type = mysqli_real_escape_string($data, $_POST['vehicle_type']);
    $plate_no = mysqli_real_escape_string($data, $_POST['plate_no']);
    $vehicle_registration = mysqli_real_escape_string($data, $_POST['vehicle_registration']);
    $apprehending_officer = mysqli_real_escape_string($data, $_POST['commending_officer']);
    $rank = mysqli_real_escape_string($data, $_POST['rank']);
    $badge_no = mysqli_real_escape_string($data, $_POST['badge_no']);
    $datetime = mysqli_real_escape_string($data, $_POST['datetime']);
    $status = mysqli_real_escape_string($data, $_POST['status']);
    $fines = mysqli_real_escape_string($data, $_POST['fines']);
    $violation_name = mysqli_real_escape_string($data, $_POST['violation_name']);

    // Check if user is logged in and get user_id
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Function to calculate offense count
function calculateOffenseCount($data, $license_no, $offender_name) {
    // List of values that are considered invalid/placeholder license numbers
    $invalid_license_values = [
        'n/a', 'N/A', 'na', 'NA', 'none', 'None', 'NONE', 
        'n/A', 'N/a', 'nA', 'No License', 'NO LICENSE'
    ];

    // Normalize inputs to prevent SQL injection
    $license_no = mysqli_real_escape_string($data, $license_no);
    $offender_name = mysqli_real_escape_string($data, $offender_name);

    // Check if the current license number is in the invalid list (case-insensitive)
    $is_invalid_license = in_array(strtolower($license_no), array_map('strtolower', $invalid_license_values));

    // Construct the SQL query to count previous offenses from both active and resolved violations
    $previous_offense_sql = "SELECT COUNT(*) as offense_count 
                              FROM violations 
                              WHERE (
                                  (offender_name = '$offender_name') 
                                  OR 
                                  (license_no = '$license_no' AND license_no NOT IN ('" . implode("', '", $invalid_license_values) . "'))
                              ) 
                              AND (is_deleted = 0 OR is_deleted = 1)"; // Include both active and resolved violations
    
    $previous_offense_result = mysqli_query($data, $previous_offense_sql);
    $previous_offense_row = mysqli_fetch_assoc($previous_offense_result);
    
    // Increment offense count, but start from 1
    return $previous_offense_row['offense_count'] + 1;
}

// Use this function when inserting or updating violations
if (!$violation_id) {
    // For new violations
    $offense_count = calculateOffenseCount($data, $license_no, $offender_name);
} else {
    // For existing violations, keep the original offense count
    $existing_violation_sql = "SELECT offense_count FROM violations WHERE violation_id = '$violation_id'";
    $existing_violation_result = mysqli_query($data, $existing_violation_sql);
    $existing_violation_row = mysqli_fetch_assoc($existing_violation_result);
    $offense_count = $existing_violation_row['offense_count'];
}

if ($violation_id) {
    $sql = "UPDATE violations SET 
                ticket_num = '$ticket_num',
                offender_name = '$offender_name',
                offender_address = '$offender_address',
                license_no = '$license_no',
                birthdate = '$birthdate',
                violation_place = '$violation_place',
                vehicle_type = '$vehicle_type',
                plate_no = '$plate_no',
                vehicle_registration = '$vehicle_registration',
                apprehending_officer = '$apprehending_officer',
                rank = '$rank',
                badge_no = '$badge_no',
                datetime = '$datetime',
                status = '$status',
                fines = '$fines',
                violation_name = '$violation_name'";
    
    // Only add user_id to the update if it's available
    if ($user_id) {
        $sql .= ", user_id = '$user_id'";
    }
    
    $sql .= " WHERE violation_id = '$violation_id'";
    
    // Remove the user_id condition if no user_id is available
    if ($user_id) {
        $sql .= " AND user_id = '$user_id'";
    }
} else {
        // Insert new violation
        $sql = "INSERT INTO violations (ticket_num, offender_name, offender_address, license_no, birthdate, violation_place, vehicle_type, plate_no, vehicle_registration, apprehending_officer, rank, badge_no, datetime, status, fines, violation_name, offense_count";
        
        // Only include user_id if it's available
        if ($user_id) {
            $sql .= ", user_id";
        }
        
        $sql .= ") VALUES ('$ticket_num', '$offender_name', '$offender_address', '$license_no', '$birthdate', '$violation_place', '$vehicle_type', '$plate_no', '$vehicle_registration', '$apprehending_officer', '$rank', '$badge_no', '$datetime', '$status', '$fines', '$violation_name', '$offense_count'";
        
        // Only include user_id value if it's available
        if ($user_id) {
            $sql .= ", '$user_id'";
        }
        
        $sql .= ")";
    }
    
    if (mysqli_query($data, $sql)) {
        echo "<script>alert('Violation " . ($violation_id ? 'updated' : 'inserted') . " successfully'); window.location.href = 'violations.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($data) . "');</script>";
    }
}

// Soft delete violation record
if (isset($_GET['delete_violation_id'])) {
    $delete_violation_id = mysqli_real_escape_string($data, $_GET['delete_violation_id']);
    $delete_sql = "UPDATE violations SET is_deleted = 1, status = 'Resolved' WHERE violation_id = '$delete_violation_id'";
    if (mysqli_query($data, $delete_sql)) {
        echo "<script>alert('Violation deleted and marked as resolved successfully'); window.location.href = 'violations.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($data) . "');</script>";
    }
}

// Fetch violation details for editing
$violation_details = null;
if (isset($_GET['edit_violation_id'])) {
    $edit_violation_id = mysqli_real_escape_string($data, $_GET['edit_violation_id']);
    $sql_edit = "SELECT * FROM violations WHERE violation_id = '$edit_violation_id'";
    $result_edit = mysqli_query($data, $sql_edit);
    if ($result_edit) {
        $violation_details = mysqli_fetch_assoc($result_edit);
    } else {
        echo "<script>alert('Error: " . mysqli_error($data) . "');</script>";
    }
}

// Fetch resolved violations with concatenated details
$resolved_violations = [];
$sql_resolved = "SELECT violation_id, ticket_num, 
                        CONCAT('<b>Name: </b>', offender_name, '<br><b>Address: </b>', offender_address, '<br><b>License No.: </b>', license_no, '<br><b>Birthdate: </b>', birthdate, '<br><b>Violation Place: </b>', violation_place) AS offender_details, 
                        CONCAT('<b>Type: </b>', vehicle_type, '<br><b>Plate No.: </b>', plate_no, '<br><b>Registration: </b>', vehicle_registration) AS vehicle_details, 
                        CONCAT('<b>Name: </b>', apprehending_officer, '<br><b>Rank: </b>', rank, '<br><b>Badge No.: </b>', badge_no) AS apprehending_officer, 
                        datetime, status, fines, violation_name, 
                        offense_count, 
                        violation_place
                    FROM violations 
                    WHERE status = 'Resolved' AND is_deleted = 1 
                    ORDER BY datetime DESC";
$result_resolved = mysqli_query($data, $sql_resolved);
if ($result_resolved) {
    while ($row = mysqli_fetch_assoc($result_resolved)) {
        $resolved_violations[] = $row;
    }
} else {
    echo "<script>alert('Error: " . mysqli_error($data) . "');</script>";
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
        background-color: #ffcccc;
        color: #ff0000;
        font-weight: bold;
        padding: 2px 4px;
        border-radius: 3px;
    }

        :root {
        --primary-color: #4e73df;
        --background-color: #f8f9fc;
        --text-color: #333;
        --card-background: #fff;
        --sidebar-background: #1a73e8;
        --sidebar-text: #fff;
        --shadow-color: rgba(0, 0, 0, 0.1);
        --border-radius: 8px;
        --transition-speed: 0.3s;
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
        font-family: 'Poppins', 'Inter', sans-serif;
        transition: all var(--transition-speed) ease;
    }
        
    .sidebar {
        background-color: var(--sidebar-background);
        color: var(--sidebar-text);
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar a {
        color: var(--sidebar-text);
        transition: background-color 0.3s ease;
        border-radius: 4px;
        padding: 10px;
        margin: 5px;
        display: block;
    }
    .sidebar a:hover {
        background: rgba(255,255,255,0.2);
    }

      /* Form Styling Improvements */
      form {
        max-width: 600px; /* Reduce form width */
        margin: 0 auto 20px;
        padding: 15px;
      }
      form input, 
    form select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
    }

    form input:focus, form select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
    }

    form button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    form button:hover {
        opacity: 0.9;
    }

    .main-content {
        background-color: var(--background-color);
        margin-left: 250px;
        padding: 20px;
        max-width: 1200px; /* Limit maximum width */
        margin-right: auto;
        margin-left: auto;
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
        width: 100%;
        max-width: 1100px; /* Slightly narrower table */
        margin: 0 auto;
    }

    .violations-table {
        font-size: 0.8em;
    }

    .violations-table th {
        background-color: var(--primary-color);
        color: white;
        padding: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .violations-table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        transition: background-color 0.3s ease;
    }

    .violations-table tr:hover {
        background-color: rgba(78, 115, 223, 0.1);
    }

/* Button Styling */
.violations-table button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    margin: 4px;
    border-radius: 4px;
    font-size: 0.8em;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

/* Edit Button */
.violations-table button:nth-child(1) {
    background-color: #4CAF50; /* Green */
    color: white;
}

.violations-table button:nth-child(1):hover {
    background-color: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.violations-table button:nth-child(1):active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

/* Resolve Button */
.violations-table button:nth-child(2) {
    background-color: #f44336; /* Red */
    color: white;
}

.violations-table button:nth-child(2):hover {
    background-color: #d32f2f;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.violations-table button:nth-child(2):active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

/* History Button (if present) */
.violations-table button:nth-child(3) {
    background-color: #2196F3; /* Blue */
    color: white;
}

.violations-table button:nth-child(3):hover {
    background-color: #1976D2;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.violations-table button:nth-child(3):active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

        /* Styling for the search bar */
        .search-container {
        max-width: 600px;
        margin: 20px auto;
        text-align: center;
    }

    .search-container input {
        width: 100%;
        max-width: 400px;

    }

    .search-container input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
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
        margin-right: 15px;
    }

    .notification-icon {
        font-size: 25px;
        cursor: pointer;
        color: var(--text-color);
        transition: color 0.3s ease;
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

    /* Responsive adjustments */
    @media (max-width: 1400px) {
        .main-content {
            margin-left: 250px;
            padding: 15px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 100px;
            padding: 10px;
        }
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
        <!-- Add or Edit violation form -->
        <form action="" method="post">
            <!-- Hidden input for violation_id -->
            <input type="hidden" name="violation_id" value="<?php echo isset($violation_details['violation_id']) ? $violation_details['violation_id'] : ''; ?>">

            <input type="text" name="ticket_num" placeholder="Ticket Number" value="<?php echo isset($violation_details['ticket_num']) ? $violation_details['ticket_num'] : ''; ?>" required>

            <h3>Offender Details</h3>
            <input type="text" name="offender_name" placeholder="Complete Offender's Name" value="<?php echo isset($violation_details['offender_name']) ? $violation_details['offender_name'] : ''; ?>" required>
            <input type="text" name="offender_address" placeholder="Offender's Address" value="<?php echo isset($violation_details['offender_address']) ? $violation_details['offender_address'] : ''; ?>" required>
            <input type="text" name="license_no" placeholder="License No." value="<?php echo isset($violation_details['license_no']) ? $violation_details['license_no'] : ''; ?>" required>
            <input type="date" name="birthdate" value="<?php echo isset($violation_details['birthdate']) ? $violation_details['birthdate'] : ''; ?>" required>
            <input type="text" name="violation_place" placeholder="Violation's Place" value="<?php echo isset($violation_details['violation_place']) ? $violation_details['violation_place'] : ''; ?>" required>

            <h3>Vehicle Details</h3>
            <input type="text" name="vehicle_type" placeholder="Type of Vehicle" value="<?php echo isset($violation_details['vehicle_type']) ? $violation_details['vehicle_type'] : ''; ?>" required>
            <input type="text" name="plate_no" placeholder="Plate No." value="<?php echo isset($violation_details['plate_no']) ? $violation_details['plate_no'] : ''; ?>" required>
            <input type="text" name="vehicle_registration" placeholder="Vehicle Registration" value="<?php echo isset($violation_details['vehicle_registration']) ? $violation_details['vehicle_registration'] : ''; ?>" required>

            <h3>Officer Details</h3>
            <input type="text" name="rank" placeholder="Rank" value="<?php echo isset($violation_details['rank']) ? $violation_details['rank'] : $current_user_rank; ?>" readonly>
            <input type="text" name="commending_officer" placeholder="Officer Name" value="<?php echo isset($violation_details['apprehending_officer']) ? $violation_details['apprehending_officer'] : $current_user_name; ?>" readonly>
            <input type="text" name="badge_no" placeholder="Badge No." value="<?php echo isset($violation_details['badge_no']) ? $violation_details['badge_no'] : $current_user_badge; ?>" readonly>

            <input type="datetime-local" name="datetime" value="<?php echo isset($violation_details['datetime']) ? $violation_details['datetime'] : ''; ?>" required>
            <select name="status" required>
                <option value="Status">Status</option>
                <option value="Pending" <?php echo (isset($violation_details['status']) && $violation_details['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Disputed" <?php echo (isset($violation_details['status']) && $violation_details['status'] == 'Disputed') ? 'selected' : ''; ?>>Disputed</option>
            </select>

            <input type="number" name="fines" placeholder="Fines" value="<?php echo isset($violation_details['fines']) ? $violation_details['fines'] : ''; ?>" required>

            <h3>Violation Name</h3>
            <select name="violation_name" id="violation_name" onchange="toggleOtherField()">
    <option value="Dazzling light" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Dazzling light') ? 'selected' : ''; ?>>Dazzling light</option>
    <option value="Overspeeding" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Overspeeding') ? 'selected' : ''; ?>>Overspeeding</option>
    <option value="Obstruction" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Obstruction') ? 'selected' : ''; ?>>Obstruction</option>
    <option value="Illegal Parking" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Illegal Parking') ? 'selected' : ''; ?>>Illegal Parking</option>
    <option value="Reckless Driving" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Reckless Driving') ? 'selected' : ''; ?>>Reckless Driving</option>
    <option value="Failure to Signal Movement" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Failure to Signal Movement') ? 'selected' : ''; ?>>Failure to Signal Movement</option>
    <option value="Operating out at line" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Operating out at line') ? 'selected' : ''; ?>>Operating out at line</option>
    <option value="Junk Vehicle" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Junk Vehicle') ? 'selected' : ''; ?>>Junk Vehicle</option>
    <option value="Driving without license" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Driving without license') ? 'selected' : ''; ?>>Driving without license</option>
    <option value="Failure to obey Police Order" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Failure to obey Police Order') ? 'selected' : ''; ?>>Failure to obey Police Order</option>
    <option value="Disregarding Traffic Lights/Signs" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Disregarding Traffic Lights/Signs') ? 'selected' : ''; ?>>Disregarding Traffic Lights/Signs</option>
    <option value="Trucks/Bus Ban" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Trucks/Bus Ban') ? 'selected' : ''; ?>>Trucks/Bus Ban</option>
    <option value="Stalled Vehicle" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Stalled Vehicle') ? 'selected' : ''; ?>>Stalled Vehicle</option>
    <option value="Leaving the scene of accident without justifiable cause" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Leaving the scene of accident without justifiable cause') ? 'selected' : ''; ?>>Leaving the scene of accident without justifiable cause</option>
    <option value="Driving with an expired license" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Driving with an expired license') ? 'selected' : ''; ?>>Driving with an expired license</option>
    <option value="Driving under the influence of liquor/drug" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Driving under the influence of liquor/drug') ? 'selected' : ''; ?>>Driving under the influence of liquor/drug</option>
    <option value="Riding a motorcycle without helmet" <?php echo (isset($violation_details['violation_name']) && $violation_details['violation_name'] == 'Riding a motorcycle without helmet') ? 'selected' : ''; ?>>Riding a motorcycle without helmet</option>
    
    <!-- Newly Added Violations -->
    <option value="Littering and/or dumping of waste matter in public places">Littering and/or dumping of waste matter in public places</option>
    <option value="Permitting the collection of unsegregated solid waste">Permitting the collection of unsegregated solid waste</option>
    <option value="Unauthorized removal of waste collection materials/garbage bins">Unauthorized removal of waste collection materials/garbage bins</option>
    <option value="Open dumping or burying of waste in flood-prone areas">Open dumping or burying of waste in flood-prone areas</option>
    <option value="Non-segregation of solid waste">Non-segregation of solid waste</option>
    <option value="Selling/providing non-biodegradable plastic bags as second packaging for wet goods">Selling/providing non-biodegradable plastic bags as second packaging for wet goods</option>
    <option value="Selling/providing non-biodegradable plastic bags as packaging for dry goods">Selling/providing non-biodegradable plastic bags as packaging for dry goods</option>
    <option value="Selling/providing Styrofor as container">Selling/providing Styrofor as container</option>
    <option value="Disposing of plastic waste">Disposing of plastic waste</option>
    <option value="Disposing of animal waste from animal-driven transportation">Disposing of animal waste from animal-driven transportation</option>
    </select>    
</select>

</script>


            <button type="submit" name="submit"><?php echo isset($violation_details) ? 'Update Violation' : 'Add Violation'; ?></button>
        </form>
        <div>
    <input type="text" id="searchInput" placeholder="Search All Violation Details" onkeyup="searchTable()" style="margin: 20px 0; padding: 8px; width: 300px;">&nbsp; Search Across All Tables
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
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT violation_id, ticket_num, 
                            CONCAT('<b>Name: </b>', offender_name, '<br><b>Address: </b>', offender_address, '<br><b>License No.: </b>', license_no, '<br><b>Birthdate: </b>', birthdate, '<br><b>Violation Place: </b>', violation_place) AS offender_details, 
                            CONCAT('<b>Type: </b>', vehicle_type, '<br><b>Plate No.: </b>', plate_no, '<br><b>Registration: </b>', vehicle_registration) AS vehicle_details, 
                            CONCAT('<b>Name: </b>', apprehending_officer, '<br><b>Rank: </b>', rank, '<br><b>Badge No.: </b>', badge_no) AS apprehending_officer, 
                            datetime, status, fines, violation_name, offense_count, violation_place
                        FROM violations 
                        WHERE is_deleted = 0 
                        ORDER BY violation_id DESC";
                $result = mysqli_query($data, $sql);

                if (!$result) {
                    die('Invalid query: ' . mysqli_error($data));
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    // Safely get license_no, defaulting to an empty string if not set
                    $license_no = isset($row['license_no']) ? $row['license_no'] : '';
                    $offense_count = isset($row['offense_count']) ? intval($row['offense_count']) : 0;
                    
                    // Only fetch history if license_no is not empty and offense count is more than 1
                    $offender_history = ($license_no && $offense_count > 1) 
                        ? fetchOffenderHistory($license_no, $row['violation_id'], $offense_count) 
                        : [];
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
                        <td>
                            <a href="?edit_violation_id=<?php echo $row['violation_id']; ?>">
                                <button>Edit</button>
                            </a><br>
                            <a href="?delete_violation_id=<?php echo $row['violation_id']; ?>" 
                               onclick="return confirm('Are you sure you want to resolve this violation?')">
                                <button>Resolve</button>
                            </a>
                            <?php 
                            // Explicitly check if offender_history is not empty
                            if (!empty($offender_history)): ?>
                                <button onclick="toggleHistory(<?php echo $row['violation_id']; ?>)">History</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if (!empty($offender_history)): ?>
                    <tr id="history-row-<?php echo $row['violation_id']; ?>" style="display: none;">
                        <td colspan="11">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Datetime</th>
                                        <th>Violation Name</th>
                                        <th>License No.</th>
                                        <th>Violation Place</th>
                                        <th>Status</th>
                                        <th>Offense Count</th>
                                        <th>Fines</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($offender_history as $history_item): ?>
                                    <tr>
                                        <td><?php echo $history_item['datetime']; ?></td>
                                        <td><?php echo $history_item['violation_name']; ?></td>
                                        <td><?php echo $history_item['license_no']; ?></td>
                                        <td><?php echo $history_item['violation_place'] ?? 'N/A'; ?></td>
                                        <td><?php echo $history_item['status']; ?></td>
                                        <td><?php echo $history_item['offense_count']; ?></td>
                                        <td><?php echo $history_item['fines']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php
                }

                ?>
            </tbody>
        </table>

        <!-- Resolved Violations Toggle Button -->
        <div>
            <button id="toggleResolved" onclick="toggleResolvedTable()">Show Resolved Violations</button>
        </div>

        <!-- Resolved Violations Table -->
        <div id="resolvedTable" style="display: none;">
            <h2>Resolved Violations</h2>
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
                    foreach ($resolved_violations as $resolved) {
                    ?>
                        <tr>
                            <td><?php echo $resolved['violation_id']; ?></td>
                            <td><?php echo $resolved['ticket_num']; ?></td>
                            <td><?php echo $resolved['offender_details']; ?></td>
                            <td><?php echo $resolved['vehicle_details']; ?></td>
                            <td><?php echo $resolved['apprehending_officer']; ?></td>
                            <td><?php echo $resolved['datetime']; ?></td>
                            <td><?php echo $resolved['status']; ?></td>
                            <td><?php echo $resolved['violation_name']; ?></td>
                            <td><?php echo $resolved['fines']; ?></td>
                            <td><?php echo $resolved['offense_count']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<script>
    function getOffenseText($count) {
        if ($count == 1) {
            return '1st offense';
        } else if ($count == 2) {
            return '2nd offense';
        } else if ($count == 3) {
            return '3rd offense';
        } else {
            return $count + 'th offense';
        }
    }
</script>

<script>

    function searchTable() {
        var input, filter, table, tr, i, j, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        table = document.querySelector('.violations-table');
        tr = table.getElementsByTagName('tr');
        
        // Search through all tables (active and resolved)
        var tables = document.getElementsByClassName('violations-table');
        
        // Iterate through all tables
        for (var tableIndex = 0; tableIndex < tables.length; tableIndex++) {
            var currentTable = tables[tableIndex];
            var currentTr = currentTable.getElementsByTagName('tr');
            
            // Skip header row (i=1) and process only the data rows
            for (i = 1; i < currentTr.length; i++) {
                // Skip history rows
                if (currentTr[i].id && currentTr[i].id.startsWith('history-row-')) {
                    continue;
                }
                
                var displayRow = false;
                var td = currentTr[i].getElementsByTagName('td');
                
                // Check each cell in the row
                for (j = 0; j < td.length - 1; j++) { // Skip the last column (Actions)
                    if (td[j]) {
                        // Store original HTML before any highlight changes
                        var originalHTML = td[j].innerHTML;
                        
                        // Extract text content for searching
                        txtValue = td[j].textContent || td[j].innerText;
                        
                        // If the filter is empty, display all rows and remove highlights
                        if (filter === "") {
                            displayRow = true;
                            // Remove any highlights if they exist
                            td[j].innerHTML = originalHTML.replace(/<span class="highlight">(.*?)<\/span>/gi, "$1");
                        } 
                        // Otherwise check if cell contains the search text
                        else if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            displayRow = true;
                            
                            // First remove any existing highlights
                            var cleanHTML = originalHTML.replace(/<span class="highlight">(.*?)<\/span>/gi, "$1");
                            
                            // Now add new highlights - safely escape regex special characters
                            var safeFilter = filter.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                            
                            // Create a regex that preserves case but matches the text
                            var regex = new RegExp('(' + safeFilter + ')', 'gi');
                            
                            // Apply highlighting while preserving HTML structure
                            td[j].innerHTML = highlightText(cleanHTML, regex);
                        }
                    }
                }
                
                // Display or hide the row
                currentTr[i].style.display = displayRow ? '' : 'none';
            }
        }
    }

    // Helper function to highlight text while preserving HTML
    function highlightText(html, regex) {
        // Split HTML into tags and text
        var parts = html.split(/(<[^>]*>)/);
        
        // Process only text nodes
        for (var i = 0; i < parts.length; i++) {
            // Skip HTML tags
            if (parts[i].charAt(0) !== '<') {
                // Apply highlighting to text part
                parts[i] = parts[i].replace(regex, '<span class="highlight">$1</span>');
            }
        }
        
        // Rejoin the parts
        return parts.join('');
    }

    function toggleResolvedTable() {
        var table = document.getElementById("resolvedTable");
        var button = document.getElementById("toggleResolved");
        
        if (table.style.display === "none") {
            table.style.display = "block";
            button.innerText = "Hide Resolved Violations";
        } else {
            table.style.display = "none";
            button.innerText = "Show Resolved Violations";
        }
    }


    
    function toggleHistory(violationId) {
    var historyRow = document.getElementById('history-row-' + violationId);
    if (historyRow) {
        historyRow.style.display = historyRow.style.display === 'none' ? '' : 'none';
    }
}

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

        // Function to switch theme with smooth transition
        function switchTheme(e) {
            if (e.target.checked) {
                document.documentElement.classList.add('dark-theme');
                localStorage.setItem('theme', 'dark-theme');
            } else {
                document.documentElement.classList.remove('dark-theme');
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