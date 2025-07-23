<?php
session_start();
include('../conn/db_connect.php');

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $cartId = $_GET['id'];

    // Delete the order from the cart table
    $query = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $cartId);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['success_message'] = "Order deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete the order.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

header("Location: ../A_order.php");
exit();
?>