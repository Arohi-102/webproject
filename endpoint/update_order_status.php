<?php
session_start();

// Include the database connection file
include('../conn/db_connect.php'); // Adjust the path as needed

// Debug: Check if $conn is set
if (!isset($conn)) {
    die("Database connection is not established.");
}

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the cart ID and new status from the form
    $cart_id = $_POST['cart_id'];
    $new_status = $_POST['order_status'];

    // Validate the status
    $allowed_statuses = ['pending', 'confirmed', 'delivered'];
    if (!in_array($new_status, $allowed_statuses)) {
        die("Invalid status.");
    }

    // Update the order status in the database
    $update_query = "UPDATE deliver_addreses SET order_status = ? WHERE cart_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $new_status, $cart_id);
        if (mysqli_stmt_execute($stmt)) {
            // Status updated successfully
            $_SESSION['success_message'] = "Order status updated successfully!"; // Store success message
            header("Location: http://localhost/Smart_Assist/A_order.php"); // Redirect to the correct URL
            exit();
        } else {
            die("Error updating status: " . mysqli_error($conn));
        }
    } else {
        die("Error preparing statement: " . mysqli_error($conn));
    }
} else {
    // If the form is not submitted, redirect to the orders page
    header("Location: http://localhost/Smart_Assist/A_order.php"); // Redirect to the correct URL
    exit();
}
?>