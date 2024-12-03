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
    <title>Privacy Policy - UniApply</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5; /* Adjust opacity as needed */
        }
    </style>
</head>
<body> <nav class="bg-gradient-to-r from-blue-300 to-blue-500 shadow-lg transition-all duration-300 hover:shadow-xl">
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
    <h1 class="text-4xl font-bold">Privacy Policy</h1>
    <p class="mt-4 text-lg">Your privacy is important to us.</p>
</div>

<!-- Content Section with Background Circles -->
<div class="relative bg-light-blue-100 overflow-hidden py-10">
    <div class="absolute inset-0">
        <!-- Adding circles -->
        <div class="circle bg-blue-300" style="width: 100px; height: 100px; top: 20%; left: 10%;"></div>
        <div class="circle bg-blue-400" style="width: 80px; height: 80px; top: 30%; left: 40%;"></div>
        <div class="circle bg-blue-300" style="width: 120px; height: 120px; top: 50%; left: 60%;"></div>
        <div class="circle bg-blue-400" style="width: 90px; height: 90px; top: 70%; left: 20%;"></div>
        <div class="circle bg-blue-300" style="width: 110px; height: 110px; top: 10%; left: 70%;"></div>
        <div class="circle bg-blue-400" style="width: 130px; height: 130px; top: 60%; left: 80%;"></div>
        <div class="circle bg-blue-300" style="width: 70px; height: 70px; top: 80%; left: 30%;"></div>
    </div>

    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">1. Introduction</h2>
        <p class="text-gray-700">Welcome to UniApply's privacy policy. This document outlines how we collect, use, and protect your personal information when you use our services.</p>

        <h2 class="text-2xl font-bold mb-6 mt-8">2. Information We Collect</h2>
        <p class="text-gray-700">We may collect personal information that you provide to us, including but not limited to:</p>
        <ul class="list-disc list-inside text-gray-700">
            <li>Name</li>
            <li>Email address</li>
            <li>Phone number</li>
            <li>Academic information</li>
            <li>Application details</li>
        </ul>

        <h2 class="text-2xl font-bold mb-6 mt-8">3. How We Use Your Information</h2>
        <p class="text-gray-700">We use the information we collect for various purposes, including:</p>
        <ul class="list-disc list-inside text-gray-700">
            <li>To provide and maintain our services</li>
            <li>To notify you about changes to our services</li>
            <li>To allow you to participate in interactive features when you choose to do so</li>
            <li>To provide customer support</li>
            <li>To gather analysis or valuable information so that we can improve our services</li>
            <li>To monitor the usage of our services</li>
            <li>To detect, prevent and address technical issues</li>
        </ul>

        <h2 class="text-2xl font-bold mb-6 mt-8">4. Data Retention</h2>
        <p class="text-gray-700">We will retain your personal information only for as long as is necessary for the purposes set out in this privacy policy. We will retain and use your personal information to the extent necessary to comply with our legal obligations.</p>

        <h2 class="text-2xl font-bold mb-6 mt-8">5. Security of Your Information</h2>
        <p class="text-gray-700">The security of your personal information is important to us, but remember that no method of transmission over the internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.</p>

        <h2 class="text-2xl font-bold mb-6 mt-8">6. Your Rights</h2>
        <p class="text-gray-700">Depending on your location, you may have certain rights regarding your personal information, including:</p>
        <ul class="list-disc list-inside text-gray-700">
            <li>The right to access, update, or delete the information we have on you</li>
            <li>The right to have your information rectified if that information is inaccurate or incomplete</li>
            <li>The right to object to our processing of your personal information</li>
            <li>The right to request that we restrict the processing of your personal information</li>
            <li>The right to data portability</li>
        </ul>

        <h2 class="text-2xl font-bold mb-6 mt-8">7. Changes to This Privacy Policy</h2>
        <p class="text-gray-700">We may update our privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page. You are advised to review this privacy policy periodically for any changes.</p>

        <h2 class="text-2xl font-bold mb-6 mt-8">8. Contact Us</h2>
        <p class="text-gray-700">If you have any questions about this privacy policy, please contact us at <a href="mailto:contact@uniapply.co.uk" class="text-blue-600">contact@uniapply.co.uk</a>.</p>
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
                        <a href="#" class="text-gray-400 hover:text-white">Facebook</a>
                        <a href="#" class="text-gray-400 hover:text-white">Twitter</a>
                        <a href="#" class="text-gray-400 hover:text-white">LinkedIn</a>
                        <a href="#" class="text-gray-400 hover:text-white">Instagram</a>
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
        $('#user-dropdown').toggleClass('hidden');
    }

    $(document).click(function(event) {
        if (!$(event.target).closest('#user-menu-button').length && !$(event.target).closest('#user-dropdown').length) {
            $('#user-dropdown').addClass('hidden');
        }
    });
</script>
</body>
</html>
