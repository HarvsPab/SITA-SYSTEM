<?php 
session_start(); 
include 'database/db_connect.php';

// Initialize error message variable
$error_message = "";

// Check if the user is already logged in, redirect according to their role
if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'user') {
        header("Location: user/dashboard.php");
        exit();
    } elseif ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $username = isset($_POST['username']) ? mysqli_real_escape_string($data, trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Use the plaintext password

    if (empty($username) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Get the user record using the username with prepared statement
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($data, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_array($result)) {
                // Found the user, compare plaintext password directly
                if ($password === $row['password']) { // Directly compare the plaintext password
                    // Set session variables
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username']; // Use database value for consistency
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['last_login'] = time(); // Track login time for session expiry

                    // Log the login activity
                    $activity = "User login successful";
                    $log_sql = "INSERT INTO activity_logs (user, action, timestamp) VALUES (?, ?, NOW())";
                    
                    if ($log_stmt = $data->prepare($log_sql)) {
                        $log_stmt->bind_param('ss', $username, $activity);
                        $log_stmt->execute();
                        $log_stmt->close();
                    }
                    
                    // Redirect based on role
                    if($row["role"] == "user") {
                        header("Location: user/dashboard.php");
                        exit();
                    } elseif($row["role"] == "admin") {
                        header("Location: admin/dashboard.php");
                        exit();
                    } else {
                        // Fallback for any other role that might be added in the future
                        header("Location: dashboard.php");
                        exit();
                    }
                } else {
                    // Incorrect password
                    $error_message = "Username and Password do not match.";
                    
                    // Log the failed login attempt for security monitoring
                    $failed_activity = "Failed login attempt";
                    $log_sql = "INSERT INTO activity_logs (user, action, timestamp) VALUES (?, ?, NOW())";
                    
                    if ($log_stmt = $data->prepare($log_sql)) {
                        $log_stmt->bind_param('ss', $username, $failed_activity);
                        $log_stmt->execute();
                        $log_stmt->close();
                    }
                }
            } else {
                $error_message = "Username and Password do not match.";
                
                // Log attempt with non-existent username
                $failed_activity = "Login attempt with non-existent username";
                $log_sql = "INSERT INTO activity_logs (user, action, timestamp) VALUES (?, ?, NOW())";
                
                if ($log_stmt = $data->prepare($log_sql)) {
                    $log_stmt->bind_param('ss', $username, $failed_activity);
                    $log_stmt->execute();
                    $log_stmt->close();
                }
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Database error occurred. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <header>
        <h2 class="logo"><img src="img/Pnp logo.png" alt="Logo"></h2>
        <button id="theme-switch">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/></svg>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z"/></svg>
        </button>
    </header>

    <?php if(!empty($error_message)): ?>
    <div class="alert-box" style="display:block;">
        <p class="alert"><?php echo htmlspecialchars($error_message); ?></p>
    </div>
    <?php endif; ?>

    <div class="form">
        <div class="heading">S.I.T.A</div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Ilagay ang iyong username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Ilagay ang iyong password" required>
            
            <center><button class="submit-btn" type="submit">Login</button></center>
        </form>
    </div>

    <div class="bottom-banner">
        <p></p>
    </div>

    <script src="js/form.js"></script>
</body>
</html>