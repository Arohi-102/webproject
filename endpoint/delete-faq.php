<?php
include('../conn/db_connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM faq WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../A_FAQ.php");
    } else {
        header("Location: ../A_FAQ.php");
    }
}
?>
