<?php
session_start();
include('./conn/db_connect.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result || mysqli_num_rows($user_result) === 0) {
    die("Error fetching user details: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($user_result);

// Fetch user's orders
$query = "
    SELECT 
        c.cart_id, c.id AS product_id, c.quantity, c.total_price, c.added_at, c.payment_method, c.bank_slip,
        d.name AS shipping_name, d.email AS shipping_email, d.Address_no_1, d.Province, d.Phone_Number, d.Postal_code, d.order_status,
        p.name AS product_name, p.image AS product_image
    FROM cart c
    JOIN deliver_addreses d ON c.cart_id = d.cart_id
    JOIN products p ON c.id = p.id
    WHERE c.user_id = $user_id
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching orders: " . mysqli_error($conn));
}

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $cart_id = $_POST['cart_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    // Save feedback to the database
    $feedback_query = "INSERT INTO feedback (cart_id, user_id, rating, feedback) VALUES ($cart_id, $user_id, $rating, '$feedback')";
    if (mysqli_query($conn, $feedback_query)) {
        echo "<script>alert('Feedback submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting feedback: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <!-- Link to external CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Modern Header -->
    <header class="header">
        <!-- Brand Section -->
        <div class="brand">
            <a href="index.php" class="navbar-brand">
                <i class="fas fa-home"></i> Back to Site
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="nav">
            <!-- Profile Link -->
            <a href="user_dashboard.php" class="nav-link">
                <i class="fas fa-user-circle"></i> Profile
            </a>

            <!-- Tickets Link -->
            <a href="user_ticket.php" class="nav-link">
                <i class="fas fa-users-cog"></i> Tickets
            </a>

            <!-- Orders Link -->
            <a href="user_oder.php" class="nav-link active">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>

            <!-- Services Link -->
            <a href="<?php echo ($user['user_type'] == 'admin') ? 'admin_view.php' : 'user_repair.php'; ?>" class="nav-link">
                <i class="fas fa-tools"></i> Services
            </a>
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
            <h4>My Orders</h4>

            <?php if (empty($orders)): ?>
                <p>No orders found. <a href="store.php" class="btn btn-primary">Continue Shopping</a></p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card" data-status="<?php echo strtolower($order['order_status']); ?>">
                        <div class="product-info">
                            <img src="<?php echo $order['product_image']; ?>" alt="<?php echo $order['product_name']; ?>">
                            <h4><?php echo $order['product_name']; ?></h4>
                        </div>
                        <div class="details">
                            <p><strong>Order ID:</strong> <?php echo $order['cart_id']; ?></p>
                            <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
                            <p><strong>Total Price:</strong> $<?php echo $order['total_price']; ?></p>
                            <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                            <p class="status status-<?php echo strtolower($order['order_status']); ?>">
                                <strong>Status:</strong> <?php echo ucfirst($order['order_status']); ?>
                            </p>
                        </div>
                        <!-- Delivery Progress Bar -->
                        <div class="delivery-progress">
                            <div class="step <?php echo $order['order_status'] === 'pending' ? 'active' : ''; ?>">
                                <div class="icon"><i class="fas fa-clock"></i></div>
                                <p>Pending</p>
                            </div>
                            <div class="step <?php echo $order['order_status'] === 'confirmed' ? 'active' : ''; ?>">
                                <div class="icon"><i class="fas fa-check"></i></div>
                                <p>Confirmed</p>
                            </div>
                            <div class="step <?php echo $order['order_status'] === 'delivered' ? 'active' : ''; ?>">
                                <div class="icon"><i class="fas fa-truck"></i></div>
                                <p>Delivered</p>
                            </div>
                        </div>
                        <div class="actions">
                            <a href="#" title="View Delivery Details" onclick="openDeliveryDetailsModal(<?php echo $order['cart_id']; ?>)"><i class="fas fa-truck"></i></a>
                            <?php if ($order['order_status'] === 'delivered'): ?>
                                <a href="#" title="Add Feedback" onclick="openFeedbackModal(<?php echo $order['cart_id']; ?>)"><i class="fas fa-comment"></i></a>
                            <?php endif; ?>
                            <a href="#" title="Download Receipt" onclick="downloadReceipt(<?php echo $order['cart_id']; ?>)"><i class="fas fa-download"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeFeedbackModal()">&times;</span>
            <h2>Submit Feedback</h2>
            <form action="" method="POST">
                <input type="hidden" id="cart_id" name="cart_id">
                <div class="star-rating">
                    <span class="star" onclick="rate(1)">&#9733;</span>
                    <span class="star" onclick="rate(2)">&#9733;</span>
                    <span class="star" onclick="rate(3)">&#9733;</span>
                    <span class="star" onclick="rate(4)">&#9733;</span>
                    <span class="star" onclick="rate(5)">&#9733;</span>
                </div>
                <input type="hidden" id="rating" name="rating" value="0">
                <label for="feedback">Feedback:</label>
                <textarea id="feedback" name="feedback" rows="5" required></textarea>
                <button type="submit" name="submit_feedback">Submit</button>
            </form>
        </div>
    </div>

    <!-- Delivery Details Modal -->
    <div id="deliveryDetailsModal" class="delivery-details-modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeliveryDetailsModal()">&times;</span>
            <h2>Delivery Details</h2>
            <div id="deliveryDetailsContent"></div>
        </div>
    </div>

    <script>
        // Function to open feedback modal
        function openFeedbackModal(cartId) {
            document.getElementById('cart_id').value = cartId;
            document.getElementById('feedbackModal').style.display = 'block';
        }

        // Function to close feedback modal
        function closeFeedbackModal() {
            document.getElementById('feedbackModal').style.display = 'none';
        }

        // Function to open delivery details modal
        function openDeliveryDetailsModal(cartId) {
            var order = <?php echo json_encode($orders); ?>.find(o => o.cart_id == cartId);
            if (order) {
                var deliveryDetailsContent = `
                    <p><strong>Name:</strong> ${order.shipping_name}</p>
                    <p><strong>Email:</strong> ${order.shipping_email}</p>
                    <p><strong>Address:</strong> ${order.Address_no_1}</p>
                    <p><strong>Province:</strong> ${order.Province}</p>
                    <p><strong>Phone:</strong> ${order.Phone_Number}</p>
                    <p><strong>Postal Code:</strong> ${order.Postal_code}</p>
                `;
                document.getElementById('deliveryDetailsContent').innerHTML = deliveryDetailsContent;
                document.getElementById('deliveryDetailsModal').style.display = 'block';
            }
        }

        // Function to close delivery details modal
        function closeDeliveryDetailsModal() {
            document.getElementById('deliveryDetailsModal').style.display = 'none';
        }

        // Function to download receipt
        function downloadReceipt(cartId) {
            var order = <?php echo json_encode($orders); ?>.find(o => o.cart_id == cartId);
            if (order) {
                var receiptContent = `
                    <h3>Order Receipt</h3>
                    <p><strong>Order ID:</strong> ${order.cart_id}</p>
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

        // Function to handle star rating
        function rate(rating) {
            const stars = document.querySelectorAll('.star-rating .star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            document.getElementById('rating').value = rating;
        }
    </script>
</body>
</html>