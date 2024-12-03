<?php
session_start();
include('../officer/includes/config.php'); // Adjust the path as needed

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetching form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user credentials
    $sql = "SELECT * FROM tbl_applicant WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    
    // Check if email exists
    if ($query->rowCount() > 0) {
        $user = $query->fetch(PDO::FETCH_ASSOC);
        
        // Compare the plain text password directly
        if ($user['password'] === $password) {
            // Password is correct, set session variables
            $_SESSION['applicant_id'] = $user['id']; // Assuming 'id' is the primary key in tbl_applicant
            $_SESSION['applicant_first_name'] = $user['first_name']; // Assuming 'first_name' is the field name
            $_SESSION['applicant_email'] = $user['email'];
            $_SESSION['applicant_photo'] = $user['passport_photo'];
            header("Location: index.php"); // Redirect to the dashboard after successful login
            exit();
        } else {
            $error_message = "Incorrect password. Please try again.";
        }
    } else {
        $error_message = "Email not found. Please register.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UniApply</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        /* Gradient Animation */
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Background styling */
        .animated-background {
            background: linear-gradient(45deg, #6a11cb, #2575fc, #ff6b6b, #ff9a76);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        /* Floating icons */
        .icon {
            position: absolute;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Varying positions and sizes for icons */
        .icon:nth-child(1) { top: 20%; left: 10%; font-size: 3rem; animation-duration: 12s; }
        .icon:nth-child(2) { top: 30%; left: 80%; font-size: 2rem; animation-duration: 10s; }
        .icon:nth-child(3) { top: 70%; left: 25%; font-size: 4rem; animation-duration: 15s; }
        .icon:nth-child(4) { top: 50%; left: 50%; font-size: 2.5rem; animation-duration: 9s; }
        .icon:nth-child(5) { top: 10%; left: 70%; font-size: 3.5rem; animation-duration: 11s; }
    </style>
</head>
<body class="animated-background flex flex-col min-h-screen justify-between items-center relative overflow-hidden">

    <!-- Floating icons -->
    <div class="icon">⭐</div>
    <div class="icon">🌐</div>
    <div class="icon">💼</div>
    <div class="icon">🎓</div>
    <div class="icon">📚</div>
    <nav class="bg-gradient-to-r from-blue-300 to-blue-500 shadow-lg transition-all duration-300 hover:shadow-xl w-full">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 w-full">           <!-- Logo Section with Animation -->
            <a href="index.php" class="flex items-center transform transition-transform duration-300 hover:scale-105">
                <img src="../logo.png" alt="UniApply Logo" class="h-16 w-auto animate__animated animate__fadeIn">
            </a>

            <!-- Navigation Links with Hover Effects -->
            <div class="hidden md:flex space-x-6">
                <?php
                $navItems = [
                    'Home' => 'index.php',
                    'Find Universities' => 'find-universities.php',
                    'Apply to University' => 'apply-to-university.php',
                    'Application Status' => 'application_status.php',
                    'About Us' => 'about-us.php'
                ];

                foreach ($navItems as $label => $url):
                ?>
                <a href="<?php echo $url; ?>" 
                   class="text-white font-medium text-sm transform transition-all duration-300 
                          hover:text-blue-200 hover:scale-110 hover:-translate-y-1 
                          relative after:absolute after:bottom-0 after:left-0 after:h-0.5 
                          after:w-0 after:bg-blue-200 after:transition-all after:duration-300 
                          hover:after:w-full">
                    <?php echo $label; ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- User Menu Section -->
            <div class="relative">
                <?php if (isset($_SESSION['applicant_id'])): ?>
                    <button onclick="toggleDropdown()" 
        id="user-menu-button" 
        class="flex items-center space-x-3 text-white font-medium text-sm 
               bg-blue-700 px-4 py-2 rounded-full transform transition-all 
               duration-300 hover:bg-blue-600 hover:scale-105">
    <!-- Profile Photo Container -->
    <div class="relative w-8 h-8 overflow-hidden rounded-full border-2 border-blue-200 
                transform transition-all duration-300 hover:border-white">
        <?php if (isset($_SESSION['applicant_photo']) && !empty($_SESSION['applicant_photo'])): ?>
            <img src="../admission/uploads/<?php echo htmlentities($_SESSION['applicant_photo']); ?>" 
                 alt="Profile Photo"
                 class="w-full h-full object-cover animate__animated animate__fadeIn">
        <?php else: ?>
            <!-- Default Avatar if no photo is set -->
            <div class="w-full h-full bg-blue-300 flex items-center justify-center">
                <span class="text-blue-700 text-lg font-bold">
                    <?php echo substr($_SESSION['applicant_first_name'], 0, 1); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Name and Welcome Text -->
    <span class="animate__animated animate__fadeIn flex items-center space-x-2">
        <span>Hi, <?php echo htmlentities($_SESSION['applicant_first_name']); ?></span>
        <svg class="w-4 h-4 transition-transform duration-300" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </span>
</button>

                    <!-- Dropdown Menu with Animation -->
                    <div id="user-dropdown" 
                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg 
                                shadow-lg z-10 animate__animated animate__fadeIn">
                        <div class="py-1">
                            <a href="profile.php" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 
                                      transition-colors duration-300">
                                Profile
                            </a>
                            <a href="logout.php" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 
                                      transition-colors duration-300">
                                Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="create-account.php" 
                       class="text-white font-medium text-sm bg-blue-700 px-6 py-2 
                              rounded-full transform transition-all duration-300 
                              hover:bg-blue-600 hover:scale-105 hover:shadow-lg">
                        Create Account
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" 
                        class="text-white hover:text-blue-200 transition-colors duration-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <?php foreach ($navItems as $label => $url): ?>
            <a href="<?php echo $url; ?>" 
               class="block text-white font-medium text-sm py-2 transform 
                      transition-all duration-300 hover:text-blue-200 
                      hover:translate-x-2">
                <?php echo $label; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>
    <!-- Login Form -->
    <div class="flex items-center justify-center mt-8 z-10">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-semibold text-blue-600">Login</h2>
                <p class="text-gray-600">Enter your email and password</p>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email:</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium">Password:</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200">
                    Login
                </button>
            </form>
            <div class="text-center mt-3">
    <p>Don't have an account? <a href="create-account.php" style="color: blue; font-weight: bold;">Create an account</a></p>
</div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-8 w-full z-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-xl font-semibold mb-4">Contact Us</h4>
                    <p>UniApply</p>
                    <p>123 University Ave, London, UK</p>
                    <p>Email: contact@uniapply.co.uk</p>
                    <p>Phone: +44 20 7946 0958</p>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Quick Links</h4>
                    <ul>
                        <li><a href="about-us.php" class="hover:text-gray-400">About Us</a></li>
                        <li><a href="find-universities.php" class="hover:text-gray-400">Find Universities</a></li>
                        <li><a href="apply-to-university.php" class="hover:text-gray-400">Apply</a></li>
                        <li><a href="contact-us.php" class="hover:text-gray-400">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Resources</h4>
                    <ul>
                        <li><a href="faq.php" class="hover:text-gray-400">FAQ</a></li>
                        <li><a href="terms.php" class="hover:text-gray-400">Terms & Conditions</a></li>
                        <li><a href="privacy.php" class="hover:text-gray-400">Privacy Policy</a></li>
                        <li><a href="blog.php" class="hover:text-gray-400">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Connect</h4>
                    <p>Follow us on social media</p>
                    <div class="flex space-x-4 mt-4">
        <a href="https://facebook.com" target="_blank" class="text-gray-400 hover:text-white">Facebook</a>
        <a href="https://x.com" target="_blank" class="text-gray-400 hover:text-white">X</a>
        <a href="https://linkedin.com" target="_blank" class="text-gray-400 hover:text-white">LinkedIn</a>
        <a href="https://instagram.com" target="_blank" class="text-gray-400 hover:text-white">Instagram</a>
    </div>
                </div>
            </div>
            <p class="text-center mt-10">&copy; 2024 UniApply. All Rights Reserved.</p>
        </div>
    </footer>
    <script>
function toggleDropdown() {
    const dropdown = document.getElementById('user-dropdown');
    const button = document.getElementById('user-menu-button');
    
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        dropdown.classList.add('animate__fadeIn');
        button.querySelector('svg').classList.add('rotate-180');
    } else {
        dropdown.classList.add('hidden');
        dropdown.classList.remove('animate__fadeIn');
        button.querySelector('svg').classList.remove('rotate-180');
    }
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.remove('hidden');
        mobileMenu.classList.add('animate__fadeIn');
    } else {
        mobileMenu.classList.add('hidden');
        mobileMenu.classList.remove('animate__fadeIn');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('user-dropdown');
    const button = document.getElementById('user-menu-button');
    
    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.classList.add('hidden');
        button.querySelector('svg')?.classList.remove('rotate-180');
    }
});
</script>
    <script>
        function toggleDropdown() {
            document.getElementById("user-dropdown").classList.toggle("hidden");
        }
    </script>
</body>
</html>
