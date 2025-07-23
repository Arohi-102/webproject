<?php
ob_start(); // Start output buffering
include('./conn/db_connect.php');
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

    <style>
        /* Center modals and prevent scrolling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Action icons color */
        .action-icons a {
            color: black; /* Set icons to black */
            margin: 0 5px;
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        /* Tabs styling */
        .tab {
            overflow: hidden;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px; /* Space between tabs and category filter */
        }

        .tab button {
            background-color: inherit;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 10px 20px;
            transition: 0.3s;
            font-size: 16px;
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #f1f1f1;
            border-bottom: 2px solid #007bff;
        }

        /* Notification badge */
        .badge {
            background-color: red;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 12px;
            margin-left: 5px;
        }

       

        .status-form select:hover {
            background-color: #e9e9e9;
        }

        /* Category filter dropdown */
        .category-filter {
            margin-left: auto; /* Push the filter to the right */
        }

        
    </style>
</head>
<body>

     <!-- Sidebar -->
     <div class="sidebar">
        <a class="navbar-brand" href="home.php">Admin Dashboard</a>
          <a href="Home.php" ><i class="fas fa-users-cog" ></i> Manage Users</a>
    <a href="A_ticket.php" class="active"><i class="fas fa-ticket-alt"></i> Tickets</a>
    <a href="admin_view.php"><i class="fas fa-calendar-check"></i> Services</a>
    <a href="A_order.php"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="A_store.php"><i class="fas fa-store"></i> Store</a>
        <a href="A_FAQ.php"><i class="fas fa-question-circle"></i> FAQ</a>
        <a href="A_Dashboard.php" ><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <h4>Admin Ticket Dashboard</h4>

            <!-- Success and Error Messages -->
            <?php
                if (isset($_GET['success'])) {
                    echo "<div class='alert success'>Ticket updated successfully!</div>";
                } elseif (isset($_GET['error'])) {
                    echo "<div class='alert error'>Error updating ticket. Please try again!</div>";
                }
            ?>

            <!-- Tabs for Ticket Categories and Category Filter -->
            <div class="tab">
                <button class="tablinks active" onclick="filterTickets('all')">All Tickets</button>
                <button class="tablinks" onclick="filterTickets('new')">New Tickets <span class="badge" id="newTicketBadge"><?php echo getNewTicketCount($conn); ?></span></button>
                <button class="tablinks" onclick="filterTickets('open')">Open Tickets</button>
                <button class="tablinks" onclick="filterTickets('closed')">Closed Tickets</button>

                <!-- Category Filter Dropdown -->
                <div class="category-filter">
                    <select id="categoryFilter" onchange="filterByCategory()">
                        <option value="all">All Category</option>
                        <option value="software">Software</option>
                        <option value="hardware">Hardware</option>
                        <option value="network">Network</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, or issue..." onkeyup="searchTickets()">
            </div>

            <!-- Ticket Table -->
            <table id="ticketTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM tickets";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr data-search="<?= strtolower($row['name'] . ' ' . $row['email'] . ' ' . $row['issue']) ?>" data-status="<?= strtolower($row['status']) ?>" data-category="<?= strtolower($row['category']) ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['priority']; ?></td>
                            <td><?php echo $row['issue']; ?></td>
                            <td>
                                <form method="POST" class="status-form" onsubmit="updateStatus(event, <?php echo $row['id']; ?>)">
                                    <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="New" <?php echo $row['status'] == 'New' ? 'selected' : ''; ?>>New</option>
                                        <option value="Open" <?php echo $row['status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
                                        <option value="Closed" <?php echo $row['status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="action-icons">
                                <!-- View Icon -->
                                <a href="#" onclick="openViewForm(<?php echo $row['id']; ?>)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                &nbsp; <!-- Space between icons -->
                                <!-- Delete Icon -->
                                <a href="A_ticket.php?delete=<?php echo $row['id']; ?>" title="Delete Ticket" onclick="return confirm('Are you sure you want to delete this ticket?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                &nbsp; <!-- Space between icons -->
                                <!-- Update Solution Icon -->
                                <a href="#" onclick="openUpdateForm(<?php echo $row['id']; ?>)" title="Update Solution">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- View Details Modal -->
                        <div id="viewForm<?php echo $row['id']; ?>" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeViewForm(<?php echo $row['id']; ?>)">&times;</span>
                                <h3>Ticket Details</h3>
                                <div class="form-group">
                                    <label>ID:</label>
                                    <p><?php echo $row['id']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>User ID:</label>
                                    <p><?php echo $row['user_id']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>Name:</label>
                                    <p><?php echo $row['name']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <p><?php echo $row['email']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>Category:</label>
                                    <p><?php echo $row['category']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>Priority:</label>
                                    <p><?php echo $row['priority']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>Issue:</label>
                                    <p><?php echo $row['issue']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>File:</label>
                                    <p><a href="<?php echo $row['file']; ?>" target="_blank">View File</a></p>
                                </div>
                                <div class="form-group">
                                    <label>Status:</label>
                                    <p><?php echo $row['status']; ?></p>
                                </div>
                                <div class="form-group">
                                    <label>Solution:</label>
                                    <p><?php echo $row['solution'] ? $row['solution'] : 'No solution yet'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Update Form Modal -->
                        <div id="updateForm<?php echo $row['id']; ?>" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeUpdateForm(<?php echo $row['id']; ?>)">&times;</span>
                                <h3>Update Ticket</h3>
                                <form method="POST">
                                    <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">
                                    <div class="form-group">
                                        <label>Category:</label>
                                        <input type="text" name="category" value="<?php echo $row['category']; ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Priority:</label>
                                        <input type="text" name="priority" value="<?php echo $row['priority']; ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Issue:</label>
                                        <textarea name="issue" class="form-control"><?php echo $row['issue']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Solution:</label>
                                        <textarea name="solution" class="form-control"><?php echo $row['solution']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="update_solution" value="Update Solution" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to update ticket status
        function updateStatus(event, ticketId) {
            event.preventDefault(); // Prevent the default form submission

            var form = event.target;
            var formData = new FormData(form);

            fetch('A_ticket.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); // Reload the page to reflect the updated status
                } else {
                    alert('Error updating status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Function to filter tickets by status
        function filterTickets(status) {
            var rows = document.querySelectorAll("#ticketTable tbody tr");
            rows.forEach(function(row) {
                var rowStatus = row.getAttribute("data-status");
                if (status === "all" || rowStatus === status) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            // Update active tab
            var tabs = document.querySelectorAll(".tablinks");
            tabs.forEach(function(tab) {
                tab.classList.remove("active");
            });
            event.currentTarget.classList.add("active");
        }

        // Function to filter tickets by category
        function filterByCategory() {
            var category = document.getElementById("categoryFilter").value;
            var rows = document.querySelectorAll("#ticketTable tbody tr");
            rows.forEach(function(row) {
                var rowCategory = row.getAttribute("data-category");
                if (category === "all" || rowCategory === category) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Function to search tickets
        function searchTickets() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var table = document.getElementById('ticketTable');
            var rows = table.getElementsByTagName('tr');
            
            for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                var text = row.getAttribute('data-search');
                if (text.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Function to open view details modal
        function openViewForm(id) {
            var modal = document.getElementById('viewForm' + id);
            modal.style.display = 'block';
            document.body.classList.add('modal-open'); // Prevent body scroll
        }

        // Function to close view details modal
        function closeViewForm(id) {
            var modal = document.getElementById('viewForm' + id);
            modal.style.display = 'none';
            document.body.classList.remove('modal-open'); // Restore body scroll
        }

        // Function to open update form modal
        function openUpdateForm(id) {
            var modal = document.getElementById('updateForm' + id);
            modal.style.display = 'block';
            document.body.classList.add('modal-open'); // Prevent body scroll
        }

        // Function to close update form modal
        function closeUpdateForm(id) {
            var modal = document.getElementById('updateForm' + id);
            modal.style.display = 'none';
            document.body.classList.remove('modal-open'); // Restore body scroll
        }
    </script>

</body>
</html>

<?php
// Function to get count of new tickets
function getNewTicketCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM tickets WHERE status = 'New'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Update ticket status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $ticket_id = $_POST['ticket_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $ticket_id);
    
    if ($stmt->execute()) {
        header("Location: A_ticket.php");
        exit;
    } else {
        header("Location: A_ticket.php");
        exit;
    }

    $stmt->close();
}

// Update ticket solution
if (isset($_POST['update_solution'])) {
    $ticket_id = $_POST['ticket_id'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $issue = $_POST['issue'];
    $solution = $_POST['solution'];

    $stmt = $conn->prepare("UPDATE tickets SET category = ?, priority = ?, issue = ?, solution = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $category, $priority, $issue, $solution, $ticket_id);
    
    if ($stmt->execute()) {
        header("Location: A_ticket.php");
        exit;
    } else {
        header("Location: A_ticket.php");
        exit;
    }

    $stmt->close();
}


// Delete ticket
if (isset($_GET['delete'])) {
    $ticket_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    
    if ($stmt->execute()) {
        header("Location: A_ticket.php");
        exit;
    } else {
        header("Location: A_ticket.php");
        exit;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>