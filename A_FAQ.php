<?php
include('./conn/db_connect.php');

// Handle adding or updating FAQ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['faq-question']) && isset($_POST['faq-answer'])) {
        $question = $_POST['faq-question'];
        $answer = $_POST['faq-answer'];

        if (isset($_POST['faq_id'])) {
            // Update existing FAQ
            $faq_id = $_POST['faq_id'];
            $sql = "UPDATE faq SET question = ?, answer = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $question, $answer, $faq_id);
            $success_message = "FAQ updated successfully!";
            $error_message = "Error updating FAQ.";
        } else {
            // Insert new FAQ
            $sql = "INSERT INTO faq (question, answer) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $question, $answer);
            $success_message = "FAQ added successfully!";
            $error_message = "Error adding FAQ.";
        }

        if ($stmt->execute()) {
            header("Location: A_FAQ.php?success=1&message=" . urlencode($success_message));
            exit();
        } else {
            header("Location: A_FAQ.php?error=1&message=" . urlencode($error_message));
            exit();
        }
    }
}

// Handle deleting FAQ
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM faq WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        header("Location: A_FAQ.php?success=1&message=" . urlencode("FAQ deleted successfully!"));
        exit();
    } else {
        header("Location: A_FAQ.php?error=1&message=" . urlencode("Error deleting FAQ."));
        exit();
    }
}

// Fetch FAQ for editing (if edit_id is set)
$faq = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM faq WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $faq = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage FAQ</title>
    <link rel="stylesheet" href="./css/styles.css">
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
        <a href="A_FAQ.php" class="active"><i class="fas fa-question-circle"></i> FAQ</a>
        <a href="A_Dashboard.php" ><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <h4>Manage FAQs</h4>

            <!-- Success and Error Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class='alert success'><?= htmlspecialchars($_GET['message']) ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class='alert error'><?= htmlspecialchars($_GET['message']) ?></div>
            <?php endif; ?>

            <!-- Add FAQ Button -->
            <div class="add-faq-button">
                <button class="btn btn-primary" onclick="openAddFAQModal()">Add FAQ</button>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <table id="faq-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM faq";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0):
                            while($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['question']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['answer'])) ?></td>
                            <td>
                                <a href="A_FAQ.php?edit_id=<?= $row['id'] ?>" class='edit-btn'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href="A_FAQ.php?delete_id=<?= $row['id'] ?>" class='delete-btn' 
                                   onclick="return confirm('Are you sure you want to delete this FAQ?');">
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan='3'>No FAQs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add FAQ Modal -->
    <div class="modal" id="addFAQModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add FAQ</h5>
                <button type="button" class="close" onclick="closeAddFAQModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="A_FAQ.php" method="POST">
                    <div class="form-group">
                        <label for="faq-question">Question:</label>
                        <input type="text" class="form-control" id="faq-question" name="faq-question" required>
                    </div>
                    <div class="form-group">
                        <label for="faq-answer">Answer:</label>
                        <textarea class="form-control" id="faq-answer" name="faq-answer" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add FAQ</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit FAQ Modal -->
    <div class="modal <?= isset($faq) ? 'show' : '' ?>" id="editFAQModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit FAQ</h5>
                <button type="button" class="close" onclick="closeEditFAQModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="A_FAQ.php" method="POST">
                    <input type="hidden" name="faq_id" value="<?= isset($faq) ? $faq['id'] : '' ?>">
                    <div class="form-group">
                        <label for="edit-faq-question">Question:</label>
                        <input type="text" class="form-control" id="edit-faq-question" name="faq-question" 
                               value="<?= isset($faq) ? htmlspecialchars($faq['question']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-faq-answer">Answer:</label>
                        <textarea class="form-control" id="edit-faq-answer" name="faq-answer" required><?= isset($faq) ? htmlspecialchars($faq['answer']) : '' ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update FAQ</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to open the Add FAQ modal
        function openAddFAQModal() {
            document.getElementById('addFAQModal').classList.add('show');
        }

        // Function to close the Add FAQ modal
        function closeAddFAQModal() {
            document.getElementById('addFAQModal').classList.remove('show');
        }

        // Function to open the Edit FAQ modal
        function openEditFAQModal() {
            document.getElementById('editFAQModal').classList.add('show');
        }

        // Function to close the Edit FAQ modal
        function closeEditFAQModal() {
            document.getElementById('editFAQModal').classList.remove('show');
            // Clear edit parameters from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        // Automatically open Edit FAQ modal if in edit mode
        <?php if (isset($_GET['edit_id'])): ?>
            window.onload = openEditFAQModal;
        <?php endif; ?>

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addFAQModal');
            const editModal = document.getElementById('editFAQModal');
            if (event.target === addModal) {
                closeAddFAQModal();
            }
            if (event.target === editModal) {
                closeEditFAQModal();
            }
        }
    </script>

</body>
</html>