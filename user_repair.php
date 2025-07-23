<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include('./conn/db_connect.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit;
}

// Fetch the logged-in user's details
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
    <title>User Dashboard - Repair Requests</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
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
            <a href="<?php echo ($user['user_type'] == 'admin') ? 'Home.php' : 'user_ticket.php'; ?>" class="nav-link">
                <i class="fas fa-users-cog"></i> <?php echo ($user['user_type'] == 'admin') ? 'Manage Customers' : 'Tickets'; ?>
            </a>

            <!-- Orders Link -->
            <a href="user_oder.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>

            
        <!-- Services Link -->
        <a href="<?php echo ($user['user_type'] == 'admin') ? 'admin_view.php' : 'user_repair.php'; ?>" class="nav-link active">
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
            <h4>Your Repair Requests</h4>

            <!-- Display the logged-in user's email -->
            <p>Logged in as: <strong><?php echo $user_email; ?></strong></p>

            <table id="booking-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Category</th>
                        <th>Issue</th>
                        <th>Scheduled Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Fetch repair requests for the logged-in user only
                    $sql = "SELECT * FROM repair_requests WHERE email = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $user_email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        while($booking = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($booking['name']) . "</td>
                                    <td>" . htmlspecialchars($booking['email']) . "</td>
                                    <td>" . htmlspecialchars($booking['category']) . "</td>
                                    <td>" . htmlspecialchars($booking['issue']) . "</td>
                                    <td>" . htmlspecialchars($booking['scheduled_time'] ?? "Not Scheduled") . "</td>
                                    <td class='action-icons'>
                                        <i class='fas fa-eye' onclick='showDetails(" . json_encode($booking) . ")'></i>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No repair requests found for your account.</td></tr>";
                    }

                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for displaying full details -->
    <div class="modal" id="details-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Repair Request Details</h4>
                <i class="fas fa-times close-icon" onclick="closeModal()"></i>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <th>Name</th>
                        <td id="modal-name"></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td id="modal-email"></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td id="modal-phone"></td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td id="modal-category"></td>
                    </tr>
                    <tr>
                        <th>Issue</th>
                        <td id="modal-issue"></td>
                    </tr>
                    <tr>
                        <th>Service Type</th>
                        <td id="modal-service-type"></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td id="modal-address"></td>
                    </tr>
                    <tr>
                        <th>Submitted At</th>
                        <td id="modal-submitted-at"></td>
                    </tr>
                    <tr>
                        <th>Scheduled Time</th>
                        <td id="modal-scheduled-time"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showDetails(booking) {
            // Populate modal with details
            document.getElementById('modal-name').textContent = booking.name;
            document.getElementById('modal-email').textContent = booking.email;
            document.getElementById('modal-phone').textContent = booking.phone;
            document.getElementById('modal-category').textContent = booking.category;
            document.getElementById('modal-issue').textContent = booking.issue;
            document.getElementById('modal-service-type').textContent = booking.service_type;
            document.getElementById('modal-address').textContent = booking.address;
            document.getElementById('modal-submitted-at').textContent = booking.submitted_at;
            document.getElementById('modal-scheduled-time').textContent = booking.scheduled_time || "Not Scheduled";

            // Show modal
            document.getElementById('details-modal').style.display = 'flex';
        }

        function closeModal() {
            // Hide modal
            document.getElementById('details-modal').style.display = 'none';
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>