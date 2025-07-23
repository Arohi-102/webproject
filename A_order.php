<?php
session_start();

// Display success message if set
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']); // Clear the message after displaying
}

include('./conn/db_connect.php');

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the `cart` table exists
$tableCheckQuery = "SHOW TABLES LIKE 'cart'";
$tableCheckResult = mysqli_query($conn, $tableCheckQuery);

if (mysqli_num_rows($tableCheckResult) == 0) {
    die("The 'cart' table does not exist in the database. Please create it.");
}

// Fetch all orders from the cart table with user, product, and payment details
$query = "
    SELECT 
        c.cart_id, c.user_id, c.id AS product_id, c.quantity, c.total_price, c.added_at, c.payment_method, c.bank_slip,
        d.name AS shipping_name, d.email AS shipping_email, d.Address_no_1, d.Province, d.Phone_Number, d.Postal_code, d.order_status,
        p.name AS product_name, p.image AS product_image
    FROM cart c
    JOIN deliver_addreses d ON c.cart_id = d.cart_id
    JOIN products p ON c.id = p.id
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching orders: " . mysqli_error($conn));
}

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// Function to get count of pending orders
function getPendingOrderCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM deliver_addreses WHERE order_status = 'pending'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Orders</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Compact table styling */
        .compact-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9em;
        }
        
        .compact-table th, .compact-table td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .compact-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .compact-table tr:hover {
            background-color: #f5f5f5;
        }
        
        /* Product image styling */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .product-cell img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        /* Status dropdown */
        .status-select {
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 0.9em;
        }
        
        /* Action buttons */
        .action-btn {
            background: none;
            border: none;
            color: #555;
            cursor: pointer;
            font-size: 0.9em;
            margin: 0 3px;
            padding: 4px;
        }
        
        .action-btn:hover {
            color: #333;
        }
        
        /* Shipping details summary */
        .shipping-summary {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Modal and other styles remain the same */
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

        /* Tabs styling */
        .tab {
            overflow: hidden;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
    </style>
</head>
<body>
    <!-- Sidebar remains the same -->
    <div class="sidebar">
        <a class="navbar-brand" href="home.php">Admin Dashboard</a>
        <a href="Home.php"><i class="fas fa-users-cog"></i> Manage Users</a>
        <a href="A_ticket.php"><i class="fas fa-ticket-alt"></i> Tickets</a>
        <a href="admin_view.php"><i class="fas fa-calendar-check"></i> Services</a>
        <a href="A_order.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="A_store.php"><i class="fas fa-store"></i> Store</a>
        <a href="A_FAQ.php"><i class="fas fa-question-circle"></i> FAQ</a>
        <a href="A_Dashboard copy.php"><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <h4>Manage Orders</h4>

            <!-- Tabs for Order Status -->
            <div class="tab">
                <button class="tablinks active" onclick="filterOrders('all')">All Orders</button>
                <button class="tablinks" onclick="filterOrders('pending')">Pending <span class="badge"><?php echo getPendingOrderCount($conn); ?></span></button>
                <button class="tablinks" onclick="filterOrders('confirmed')">Confirmed</button>
                <button class="tablinks" onclick="filterOrders('delivered')">Delivered</button>

                <!-- Payment Method Filter Dropdown -->
                <div class="payment-filter" style="margin-left: auto;">
                    <select id="paymentFilter" onchange="filterByPaymentMethod()">
                        <option value="all">All Payment Methods</option>
                        <option value="Cash-on-Delivery">Cash on Delivery</option>
                        <option value="Bank-Transfer">Bank Transfer</option>
                    </select>
                </div>
            </div>

            <?php if (empty($orders)): ?>
                <p>No orders found.</p>
            <?php else: ?>
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Shipping</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr data-status="<?= strtolower($order['order_status']) ?>" data-payment="<?= strtolower($order['payment_method']) ?>">
                            <td><?= $order['cart_id'] ?></td>
                            <td class="product-cell">
                                <img src="<?= $order['product_image'] ?>" alt="<?= $order['product_name'] ?>">
                                <span><?= $order['product_name'] ?></span>
                            </td>
                            <td><?= $order['quantity'] ?></td>
                            <td>$<?= number_format($order['total_price'], 2) ?></td>
                            <td class="shipping-summary" title="<?= htmlspecialchars($order['shipping_name'] . ', ' . $order['Address_no_1'] . ', ' . $order['Province']) ?>">
                                <?= $order['shipping_name'] ?>
                            </td>
                            <td>
                                <form action="endpoint/update_order_status.php" method="POST" class="status-form">
                                    <input type="hidden" name="cart_id" value="<?= $order['cart_id'] ?>">
                                    <select name="order_status" class="status-select" onchange="this.form.submit()">
                                        <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="confirmed" <?= $order['order_status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                        <option value="delivered" <?= $order['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button class="action-btn" title="View" onclick="viewOrderDetails(<?= $order['cart_id'] ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn" title="Delete" onclick="confirmDelete(<?= $order['cart_id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button class="action-btn" title="Print" onclick="printReceipt(<?= $order['cart_id'] ?>)">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to filter orders by status
        function filterOrders(status) {
            var rows = document.querySelectorAll(".compact-table tbody tr");
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

        // Function to filter orders by payment method
        function filterByPaymentMethod() {
            var paymentMethod = document.getElementById("paymentFilter").value.toLowerCase();
            var rows = document.querySelectorAll(".compact-table tbody tr");
            rows.forEach(function(row) {
                var rowPayment = row.getAttribute("data-payment");
                if (paymentMethod === "all" || rowPayment === paymentMethod) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Function to print receipt
        function printReceipt(cartId) {
            var order = <?php echo json_encode($orders); ?>.find(o => o.cart_id == cartId);
            if (order) {
                var receiptContent = `
                    <h3>Order Receipt</h3>
                    <p><strong>Order ID:</strong> ${order.cart_id}</p>
                    <p><strong>User ID:</strong> ${order.user_id}</p>
                    <p><strong>Product:</strong> ${order.product_name}</p>
                    <p><strong>Quantity:</strong> ${order.quantity}</p>
                    <p><strong>Total Price:</strong> $${order.total_price}</p>
                    <p><strong>Shipping Details:</strong></p>
                    <p>Name: ${order.shipping_name}</p>
                    <p>Email: ${order.shipping_email}</p>
                    <p>Address: ${order.Address_no_1}</p>
                    <p>Province: ${order.Province}</p>
                    <p>Phone: ${order.Phone_Number}</p>
                    <p>Postal Code: ${order.Postal_code}</p>
                `;
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>Receipt</title></head><body>');
                printWindow.document.write(receiptContent);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }
        }

        // Function to view order details
        function viewOrderDetails(cartId) {
            var order = <?php echo json_encode($orders); ?>.find(o => o.cart_id == cartId);
            if (order) {
                var detailsContent = `
                    <h3>Order Details</h3>
                    <p><strong>Order ID:</strong> ${order.cart_id}</p>
                    <p><strong>User ID:</strong> ${order.user_id}</p>
                    <p><strong>Product:</strong> ${order.product_name}</p>
                    <p><strong>Quantity:</strong> ${order.quantity}</p>
                    <p><strong>Total Price:</strong> $${order.total_price}</p>
                    <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                    ${order.bank_slip ? `<p><strong>Bank Slip:</strong> <a href="${order.bank_slip}" target="_blank">View Slip</a></p>` : ''}
                    <p><strong>Shipping Details:</strong></p>
                    <p>Name: ${order.shipping_name}</p>
                    <p>Email: ${order.shipping_email}</p>
                    <p>Address: ${order.Address_no_1}</p>
                    <p>Province: ${order.Province}</p>
                    <p>Phone: ${order.Phone_Number}</p>
                    <p>Postal Code: ${order.Postal_code}</p>
                `;
                var modal = document.createElement('div');
                modal.className = 'modal';
                modal.innerHTML = `
                    <div class="modal-content">
                        <span class="close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span>
                        ${detailsContent}
                    </div>
                `;
                document.body.appendChild(modal);
                modal.style.display = 'block';
            }
        }

        // Function to confirm delete and redirect to delete endpoint
        function confirmDelete(cartId) {
            if (confirm("Are you sure you want to delete this order?")) {
                window.location.href = "endpoint/delete_order.php?id=" + cartId;
            }
        }
    </script>
</body>
</html>