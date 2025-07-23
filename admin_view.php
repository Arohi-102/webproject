<?php
include('./conn/db_connect.php');

// Handle deleting a booking
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM repair_requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        header("Location: admin_view.php?success=1");
        exit();
    } else {
        header("Location: admin_view.php?error=1");
        exit();
    }
}

// Handle scheduling a booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_id'], $_POST['schedule_time'])) {
    $schedule_id = $_POST['schedule_id'];
    $schedule_time = $_POST['schedule_time'];

    if (!empty($schedule_time)) {
        $sql = "UPDATE repair_requests SET scheduled_time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $schedule_time, $schedule_id);

        if ($stmt->execute()) {
            header("Location: admin_view.php?schedule_success=1");
            exit();
        } else {
            header("Location: admin_view.php?schedule_error=1");
            exit();
        }
    } else {
        header("Location: admin_view.php?schedule_error=1");
        exit();
    }
}

// Fetch counts for Home and Remote service types where scheduled_time is NULL or empty
$sql_home_count = "SELECT COUNT(*) as home_count FROM repair_requests WHERE service_type = 'Home' AND (scheduled_time IS NULL OR scheduled_time = '')";
$sql_remote_count = "SELECT COUNT(*) as remote_count FROM repair_requests WHERE service_type = 'Remote' AND (scheduled_time IS NULL OR scheduled_time = '')";

$home_count_result = $conn->query($sql_home_count);
$remote_count_result = $conn->query($sql_remote_count);

$home_count = $home_count_result->fetch_assoc()['home_count'];
$remote_count = $remote_count_result->fetch_assoc()['remote_count'];

// Handle filtering by service type and schedule status
$service_type_filter = isset($_GET['service_type']) ? $_GET['service_type'] : 'all';
$schedule_filter = isset($_GET['schedule_filter']) ? $_GET['schedule_filter'] : 'all';

$sql = "SELECT * FROM repair_requests";
$where_clauses = [];

if ($service_type_filter !== 'all') {
    $where_clauses[] = "service_type = '$service_type_filter'";
}

if ($schedule_filter === 'scheduled') {
    $where_clauses[] = "scheduled_time IS NOT NULL AND scheduled_time != ''";
} elseif ($schedule_filter === 'not_scheduled') {
    $where_clauses[] = "scheduled_time IS NULL OR scheduled_time = ''";
}

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Bookings</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Tab Styling */
        .tab {
            overflow: hidden;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .tab button {
            background-color: inherit;
            float: left;
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

        /* Dropdown Styling */
        .schedule-filter {
            margin-left: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Notification Badge */
        .badge {
            background-color: red;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 12px;
            margin-left: 5px;
        }

        /* Popup Styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 400px;
            max-width: 90%;
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup.active, .popup-overlay.active {
            display: block;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .popup-header h3 {
            margin: 0;
        }

        .popup-header .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
  
     <!-- Sidebar -->
     <div class="sidebar">
        <a class="navbar-brand" href="home.php">Admin Dashboard</a>
          <a href="Home.php" ><i class="fas fa-users-cog" ></i> Manage Users</a>
    <a href="A_ticket.php" ><i class="fas fa-ticket-alt"></i> Tickets</a>
    <a href="admin_view.php" class="active"><i class="fas fa-calendar-check"></i> Services</a>
    <a href="A_order.php"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="A_store.php"><i class="fas fa-store"></i> Store</a>
        <a href="A_FAQ.php"><i class="fas fa-question-circle"></i> FAQ</a>
        <a href="A_Dashboard.php" ><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

    <div class="main-content">
        <div class="content">
            <h4>Manage User Bookings</h4>

            <!-- Filter Tabs -->
            <div class="tab">
                <button class="tablinks <?php echo ($service_type_filter === 'all') ? 'active' : ''; ?>" onclick="filterTickets('all')">All Services</button>
                <button class="tablinks <?php echo ($service_type_filter === 'Home') ? 'active' : ''; ?>" onclick="filterTickets('Home')">Home <span class="badge"><?php echo $home_count; ?></span></button>
                <button class="tablinks <?php echo ($service_type_filter === 'Remote') ? 'active' : ''; ?>" onclick="filterTickets('Remote')">Remote <span class="badge"><?php echo $remote_count; ?></span></button>
            </div>

            <!-- Schedule Filter Dropdown -->
            <?php if ($service_type_filter !== 'all'): ?>
                <div>
                    <label for="schedule_filter">Filter by Schedule:</label>
                    <select id="schedule_filter" class="schedule-filter" onchange="applyScheduleFilter()">
                        <option value="all" <?php echo ($schedule_filter === 'all') ? 'selected' : ''; ?>>All</option>
                        <option value="scheduled" <?php echo ($schedule_filter === 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                        <option value="not_scheduled" <?php echo ($schedule_filter === 'not_scheduled') ? 'selected' : ''; ?>>Not Scheduled</option>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])) echo "<div class='alert success'>Booking deleted successfully!</div>"; ?>
            <?php if (isset($_GET['error'])) echo "<div class='alert error'>Error deleting booking.</div>"; ?>
            <?php if (isset($_GET['schedule_success'])) echo "<div class='alert success'>Schedule saved successfully!</div>"; ?>
            <?php if (isset($_GET['schedule_error'])) echo "<div class='alert error'>Error saving schedule.</div>"; ?>

            <table id="booking-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Scheduled Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($booking = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($booking['user_id']) . "</td>
                                    <td>" . htmlspecialchars($booking['name']) . "</td>
                                    <td>" . htmlspecialchars($booking['email']) . "</td>
                                    <td>" . htmlspecialchars($booking['phone']) . "</td>
                                    <td>" . (!empty($booking['scheduled_time']) ? htmlspecialchars($booking['scheduled_time']) : "Not Scheduled") . "</td>
                                    <td>
                                        <form method='POST' action='admin_view.php' style='display:inline-block;'>
                                            <input type='hidden' name='schedule_id' value='" . $booking['id'] . "'>
                                            <input type='datetime-local' name='schedule_time' required>
                                            <button type='submit' class='schedule-btn'><i class='fas fa-save'></i></button>
                                        </form>
                                        <a href='A_Bookings.php?delete_id=" . $booking['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this booking?\");'>
                                            <i class='fas fa-trash-alt'></i>
                                        </a>
                                        <button class='view-btn' onclick='openDetailsPopup(" . json_encode($booking) . ")'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popup for Viewing Full Details -->
    <div class="popup-overlay" id="detailsPopupOverlay"></div>
    <div class="popup" id="detailsPopup">
        <div class="popup-header">
            <h3>Booking Details</h3>
            <button class="close-btn" onclick="closeDetailsPopup()"><i class='fas fa-times'></i></button>
        </div>
        <div id="popupContent"></div>
    </div>

    <script>
        // Function to open the details popup
        function openDetailsPopup(booking) {
            const popupContent = `
                <p><strong>User ID:</strong> ${booking.user_id}</p>
                <p><strong>Name:</strong> ${booking.name}</p>
                <p><strong>Email:</strong> ${booking.email}</p>
                <p><strong>Phone:</strong> ${booking.phone}</p>
                <p><strong>Category:</strong> ${booking.category}</p>
                <p><strong>Issue:</strong> ${booking.issue}</p>
                <p><strong>Service Type:</strong> ${booking.service_type}</p>
                <p><strong>Address:</strong> ${booking.address}</p>
                <p><strong>Submitted At:</strong> ${booking.submitted_at}</p>
                <p><strong>Scheduled Time:</strong> ${booking.scheduled_time || 'Not Scheduled'}</p>
            `;
            document.getElementById('popupContent').innerHTML = popupContent;
            document.getElementById('detailsPopup').classList.add('active');
            document.getElementById('detailsPopupOverlay').classList.add('active');
        }

        // Function to close the details popup
        function closeDetailsPopup() {
            document.getElementById('detailsPopup').classList.remove('active');
            document.getElementById('detailsPopupOverlay').classList.remove('active');
        }

        // Function to filter tickets by service type
        function filterTickets(serviceType) {
            window.location.href = `?service_type=${serviceType}`;
        }

        // Function to apply schedule filter
        function applyScheduleFilter() {
            const scheduleFilter = document.getElementById('schedule_filter').value;
            const serviceType = "<?php echo $service_type_filter; ?>";
            window.location.href = `?service_type=${serviceType}&schedule_filter=${scheduleFilter}`;
        }
    </script>
</body>
</html>