<?php
session_start();

// Correct the path to db_connect.php
$dbPath = __DIR__ . '/../conn/db_connect.php'; // Adjust the path as needed
if (file_exists($dbPath)) {
    include($dbPath);
} else {
    die("Database connection file not found!");
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the cart item ID is provided
if (!isset($_GET['id'])) {
    die("Cart item ID is missing!");
}

$id = $_GET['id'];

// Fetch the cart item
$query = "SELECT * FROM cart WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cartItem = $result->fetch_assoc();

if (!$cartItem) {
    die("Cart item not found!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $total_price = $quantity * $price;

    // Update the cart item
    $updateQuery = "UPDATE cart SET quantity = ?, price = ?, total_price = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("dddi", $quantity, $price, $total_price, $id);

    if ($stmt->execute()) {
        header('Location: cart_management.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cart Item</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Adjust the path as needed -->
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a class="navbar-brand" href="home.php">Rep Dashboard</a>
        <a href="user_Dashboard.php">Profile</a>
        <a href="Home.php">Manage Customer</a>
        <a href="A_ticket.php">Ticket</a>
        <a href=".php">Orders</a>
        <a href="admin_view.php">Booking Appointment</a>
        <a href="A_FAQ.php">FAQ</a>
        <a href="cart_management.php">Manage Cart</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Edit Cart Item</h1>
        <form method="POST" action="">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="<?php echo $cartItem['quantity']; ?>" required>
            <br>
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" value="<?php echo $cartItem['price']; ?>" required>
            <br>
            <button type="submit">Update</button>
        </form>
    </div>

</body>
</html>