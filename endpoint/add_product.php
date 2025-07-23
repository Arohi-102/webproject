<?php
include('../conn/db_connect.php'); // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    // Handle image upload
    $image = $_FILES['image']['name']; // Get image name
    $target_dir = "../uploads/"; // Ensure the folder exists
    $target_file = $target_dir . basename($image);

    // Check if the uploads directory exists, if not create it
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move uploaded file to the uploads directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Save only relative path (without "../")
        $image_path = "uploads/" . basename($image);

        // Insert product into the database
        $sql = "INSERT INTO products (name, category, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $category, $price, $image_path);

        if ($stmt->execute()) {
            header("Location: ../A_store.php");
            exit();
        } else {
            header("Location: ../A_store.php");
            exit();
        }
    } else {
        header("Location: ../A_store.phpp"); // Error in file upload
        exit();
    }
}
?>
