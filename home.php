<?php include('./conn/db_connect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration and Login System</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/styles.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

   <!-- Sidebar -->
   <div class="sidebar">
        <a class="navbar-brand" href="home.php">Admin Dashboard</a>
          <a href="Home.php" class="active"><i class="fas fa-users-cog" ></i> Manage Users</a>
    <a href="A_ticket.php"><i class="fas fa-ticket-alt"></i> Tickets</a>
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
            <h4>List of Users</h4>

            <!-- Success and Error Messages -->
            <?php
                if (isset($_GET['success']) && $_GET['success'] == '1') {
                    echo "<div class='alert success'>User updated successfully!</div>";
                } elseif (isset($_GET['error']) && $_GET['error'] == '1') {
                    echo "<div class='alert error'>Error updating user. Please try again!</div>";
                }
            ?>
            
            <!-- Search Bar and Add User Button -->
            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, contact number, or username..." onkeyup="searchUsers()">
                <button class="btn" onclick="openAddUserModal()">Add User</button>
            </div>

            <table id="userTable">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Username</th>
                        <th>User Type</th>
                        <th>Address</th> <!-- New Column -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        // Query to get user details from the 'users' table
                        $stmt = $conn->prepare("SELECT * FROM `users`");
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Fetch all rows as associative arrays
                        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

                        if (empty($users)) {
                            echo "<tr><td colspan='7'>No users found</td></tr>";
                        } else {
                            foreach ($users as $row) {
                                $userID = $row['user_id'];
                                $email = htmlspecialchars($row['email']);
                                $contactNumber = htmlspecialchars($row['contact_number']);
                                $username = htmlspecialchars($row['user_name']);
                                $userType = htmlspecialchars($row['user_type']);
                                $address = htmlspecialchars($row['address']); // New Address Field
                    ?>

                    <tr data-search="<?= strtolower($email . ' ' . $contactNumber . ' ' . $username . ' ' . $address) ?>">
                        <td id="userID-<?= $userID ?>"><?php echo $userID ?></td>
                        <td id="email-<?= $userID ?>"><?php echo $email ?></td>
                        <td id="contactNumber-<?= $userID ?>"><?php echo $contactNumber ?></td>
                        <td id="username-<?= $userID ?>"><?php echo $username ?></td>
                        <td id="userType-<?= $userID ?>"><?php echo $userType ?></td>
                        <td id="address-<?= $userID ?>"><?php echo $address ?></td> <!-- New Column -->
                        <td>
                            <button onclick="update_user(<?php echo $userID ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="delete_user(<?php echo $userID ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>

                    <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Update User Modal -->
    <div class="modal" id="updateUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update User</h5>
                <button type="button" class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/update-user.php" method="POST">
                    <input type="hidden" name="user_id" id="updateUserID">
                    <input type="email" class="form-control" id="updateEmail" name="email" placeholder="Email">
                    <input type="number" class="form-control" id="updateContactNumber" name="contact_number" placeholder="Contact Number">
                    <input type="text" class="form-control" id="updateUsername" name="username" placeholder="Username">
                    <input type="text" class="form-control" id="updateAddress" name="address" placeholder="Address"> <!-- New Address Field -->
                    <select class="form-control" id="updateUserType" name="user_type">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="representative">Representative</option>
                    </select>
                    <input type="password" class="form-control" id="updatePassword" name="password" placeholder="Password" readonly>
                    <button type="submit" class="btn">Update</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal" id="addUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close" onclick="closeAddUserModal()" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/add-user.php" method="POST" id="addUserForm">
                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            placeholder="Enter email" 
                            required
                        >
                    </div>

                    <!-- Contact Number Input -->
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="contact_number" 
                            name="contact_number" 
                            placeholder="Enter contact number" 
                            required
                        >
                    </div>

                    <!-- Username Input -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="username" 
                            name="username" 
                            placeholder="Enter username" 
                            required
                        >
                    </div>

                    <!-- Address Input -->
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="address" 
                            name="address" 
                            placeholder="Enter address" 
                            required
                        >
                    </div>

                    <!-- User Type Dropdown -->
                    <div class="form-group">
                        <label for="user_type">User Type</label>
                        <select 
                            class="form-control" 
                            id="user_type" 
                            name="user_type" 
                            required
                        >
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="representative">Representative</option>
                        </select>
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Enter password" 
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddUserModal() {
            document.getElementById('addUserModal').classList.add('show');
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.remove('show');
        }

        function update_user(id) {
            document.getElementById('updateUserModal').classList.add('show');
            document.getElementById('updateUserID').value = document.getElementById('userID-' + id).innerText;
            document.getElementById('updateEmail').value = document.getElementById('email-' + id).innerText;
            document.getElementById('updateContactNumber').value = document.getElementById('contactNumber-' + id).innerText;
            document.getElementById('updateUsername').value = document.getElementById('username-' + id).innerText;
            document.getElementById('updateAddress').value = document.getElementById('address-' + id).innerText; // New Address Field
            document.getElementById('updateUserType').value = document.getElementById('userType-' + id).innerText;
        }

        function closeModal() {
            document.getElementById('updateUserModal').classList.remove('show');
        }

        function delete_user(id) {
            if (confirm("Do you want to delete this user?")) {
                window.location = "./endpoint/delete-user.php?user=" + id;
            }
        }

        document.getElementById('updateUserModal').addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                closeModal();
            }
        });

        // Search function
        function searchUsers() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var table = document.getElementById('userTable');
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
    </script>
</body>
</html>