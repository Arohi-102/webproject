<?php
session_start();
include('../conn/db_connect.php'); // Corrected path

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $userID = $_POST['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contactNumber = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $userType = isset($_POST['user_type']) ? trim($_POST['user_type']) : null; // Add user_type field in the form if needed

    // Validate inputs
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($contactNumber)) {
        $errors[] = "Contact number is required.";
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    // If there are no errors, proceed with updating the profile
    if (empty($errors)) {
        // Fetch the user's current role from the database
        $roleQuery = "SELECT user_type FROM users WHERE user_id = ?";
        $roleStmt = $conn->prepare($roleQuery);
        $roleStmt->bind_param("i", $userID);
        $roleStmt->execute();
        $roleResult = $roleStmt->get_result();
        $currentUserRole = $roleResult->fetch_assoc()['user_type'];

        // Prepare the SQL query
        if (!empty($password)) {
            // If password is provided, hash it and include it in the update
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET user_name = ?, email = ?, contact_number = ?, address = ?, password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $username, $email, $contactNumber, $address, $hashedPassword, $userID);
        } else {
            // If password is not provided, update other fields only
            $sql = "UPDATE users SET user_name = ?, email = ?, contact_number = ?, address = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $username, $email, $contactNumber, $address, $userID);
        }

        // Execute the query
        if ($stmt->execute()) {
            // Redirect based on the user's role
            switch ($currentUserRole) {
                case 'admin':
                    header("Location: ../A_Dashboard.php");
                    break;
                case 'representative':
                    header("Location: ../representative_dashboard.php");
                    break;
                case 'user':
                    header("Location: ../user_Dashboard.php");
                    break;
                default:
                    header("Location: ../login.php");
                    break;
            }
            exit();
        } else {
            // Redirect with error message
            $_SESSION['errors'] = ["Failed to update profile. Please try again."];
            header("Location: ../A_Dashboard.php"); // Default fallback
            exit();
        }
    } else {
        // Redirect with error messages
        $_SESSION['errors'] = $errors;
        switch ($_SESSION['user_type']) {
            case 'admin':
                header("Location: ../A_Dashboard.php");
                break;
            case 'representative':
                header("Location: ../representative_dashboard.php");
                break;
            case 'user':
                header("Location: ../user_Dashboard.php");
                break;
            default:
                header("Location: ../login.php");
                break;
        }
        exit();
    }
} else {
    // Redirect if accessed directly without form submission
    header("Location: ../A_Dashboard.php"); // Default fallback
    exit();
}
?>