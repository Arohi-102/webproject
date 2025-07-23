<?php 
session_start();
include('./conn/db_connect.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/styles.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

     <!-- Sidebar -->
     <div class="sidebar">
        <a class="navbar-brand" href="home.php">Admin Dashboard</a>
          <a href="Home.php" ><i class="fas fa-users-cog" ></i> Manage Users</a>
    <a href="A_ticket.php" ><i class="fas fa-ticket-alt"></i> Tickets</a>
    <a href="admin_view.php"><i class="fas fa-calendar-check"></i> Services</a>
    <a href="A_order.php"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="A_store.php" ><i class="fas fa-store"></i> Store</a>
        <a href="A_FAQ.php" ><i class="fas fa-question-circle"></i> FAQ</a>
        <a href="A_Dashboard.php" class="active" ><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>


</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <!-- User Details Section -->
            <h4>Profile Details</h4>

            <!-- Success and Error Messages -->
            <?php
                if (isset($_GET['success']) && $_GET['success'] == '1') {
                    echo "<div class='alert success'>Profile updated successfully!</div>";
                } elseif (isset($_GET['error']) && $_GET['error'] == '1') {
                    echo "<div class='alert error'>Error updating profile. Please try again!</div>";
                }
            ?>

            <div class="profile-container">
                <div class="profile-card">
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($user['user_id']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['user_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contact_number']); ?></p>
                    <p><strong>User Type:</strong> <?php echo htmlspecialchars($user['user_type']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                    <p><strong>Created At:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
                    <p><strong>Last Updated:</strong> <?php echo isset($user['updated_at']) ? htmlspecialchars($user['updated_at']) : 'N/A'; ?></p>
                </div><br><br>
                <button class="btn" onclick="openEditProfileModal()">Edit Profile</button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal" id="editProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="close" onclick="closeEditProfileModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/edit_profile.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="username" 
                            name="username" 
                            value="<?php echo htmlspecialchars($user['user_name']); ?>" 
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($user['email']); ?>" 
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="contact_number" 
                            name="contact_number" 
                            value="<?php echo htmlspecialchars($user['contact_number']); ?>" 
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="address" 
                            name="address" 
                            value="<?php echo htmlspecialchars($user['address']); ?>" 
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Leave blank to keep current password"
                        >
                    </div>
                    <button type="submit" class="btn">Update</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Open Edit Profile Modal
        function openEditProfileModal() {
            document.getElementById('editProfileModal').classList.add('show');
        }

        // Close Edit Profile Modal
        function closeEditProfileModal() {
            document.getElementById('editProfileModal').classList.remove('show');
        }

        // Close modal when clicking outside
        document.getElementById('editProfileModal').addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                closeEditProfileModal();
            }
        });
    </script>
</body>
</html>