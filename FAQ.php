<?php
session_start();
include('./conn/db_connect.php'); 

// Check if the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch FAQs from the database
$query = "SELECT * FROM faq";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Error fetching FAQs: " . mysqli_error($conn));
}

// Store the FAQs in an array
$faqs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $faqs[] = $row;
}

// Store the length of the array
$faqCount = count($faqs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SmartAssist</title>
    <!-- Importing Poppins font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="css/form_styles.css">
</head>
<body>


<!-- Navigation Bar -->
<nav class="header">
    <!-- Logo Section -->
    <div class="logo-nav">
        <img src="image/logo.png" alt="Logo" class="logo" style="width: 100px; height: 150px;">
    </div>
    <!-- Navigation Links -->
    <div class="nav-links">
        <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a>
        <a href="Ticket.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'Ticket.php' ? 'active' : ''; ?>">Support</a>
        <a href="Repair.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'Repair.php' ? 'active' : ''; ?>">Repair Services</a>
        <a href="store.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'store.php' ? 'active' : ''; ?>">Parts Store</a>
        <a href="FAQ.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'FAQ.php' ? 'active' : ''; ?>">FAQ</a>
    </div>

    <!-- User Panel and Login/Logout Section -->
    <div class="user-actions">
        <?php
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'representative') {
                // Admin/Representative Panel (No Dropdown)
                echo '<a href="' . ($_SESSION['user_type'] == 'admin' ? 'A_Dashboard.php' : 'representative_dashboard.php') . '" class="login-icon" title="Admin Panel">
                        <i class="fas fa-user-circle"></i> ' . ($_SESSION['user_type'] == 'admin' ? 'Admin Panel' : 'Representative Panel') . '
                      </a>';
            } else {
                // User Panel (With Dropdown)
                echo '<div class="user-panel">
                        <a href="#" class="user-panel-btn">
                            <i class="fas fa-user-circle"></i> ' . (isset($_SESSION['username']) ? $_SESSION['username'] : 'My Account') . ' <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown">
                            <a href="user_dashboard.php" class="dropdown-link">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <a href="user_ticket.php" class="dropdown-link">
                                <i class="fas fa-ticket-alt"></i> Solution
                            </a>
                            <a href="user_repair.php" class="dropdown-link">
                                <i class="fas fa-tools"></i> Services
                            </a>
                            <a href="user_oder.php" class="dropdown-link">
                                <i class="fas fa-shopping-cart"></i> My Orders
                            </a>
                        </div>
                      </div>';
            }

            // Logout Button
            echo '<a href="logout.php" class="login-icon" title="Logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                  </a>';
        } else {
            // Login Button
            echo '<a href="main.php" class="login-icon" title="Login">
                    <i class="fas fa-user-circle fa-2x"></i>
                    <span>Login</span>
                  </a>';
        }
        ?>
    </div>
</nav>

<!-- FAQ Header Section -->
<div class="faq-header">
    <div class="faq-image">
        <img src="image/FAQ1.jpg" alt="FAQ Background" />
    </div>
    <div class="faq-overlay">
        <h1>Hi, We can help you</h1>
        <p>Find answers to your questions or browse through our FAQs below.</p>
        <div class="faq-search-bar">
            <input type="text" placeholder="Search for answers..." />
            <button>
                <img src="image/search.png" alt="Search Icon" />
            </button>
        </div>
    </div>
</div>

<!-- Main Content Section -->
<div class="main-content">
    <!-- FAQ Section -->
    <div id="faqList"></div>

    <script>
        // Fetch FAQ data from PHP and parse it to JavaScript
        const faqs = <?php echo json_encode($faqs); ?>;
        console.log('FAQ Array:', faqs); // Debugging: Check data in the console

        const faqListDiv = document.getElementById('faqList');

        // Function to create FAQ elements dynamically
        faqs.forEach((faq, index) => {
            // Create a new FAQ item div
            const faqItemDiv = document.createElement('div');
            faqItemDiv.classList.add('faq-item');

            // Create the question button
            const questionButton = document.createElement('button');
            questionButton.classList.add('faq-question');
            questionButton.textContent = faq.question;
            questionButton.setAttribute('onclick', `toggleAnswer('answer${index}')`);

            // Create the answer div
            const answerDiv = document.createElement('div');
            answerDiv.classList.add('faq-answer');
            answerDiv.id = `answer${index}`;
            answerDiv.innerHTML = `<p>${faq.answer}</p>`;

            // Append question and answer to the FAQ item
            faqItemDiv.appendChild(questionButton);
            faqItemDiv.appendChild(answerDiv);

            // Append the FAQ item to the main FAQ list
            faqListDiv.appendChild(faqItemDiv);
        });

        // Function to toggle answer visibility
        function toggleAnswer(answerId) {
            const answerDiv = document.getElementById(answerId);
            if (answerDiv.style.display === "none" || answerDiv.style.display === "") {
                answerDiv.style.display = "block";
            } else {
                answerDiv.style.display = "none";
            }
        }
    </script>
</div>

<!-- Knowledge Base Section -->
<div class="knowledge-base-section" style="text-align: center; padding: 40px;">
    <h2>Knowledge Base</h2>
    <p>Explore our curated knowledge base to find quick solutions and guides.</p>
    <div class="knowledge-base-grid" style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
        <div class="knowledge-item" style="flex: 1; min-width: 250px; max-width: 300px; background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div class="image-container" style="width: 100%; height: 150px; margin-bottom: 15px; overflow: hidden;">
                <img src="image/problem2.png" alt="Troubleshooting" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h3>Troubleshooting</h3>
            <p>Quick fixes for common issues and errors.</p>
        </div>
        <div class="knowledge-item" style="flex: 1; min-width: 250px; max-width: 300px; background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div class="image-container" style="width: 100%; height: 150px; margin-bottom: 15px; overflow: hidden;">
                <img src="image/problem3.jpg" alt="User Guides" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h3>User Guides</h3>
            <p>Find step-by-step instructions to get the most out of our services.</p>
        </div>
        <div class="knowledge-item" style="flex: 1; min-width: 250px; max-width: 300px; background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div class="image-container" style="width: 100%; height: 150px; margin-bottom: 15px; overflow: hidden;">
                <img src="image/problem.png" alt="Video Tutorials" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h3>Regular Server Maintenance:</h3>
            <p>Ensure backups are running regularly for recovery in case of hardware failures.</p>
        </div>
        <div class="knowledge-item" style="flex: 1; min-width: 250px; max-width: 300px; background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div class="image-container" style="width: 100%; height: 150px; margin-bottom: 15px; overflow: hidden;">
                <img src="image/problem4.png" alt="Video Tutorials" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h3>Video Tutorials</h3>
            <p>Watch detailed videos for a better understanding of our features.</p>
        </div>
    </div>
</div>

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
    // Toggle the visibility of the chat window
    function toggleChatWindow() {
        const chatWindow = document.querySelector('.chat-window');
        chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
    }

    // Send message function (can be extended for real chatbot logic)
    function sendMessage() {
        const userInput = document.getElementById("user-input").value;
        if (userInput.trim() !== "") {
            displayMessage(userInput, "user");
            document.getElementById("user-input").value = ""; // Clear input field
            simulateBotResponse(userInput);
        }
    }

    // Display the message in the chat window
    function displayMessage(message, sender) {
        const chatMessages = document.querySelector('.chat-messages');
        const messageDiv = document.createElement("div");
        messageDiv.classList.add(sender === "user" ? "user-message" : "bot-message");
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to the bottom
    }

    // Simulate bot response (to be replaced with actual AI logic)
    function simulateBotResponse(userMessage) {
        setTimeout(() => {
            const botResponse = "I'm sorry, I don't understand that yet."; // Placeholder response
            displayMessage(botResponse, "bot");
        }, 1000); // Simulate delay
    }
</script>
<!-- Tawk.to Script -->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/67bd46122b68c6190a36b9b3/1iktljebt'; // Replace with your actual widget ID
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
</body>
</html>