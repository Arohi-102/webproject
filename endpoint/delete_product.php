<?php
include('../conn/db_connect.php');

if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $stmt->bind_param("i", $productID);

    if ($stmt->execute()) {
        // Redirect back to the store management page with a success message
        header("Location: ../A_store.php");
    } else {
        // Redirect back to the store management page with an error message
        header("Location: ../A_store.php");
    }

    $stmt->close();
} else {
    // Redirect back to the store management page if no ID is provided
    header("Location: ../A_store.php");
}

$conn->close();
?>