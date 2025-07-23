<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include('./conn/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit;
}

$user_email = $user['email']; // Get the logged-in user's email
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

    </style>
</head>
<body>

    <!-- Modern Header -->
    <header class="header">
        <!-- Brand Section -->
        <div class="brand">
            <a href="<?php echo ($user['user_type'] == 'admin') ? 'home.php' : 'index.php'; ?>" class="navbar-brand">
                <i class="fas fa-home"></i> <?php echo ($user['user_type'] == 'admin') ? 'Admin Dashboard' : 'Back to Site'; ?>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="nav">
            <!-- Profile Link -->
            <a href="<?php echo ($user['user_type'] == 'admin') ? 'A_Dashboard.php' : 'user_Dashboard.php'; ?>" class="nav-link">
                <i class="fas fa-user-circle"></i> Profile
            </a>

            <!-- Manage Customers / Tickets Link -->
            <a href="<?php echo ($user['user_type'] == 'admin') ? 'Home.php' : 'user_ticket.php'; ?>" class="nav-link active">
                <i class="fas fa-users-cog"></i> <?php echo ($user['user_type'] == 'admin') ? 'Manage Customers' : 'Tickets'; ?>
            </a>

            <!-- Orders Link -->
            <a href="user_oder.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>

            <!-- Services Link -->
            <a href="<?php echo ($user['user_type'] == 'admin') ? 'admin_view.php' : 'user_repair.php'; ?>" class="nav-link">
                <i class="fas fa-tools"></i> Services
            </a>

            <!-- Admin-Specific Links -->
            <?php if ($user['user_type'] == 'admin') { ?>
                <a href="A_FAQ.php" class="nav-link">
                    <i class="fas fa-question-circle"></i> FAQ
                </a>
                <a href="A_store.php" class="nav-link">
                    <i class="fas fa-store"></i> Store
                </a>
            <?php } ?>
        </nav>

        <!-- Logout Button -->
        <div class="user-actions">
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>
   
    <br><br>
    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <h4>User Ticket Dashboard</h4>

            <!-- Display the logged-in user's email -->
            <p>Logged in as: <strong><?php echo $user_email; ?></strong></p>

            <!-- Card Container -->
            <div class="card-container">
                <?php
                // Fetch tickets for the logged-in user
                $sql = "SELECT * FROM tickets WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $user_email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <!-- Ticket Card -->
                        <div class="card">
                            <h3><?php echo $row['name']; ?></h3>
                            <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
                            <p><strong>Priority:</strong> <?php echo $row['priority']; ?></p>
                            <p><strong>Issue:</strong> <?php echo $row['issue']; ?></p>
                            <p><strong>File:</strong> 
                                <?php if ($row['file']) : ?>
                                    <a href="<?php echo $row['file']; ?>" target="_blank" class="file-link">View File</a>
                                <?php else : ?>
                                    No File
                                <?php endif; ?>
                            </p>
                            <p><strong>Status:</strong> 
                                <span class="status <?php echo ($row['status'] == 'Open') ? 'status-open' : 'status-closed'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </p>
                            <p><strong>Solution:</strong> <?php echo $row['solution'] ? $row['solution'] : 'No solution yet'; ?></p>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No tickets found for your account.</p>";
                }

                $stmt->close();
                ?>
            </div>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('updateForm').style.display = 'none';
        }
    </script>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>