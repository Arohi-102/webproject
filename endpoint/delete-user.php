<?php
include('../conn/db_connect.php');

if (isset($_GET['user'])) {
    $user = intval($_GET['user']); // Ensure it's an integer for safety.

    try {
        $query = "DELETE FROM `users` WHERE `user_id` = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $user);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    // Redirect with success
                    header('Location: ../home.php');
                } else {
                    // Redirect with error (user not found)
                    header('Location: ../home.php');
                }
            } else {
                // Redirect with error (execution failed)
                header('Location: ../home.php');
            }
        } else {
            // Redirect with error (preparation failed)
            header('Location: ../home.php');
        }
        $stmt->close();
    } catch (Exception $e) {
        // Redirect with error and optionally log the exception
        header('Location: ../home.php');
    }
} else {
    // Redirect with error (missing user ID)
    header('Location: ../home.php');
}
exit;
?>
