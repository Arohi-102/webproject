<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, return a JSON response indicating false
    echo json_encode(['loggedIn' => false]);
    exit;
}

// If logged in, return a JSON response indicating true
echo json_encode(['loggedIn' => true]);
?>
