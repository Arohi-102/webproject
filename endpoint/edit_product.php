<?php
include('../conn/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    // Fetch existing product details (to retain old image if no new one is uploaded)
    $query = "SELECT image FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $current_image = $product['image']; // Store the current image path

    // Image Upload Handling (Only if a new image is uploaded)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        // Ensure the uploads folder exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move uploaded file and update image path
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = "uploads/" . $image_name;
        } else {
            $image_path = $current_image; // Keep the old image if upload fails
        }
    } else {
        $image_path = $current_image; // Keep the old image if no new file is uploaded
    }

    // Update product in the database
    $sql = "UPDATE products SET name=?, category=?, price=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $name, $category, $price, $image_path, $product_id);

    if ($stmt->execute()) {
        header("Location: ../A_store.php");
        exit();
    } else {
        header("Location: ../A_store.php");
        exit();
    }
}
?>
