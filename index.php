<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SmartAssist</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
<!-- Main Content -->
<div class="welcome-section">
    <div class="text">
        <h1 data-aos="fade-up">Welcome to SmartAssist</h1>
        <h2 data-aos="fade-up">(We offer modern solutions)</h2>
        <p>We provide high-quality computer services, including troubleshooting, repairs, and part sales. Our team ensures your tech works at its best.</p><br><br>
        <a href="about.html" class="learn-more">Learn More</a>
    </div>
    <img src="image/index.png" alt="SmartAssist Image">
</div>

<!-- Auto-Generate Solution Section -->
<div class="solution-section">
    <div class="solution-form">
        <h2>Auto-Generate Solution</h2>
        <form id="solutionForm">
            <label for="query">Describe Your Problem:</label>
            <textarea id="query" name="query" rows="4" required></textarea>

            <label for="image">Upload Image (Optional):</label>
            <input type="file" id="image" name="image" accept="image/*">

            <button type="submit">Generate Solution</button>
        </form>
    </div>

    <div class="solution-result">
        <h3>Solution:</h3>
        <p id="solutionText"></p>
    </div>
</div>

<!-- Who We Are Section -->
<div class="who-we-are" id="who-we-are">
    <div class="who-we-are-left">
        <img src="image/index2.png" alt="Who We Are Image">
    </div>
    <div class="who-we-are-right">
        <h2>Who We Are</h2>
        <p>SmartAssist is dedicated to providing top-notch computer repair services, part replacements, and technical support. Our mission is to make technology work for you.</p><br><br>
        <a href="about.html" class="learn-more">Learn More</a>
    </div>
</div>

<!-- Meet Our Team Section -->
<h2><center><font color="#003366">What our Customers think about us</font></h2><br><br>
<div class="card-container">
    <!-- Left arrow -->
    <button class="prev-btn">←</button>

    <!-- Row of cards -->
    <div class="card-row">
        <div class="card">
            <img src="image/clients/client-1.jpg" alt="John Doe">
            <div class="card-name">John Doe</div>
            <div class="card-Description">Good Service and Best Staff</div>
            <div class="stars">★★★★☆</div>
        </div>
        <div class="card">
            <img src="image/clients/client-2.jpg" alt="Jane Smith">
            <div class="card-name">Jane Smith</div>
            <div class="card-Description">Good Service and Best Staff</div>
            <div class="stars">★★★☆☆</div>
        </div>
        <div class="card">
            <img src="image/clients/client-3.jpg" alt="Alice Johnson">
            <div class="card-name">Alice Johnson</div>
            <div class="card-Description">Good Service and Best Staff</div>
            <div class="stars">★★★★★</div>
        </div>
        <div class="card">
            <img src="image/clients/client-4.jpg" alt="Bob Lee">
            <div class="card-name">Bob Lee</div>
            <div class="card-Description">Good Service and Best Staff</div>
            <div class="stars">★★☆☆☆</div>
        </div>
        <div class="card">
            <img src="image/clients/client-5.jpg" alt="Charlie Brown">
            <div class="card-name">Charlie Brown</div>
            <div class="card-Description">Good Service and Best Staff</div>
            <div class="stars">★★★★☆</div>
        </div>
    </div>

    <!-- Right arrow -->
    <button class="next-btn">→</button>
</div><br><br><br>

<script>
    let currentIndex = 0;
    const cards = document.querySelector('.card-row');
    const totalCards = document.querySelectorAll('.card').length;
    const cardWidth = 220; // Adjust for card width + margin

    // Next button click event
    document.querySelector('.next-btn').addEventListener('click', () => {
        if (currentIndex < totalCards - 4) {
            currentIndex++;
            cards.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        }
    });

    // Previous button click event
    document.querySelector('.prev-btn').addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            cards.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        }
    });
</script>

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

<!-- JavaScript for Auto-Generate Solution -->
<script>
    const GEMINI_API_KEY = 'AIzaSyCsgkHGSJiG4TvwgorjqAEU0jpM-hhUevc'; // Replace with your actual API key

    async function generateContent(prompt, imageFile = null) {
        const url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${GEMINI_API_KEY}`;

        const requestBody = {
            contents: [{
                parts: [{ text: prompt }]
            }]
        };

        if (imageFile) {
            // Read the image file as a base64 string
            const base64Image = await readFileAsBase64(imageFile);
            requestBody.contents[0].parts.push({
                inline_data: {
                    mime_type: imageFile.type,
                    data: base64Image // Base64 data
                }
            });
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestBody),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error generating content:', error);
            return { error: 'Failed to generate content.' };
        }
    }

    // Helper function to read a file as a base64 string
    function readFileAsBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => {
                // Remove the "data:image/png;base64," prefix
                const base64Data = reader.result.split(',')[1];
                resolve(base64Data);
            };
            reader.onerror = (error) => reject(error);
            reader.readAsDataURL(file);
        });
    }

    document.getElementById('solutionForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const query = document.getElementById('query').value;
        const imageFile = document.getElementById('image').files[0];

        // Show a loading message while processing
        document.getElementById('solutionText').textContent = 'Generating solution...';

        try {
            const result = await generateContent(query, imageFile);

            if (result.error) {
                document.getElementById('solutionText').textContent = result.error;
            } else {
                const generatedText = result.candidates[0].content.parts[0].text;
                document.getElementById('solutionText').textContent = generatedText;
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('solutionText').textContent = 'An error occurred while generating the solution.';
        }
    });
</script>

<!-- JavaScript for Chatbot -->
<script>
    function toggleChatWindow() {
        const chatWindow = document.querySelector('.chat-window');
        chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
    }

    function sendMessage() {
        const userInput = document.getElementById("user-input").value;
        if (userInput.trim() !== "") {
            displayMessage(userInput, "user");
            document.getElementById("user-input").value = "";
            simulateBotResponse(userInput);
        }
    }

    function displayMessage(message, sender) {
        const chatMessages = document.querySelector('.chat-messages');
        const messageDiv = document.createElement("div");
        messageDiv.classList.add(sender === "user" ? "user-message" : "bot-message");
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function simulateBotResponse(userMessage) {
        setTimeout(() => {
            const botResponse = "I'm sorry, I don't understand that yet.";
            displayMessage(botResponse, "bot");
        }, 1000);
    }
</script>

<!-- Tawk.to Script -->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/67bd46122b68c6190a36b9b3/1iktljebt';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>

</body>
</html>