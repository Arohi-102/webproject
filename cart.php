<?php
session_start();
include('./conn/db_connect.php');

// Check if the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user details if logged in
$userDetails = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $query = "SELECT user_name, email, contact_number, address FROM users WHERE user_id = $userId";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $userDetails = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SmartAssist - Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/form_styles.css">
</head>
<body>
    <nav>
        <div class="logo-nav">
            <img src="image/logo.png" alt="Logo" class="logo" style="width: 100px; height: 150px;">
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="Ticket.php">Support</a>
            <a href="Repair.php">Repair Services</a>
            <a href="store.php" class="active">Parts Store</a>
            <a href="FAQ.php">FAQ</a>
        </div>

        <?php
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'admin') {
                echo '<div><a href="A_Dashboard.php">Admin Panel</a></div>';
            } elseif ($_SESSION['user_type'] == 'representative') {
                echo '<div><a href="representative_dashboard.php">Representative Panel</a></div>';
            } else {
                // User Panel with Dropdown
                echo '<div class="user-panel">
                        <a href="" class="user-panel-btn">
                            User Panel <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown">
                            <a href="user_dashboard.php">My Profile</a>
                            <a href="user_ticket.php">Solution</a>
                            <a href="user_repair.php">Services</a>
                            <a href="user_oder.php">My order</a>
                        </div>
                    </div>';
            }
        }
        ?>

        <div class="left-icons">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<a href="logout.php" class="login-icon" title="Logout">
                        <i class="fas fa-sign-out-alt fa-2x"></i>
                        <span>Log out</span>
                    </a>';
            } else {
                echo '<a href="main.php" class="login-icon" title="Login">
                        <i class="fas fa-user-circle fa-2x"></i>
                        <span>Login</span>
                    </a>';
            }
            ?>
        </div>
    </nav>
<br><br><br>

    <section class="cart-container">
        <!-- Cart Items Section --><a href="store.php" style="text-decoration: none; color: black;">←</a> 
        <div class="cart-items-section">
            <h2>Your Cart</h2>

            <!-- User Details Section -->
            <div class="user-details-section">
                <h3>Shipping Details <i class="fas fa-edit" onclick="toggleEditShippingDetails()"></i></h3><br>
                <input type="text" id="Name" placeholder="Your Name" value="<?php echo $userDetails['user_name'] ?? ''; ?>">
                <input type="text" id="E_mail" placeholder="Your E-mail" value="<?php echo $userDetails['email'] ?? ''; ?>">
                <input type="text" id="address_no_1" placeholder="Your Address Line 1">
                <input type="text" id="province" placeholder="Your Province">
                <input type="text" id="phone_number" placeholder="Your Phone Number" value="<?php echo $userDetails['contact_number'] ?? ''; ?>">
                <input type="text" id="postal_code" placeholder="Your Postal Code">
            </div>

            <!-- Select All Section -->
            <div class="select-all-section">
                <label>
                    <input type="checkbox" id="select-all" onchange="toggleSelectAll()"> Select All
                </label>
            </div>

            <!-- Cart Items -->
            <div id="cart-items"></div>
        </div>

        <!-- Order Summary Section -->
        <div class="order-summary-section">
            <h2>Order Summary</h2>
            <div id="order-summary-items"></div>
            <div class="cart-total">
                <strong>Total: $ <span id="order-summary-total">0.00</span></strong>
            </div>

            <!-- Payment Method Section -->
            <div class="payment-method-section">
                <h3>Payment Method</h3>
                <label>
                    <input type="radio" name="payment-method" value="cash-on-delivery" checked> Cash on Delivery
                </label>
                <label>
                    <input type="radio" name="payment-method" value="bank-transfer" onchange="toggleBankTransferDetails()"> Bank Transfer
                </label>
                <div id="bank-transfer-details" class="bank-transfer-details" style="display: none;">
                    <p>Please upload your payment slip after transferring the amount to the following account:</p>
                    <p><strong>Account Name:</strong> SmartAssist</p>
                    <p><strong>Account Number:</strong> 1234567890</p>
                    <p><strong>Bank:</strong> Example Bank</p>
                    <input type="file" id="payment-slip" accept="image/*">
                </div>
            </div>

            <!-- Place Order Button -->
            <button class="place-order-btn" onclick="placeOrder()">Place Order</button>
        </div>
    </section>

    <!-- Footer Section -->
    <footer style="background: #2d3a49; color: #fff; padding: 40px 20px;">
        <div style="background: linear-gradient(135deg, #4e6e88 50%, #2d3a49 50%); padding: 30px 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; max-width: 1200px; margin: auto; gap: 40px;">
                <div style="max-width: 300px; padding-right: 20px;">
                    <img src="image/logo.png" alt="Company Logo" style="width: 100px; height: auto; margin-bottom: 15px;">
                    <p style="font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 14px; color: #ddd; line-height: 1.6;">Providing quality services since 2020. We aim to deliver exceptional value to our clients through innovative solutions that meet their needs and exceed expectations.</p>
                </div>
                <div style="max-width: 220px;">
                    <h3 style="font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 18px; font-weight: bold; color: #fff; margin-bottom: 15px;">Quick Links</h3>
                    <ul style="list-style: none; padding: 0; font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 14px; color: #ddd; line-height: 1.8;">
                        <li><a href="#" style="text-decoration: none; color: #ddd; transition: color 0.3s;">Home</a></li>
                        <li><a href="#" style="text-decoration: none; color: #ddd; transition: color 0.3s;">About Us</a></li>
                        <li><a href="#" style="text-decoration: none; color: #ddd; transition: color 0.3s;">Services</a></li>
                        <li><a href="#" style="text-decoration: none; color: #ddd; transition: color 0.3s;">Contact</a></li>
                    </ul>
                </div>
                <div style="max-width: 250px;">
                    <h3 style="font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 18px; font-weight: bold; color: #fff; margin-bottom: 15px;">Contact Us</h3>
                    <p style="font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 14px; color: #ddd; margin-bottom: 5px;">Email: <a href="mailto:contact@company.com" style="color: #f1c40f; text-decoration: none;">contact@company.com</a></p>
                    <p style="font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 14px; color: #ddd;">Phone: +123 456 7890</p>
                    <div style="margin-top: 15px;">
                        <a href="#"><img src="image/social media/fb.png" alt="Facebook" style="width: 24px; height: 24px; margin-right: 10px; transition: transform 0.3s;"></a>
                        <a href="#"><img src="image/social media/twitter.png" alt="Twitter" style="width: 24px; height: 24px; margin-right: 10px; transition: transform 0.3s;"></a>
                        <a href="#"><img src="image/social media/linkedin.png" alt="LinkedIn" style="width: 24px; height: 24px; transition: transform 0.3s;"></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom" style="text-align: center; margin-top: 30px;">
                <p style="font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 14px; color: #bbb; margin-top: 15px;">&copy; 2024 Your Company. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Chatbot Section -->
    <div class="chatbot-container">
        <button class="chatbot-btn" onclick="toggleChatWindow()">💬</button>
        <div class="chat-window" style="display: none;">
            <header>SmartAssist Chat</header>
            <div class="chat-messages"></div>
            <div class="chat-input">
                <input type="text" placeholder="Type your message..." id="user-input">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        // Function to get the user-specific cart key
        function getCartKey() {
            return `cart_${<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest'; ?>}`;
        }

        // Load cart from localStorage
        function loadCart() {
            const cartKey = getCartKey();
            return JSON.parse(localStorage.getItem(cartKey)) || [];
        }

        // Save cart to localStorage
        function saveCart(cart) {
            const cartKey = getCartKey();
            localStorage.setItem(cartKey, JSON.stringify(cart));
        }

        let cart = loadCart(); // Initialize cart with user-specific data
        const cartItemsContainer = document.getElementById('cart-items');
        const orderSummaryItems = document.getElementById('order-summary-items');
        const orderSummaryTotal = document.getElementById('order-summary-total');

        // Update cart UI
        function updateCartUI() {
            cartItemsContainer.innerHTML = '';
            orderSummaryItems.innerHTML = '';
            let totalAmount = 0;

            cart.forEach((product, index) => {
                const uniqueId = `select-item-${product.id}-${index}`;

                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <input type="checkbox" class="item-checkbox" id="${uniqueId}" onchange="updateSelectedItems()">
                    <img src="${product.image}" alt="${product.name}">
                    <div class="cart-item-details">
                        <span>${product.name}</span>
                        <span>Quantity: ${product.quantity}</span>
                        <span>Price: $${(product.price * product.quantity).toFixed(2)}</span>
                    </div>
                    <button onclick="deleteItem(${product.id})"><i class="fas fa-trash"></i></button>
                `;
                cartItemsContainer.appendChild(cartItem);

                totalAmount += product.price * product.quantity;
            });

            orderSummaryTotal.textContent = totalAmount.toFixed(2);
            updateSelectedItems();
        }

        // Delete an item from the cart
        function deleteItem(id) {
            cart = cart.filter(product => Number(product.id) !== id);
            saveCart(cart);
            updateCartUI();
        }

        // Toggle select all items
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selectAllCheckbox = document.getElementById('select-all');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedItems();
        }

        // Update selected items in the order summary
        function updateSelectedItems() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            let selectedItems = [];
            let selectedTotal = 0;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const checkboxId = checkbox.id;
                    const parts = checkboxId.split('-');
                    const productId = parts[2];
                    const index = parts[3];

                    const product = cart.find(item => item.id == productId);
                    if (product) {
                        selectedItems.push(product);
                        selectedTotal += product.price * product.quantity;
                    }
                }
            });

            orderSummaryItems.innerHTML = '';
            selectedItems.forEach(product => {
                const orderItem = document.createElement('div');
                orderItem.className = 'order-summary-item';
                orderItem.innerHTML = `
                    <img src="${product.image}" alt="${product.name}">
                    <div class="order-summary-item-details">
                        <span>${product.name}</span>
                        <span>Quantity: ${product.quantity}</span>
                        <span>Price: $${(product.price * product.quantity).toFixed(2)}</span>
                    </div>
                `;
                orderSummaryItems.appendChild(orderItem);
            });

            orderSummaryTotal.textContent = selectedTotal.toFixed(2);
        }

        // Toggle bank transfer details
        function toggleBankTransferDetails() {
            const bankTransferDetails = document.getElementById('bank-transfer-details');
            bankTransferDetails.style.display = bankTransferDetails.style.display === 'none' ? 'block' : 'none';
        }

        // Validate email format
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        // Validate phone number format
        function validatePhoneNumber(phone) {
            const regex = /^\d{10}$/;
            return regex.test(phone);
        }

        // Validate all fields
        function validateFields() {
            const name = document.getElementById('Name').value.trim();
            const email = document.getElementById('E_mail').value.trim();
            const address = document.getElementById('address_no_1').value.trim();
            const province = document.getElementById('province').value.trim();
            const phone = document.getElementById('phone_number').value.trim();
            const postalCode = document.getElementById('postal_code').value.trim();

            if (!name || !email || !address || !province || !phone || !postalCode) {
                alert("All fields are required.");
                return false;
            }

            if (!validateEmail(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            if (!validatePhoneNumber(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }

            return true;
        }

        // Place order
        function placeOrder() {
            if (!validateFields()) {
                return;
            }

            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            const selectedIndexes = [];
            checkboxes.forEach(checkbox => {
                const parts = checkbox.id.split('-');
                const index = parseInt(parts[3]);
                if (!isNaN(index)) {
                    selectedIndexes.push(index);
                }
            });

            const selectedCart = cart.filter((item, index) => selectedIndexes.includes(index));

            if (selectedCart.length === 0) {
                alert('Please select at least one item to place an order.');
                return;
            }

            const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;
            const bankSlipFile = document.getElementById('payment-slip').files[0];

            if (paymentMethod === 'bank-transfer' && !bankSlipFile) {
                alert('Please upload your payment slip.');
                return;
            }

            const addressDetails = {
                name: document.getElementById('Name').value,
                email: document.getElementById('E_mail').value,
                address_no_1: document.getElementById('address_no_1').value,
                province: document.getElementById('province').value,
                phone_number: document.getElementById('phone_number').value,
                postal_code: document.getElementById('postal_code').value
            };

            // Confirmation dialog
            const confirmOrder = confirm("Are you sure you want to place this order?");
            if (!confirmOrder) {
                return;
            }

            // Convert the bank slip file to a base64 string
            let bankSlipBase64 = null;
            if (bankSlipFile) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    bankSlipBase64 = event.target.result.split(',')[1];
                    sendOrderData(selectedCart, addressDetails, paymentMethod, bankSlipBase64, selectedIndexes);
                };
                reader.readAsDataURL(bankSlipFile);
            } else {
                sendOrderData(selectedCart, addressDetails, paymentMethod, bankSlipBase64, selectedIndexes);
            }
        }

        // Send order data to the server
        function sendOrderData(selectedCart, addressDetails, paymentMethod, bankSlipBase64, selectedIndexes) {
            selectedCart.forEach(item => {
                item.total_price = item.price * item.quantity;
            });

            fetch("save_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ 
                    cart: selectedCart, 
                    address: addressDetails, 
                    payment_method: paymentMethod, 
                    bank_slip: bankSlipBase64 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Order placed successfully!");
                    // Remove selected items from the cart
                    cart = cart.filter((item, index) => !selectedIndexes.includes(index));
                    saveCart(cart);
                    updateCartUI();
                    window.location.href = "store.php";
                } else {
                    alert("Error placing order: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }

        // Initialize cart UI on page load
        updateCartUI();
    </script>

    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/67bd46122b68c6190a36b9b3/1iktljebt';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
</body>
</html>