<?php
// Include database connection file
include('../conn/db_connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $contactNumber = $_POST['contact_number'];
    $username = $_POST['username'];
    $userType = $_POST['user_type'];
    $address = $_POST['address']; // New field

    // Validate and sanitize the inputs
    if (empty($email) || empty($contactNumber) || empty($username) || empty($address)) {
        // Error: Show alert for missing fields
        echo "<script>
                alert('Error: All fields are required.');
                window.history.back();
              </script>";
        exit();
    }

    // Sanitize inputs to avoid SQL injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $contactNumber = filter_var($contactNumber, FILTER_SANITIZE_STRING);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $userType = filter_var($userType, FILTER_SANITIZE_STRING);
    $address = filter_var($address, FILTER_SANITIZE_STRING); // Sanitize the address

    // Prepare SQL to insert new user details
    $stmt = $conn->prepare("INSERT INTO `users` (email, contact_number, user_name, user_type, address) VALUES (?, ?, ?, ?, ?)");
    
    // Check for preparation errors
    if ($stmt === false) {
        die("Error: Could not prepare query.");
    }
    
    // Bind parameters and execute the query
    $stmt->bind_param("sssss", $email, $contactNumber, $username, $userType, $address);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Success: Show success alert and redirect
        echo "<script>
                alert('User added successfully!');
                window.location.href='../home.php';
              </script>";
    } else {
        // Error: Show error alert and redirect
        echo "<script>
                alert('Error: Unable to add user. Please try again!');
                window.history.back();
              </script>";
    }
}
?>