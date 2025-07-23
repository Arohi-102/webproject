<?php
session_start();
include('./conn/db_connect.php'); 

if (isset($_POST['login'])) {
    $email = htmlspecialchars(trim($_POST['user_email'])); // Use email for login
    $password = htmlspecialchars(trim($_POST['password']));
    
    // Capture the previous page the user was on
    $previous_page = isset($_POST['previous_page']) ? $_POST['previous_page'] : 'index.php'; // Fallback if no previous page

    // Query to check if the email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email); // Bind the email parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true); // Regenerate session ID for security
        $_SESSION['user_id'] = $user['user_id']; // Store user ID in session
        $_SESSION['user_type'] = $user['user_type']; // Store user type in session

        // Redirect based on user type
        if ($user['user_type'] == 'admin') {
            header("Location: Home.php");
            exit();
        } elseif ($user['user_type'] == 'representative') {
            header("Location: representative_dashboard.php");
            exit();
        } else {
            // Redirect back to the page the user came from (or fallback to index.php)
            header("Location: " . $previous_page);
            exit();
        }
    } else {
        error_log("Invalid login attempt for email: $email");
        header("Location: main.php");
        exit();
    }
}
?>