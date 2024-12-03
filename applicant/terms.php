<?php
session_start();
include('../officer/includes/config.php');

// Check if user is logged in
$isLoggedIn = isset($_SESSION['applicant_email']);
$user_first_name = $isLoggedIn ? $_SESSION['applicant_first_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Terms and Conditions - UniApply</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body class="bg-light-blue-100 relative">
 <nav class="bg-gradient-to-r from-blue-300 to-blue-500 shadow-lg transition-all duration-300 hover:shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo Section with Animation -->
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
                    <a href="login.php" 
                       class="text-white font-medium text-sm bg-blue-700 px-6 py-2 
                              rounded-full transform transition-all duration-300 
                              hover:bg-blue-600 hover:scale-105 hover:shadow-lg">
                        Login
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

<!-- Banner Section -->
<div class="banner bg-cover bg-center text-center text-white py-20" style="background-image: url('./images/banner.jpg');">
    <h1 class="text-4xl font-bold">Terms and Conditions</h1>
    <p class="mt-4 text-lg">Read our terms and conditions carefully.</p>
</div>

<!-- Terms Content Section -->
<div class="container mx-auto my-10 px-4">
    <h2 class="text-2xl font-bold mb-6">1. Introduction</h2>
    <p class="text-gray-700">Welcome to UniApply. By using our services, you agree to comply with and be bound by the following terms and conditions. Please review these terms carefully.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">2. Eligibility</h2>
    <p class="text-gray-700">You must be at least 16 years old to use our services. By using the services, you represent that you are of legal age to form a binding contract.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">3. Account Registration</h2>
    <p class="text-gray-700">To access certain features of our platform, you may be required to register for an account. You agree to provide accurate and complete information during the registration process.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">4. Use of Services</h2>
    <p class="text-gray-700">You agree to use our services only for lawful purposes and in a manner that does not infringe on the rights of others. You may not use our services in any way that could damage, disable, overburden, or impair our platform.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">5. User Content</h2>
    <p class="text-gray-700">You are solely responsible for any content that you submit, post, or otherwise make available through our services. By providing content, you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, and distribute your content.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">6. Limitation of Liability</h2>
    <p class="text-gray-700">In no event shall UniApply be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of our services.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">7. Changes to Terms</h2>
    <p class="text-gray-700">We may revise these terms from time to time. The revised terms will be effective when posted on our platform. Your continued use of the services after any changes constitutes your acceptance of the new terms.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">8. Governing Law</h2>
    <p class="text-gray-700">These terms shall be governed by and construed in accordance with the laws of the United Kingdom, without regard to its conflict of law principles.</p>

    <h2 class="text-2xl font-bold mb-6 mt-8">9. Contact Information</h2>
    <p class="text-gray-700">If you have any questions about these terms, please contact us at <a href="mailto:contact@uniapply.co.uk" class="text-blue-600">contact@uniapply.co.uk</a>.</p>
</div>

<!-- Background Circles -->
<div class="absolute inset-0 pointer-events-none">
    <div class="absolute w-32 h-32 bg-blue-200 rounded-full opacity-50" style="top: 10%; left: 20%;"></div>
    <div class="absolute w-24 h-24 bg-blue-300 rounded-full opacity-50" style="top: 30%; left: 70%;"></div>
    <div class="absolute w-36 h-36 bg-blue-400 rounded-full opacity-50" style="top: 50%; left: 10%;"></div>
    <div class="absolute w-28 h-28 bg-blue-500 rounded-full opacity-50" style="top: 70%; left: 50%;"></div>
    <div class="absolute w-32 h-32 bg-blue-300 rounded-full opacity-50" style="top: 80%; left: 30%;"></div>
    <div class="absolute w-20 h-20 bg-blue-200 rounded-full opacity-50" style="top: 40%; left: 80%;"></div>
    <div class="absolute w-24 h-24 bg-blue-400 rounded-full opacity-50" style="top: 20%; left: 40%;"></div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-10">
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
                    <li><a href="apply-to-university.php" class="hover:text-gray-400">Apply Now</a></li>
                    <li><a href="contact-us.php" class="hover:text-gray-400">Contact Support</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-4">Follow Us</h4>
                <ul>
                    <li><a href="#" class="hover:text-gray-400">Facebook</a></li>
                    <li><a href="#" class="hover:text-gray-400">Twitter</a></li>
                    <li><a href="#" class="hover:text-gray-400">Instagram</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-4">Legal</h4>
                <ul>
                    <li><a href="terms.php" class="hover:text-gray-400">Terms & Conditions</a></li>
                    <li><a href="privacy.php" class="hover:text-gray-400">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
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
        $('#user-dropdown').toggleClass('hidden');
    }

    $(document).click(function(event) {
        if (!$(event.target).closest('#user-menu-button').length) {
            $('#user-dropdown').addClass('hidden');
        }
    });
</script>
</body>
</html>
