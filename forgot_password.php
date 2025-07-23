<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('./conn/db_connect.php');

function generateToken() {
    return bin2hex(random_bytes(50));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $token = generateToken();
            $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
            
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expires, $email);
            $stmt->execute();
            $stmt->close();
            
            $resetLink = "http://yourwebsite.com/reset_password.php?token=" . urlencode($token);
            $headers = "From: no-reply@yourwebsite.com\r\nContent-Type: text/plain; charset=UTF-8\r\n";
            mail($email, "Password Reset Request", "Click to reset: $resetLink", $headers);
            $_SESSION['success'] = "A reset link has been sent to your email.";
        } else {
            $_SESSION['error'] = "No account found with that email.";
        }
    }
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f4f7fc; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
        .close-btn { position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #555; }
        .close-btn:hover { color: #f44336; }
        h2 { text-align: center; color: #333; }
        input, button { width: 100%; padding: 12px; margin: 8px 0; border-radius: 8px; border: 1px solid #ddd; }
        button { background: #003366; color: white; border: none; cursor: pointer; }
        button:hover { background: #3f51b5; }
        p { text-align: center; margin-top: 15px; }
        p a { color: #5c6bc0; text-decoration: none; }
        p a:hover { text-decoration: underline; }
        .loading-spinner { display: none; width: 30px; height: 30px; border: 4px solid #f3f3f3; border-top: 4px solid #5c6bc0; border-radius: 50%; animation: spin 1s linear infinite; margin: 10px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="login-container">
        <span class="close-btn" onclick="history.back()">&times;</span>
        <h2>Forgot Password</h2>
        <?php if (isset($_SESSION['success'])) { echo "<p style='color: green;'>" . $_SESSION['success'] . "</p>"; unset($_SESSION['success']); } ?>
        <?php if (isset($_SESSION['error'])) { echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); } ?>
        <form action="forgot_password.php" method="POST" onsubmit="showLoading()">
            <input type="email" name="email" placeholder="Enter your registered email" required>
            <button type="submit">Send Reset Link</button>
            <div class="loading-spinner" id="loadingSpinner"></div>
        </form>
        <p>Remember your password? <a href="main.php">Login here</a></p>
    </div>
    <script>
        function showLoading() { document.getElementById('loadingSpinner').style.display = 'block'; }
    </script>
</body>
</html>
