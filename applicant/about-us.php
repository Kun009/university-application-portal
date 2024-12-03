<?php
session_start();
include('../officer/includes/config.php'); // Adjust path as needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - University Application Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        /* Shape container styling */
        .shape-container {
            position: absolute;
            inset: 0;
            z-index: -10; /* Places shapes behind the content */
            overflow: hidden;
        }

        /* Individual shape styling */
        .shape {
            position: absolute;
            background-color: rgba(59, 130, 246, 0.2); /* Light blue with low opacity */
            border-radius: 50%;
        }

        /* Specific shapes for strategic positioning */
        .shape-1 {
            width: 200px;
            height: 200px;
            top: 50px;
            left: 20px;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            top: 400px;
            right: 50px;
        }

        .shape-3 {
            width: 180px;
            height: 180px;
            bottom: 150px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body class="relative">
<!-- Navigation Bar -->

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
<!-- Shape Background -->
<div class="shape-container">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
</div>

<!-- Banner Section -->
<div class="banner bg-cover bg-center text-center text-white py-20" style="background-image: url('./images/banner.jpg');">
    <h1 class="text-4xl font-bold">About Us</h1>
    <p class="mt-4 text-lg">Learn more about our mission and how we can help you.</p>
</div>

<!-- About Us Content Section -->
<div class="container mx-auto my-10 px-4 relative">
    <h2 class="text-2xl font-bold mb-6 text-center">Our Mission</h2>
    <p class="text-gray-700 mb-4">
        At UniApply, we strive to streamline the university application process for students worldwide. Our goal is to provide a seamless and user-friendly platform that connects students with their desired universities, making the application experience efficient and accessible.
    </p>
    <p class="text-gray-700 mb-4">
        We understand the challenges that students face when applying for higher education, from finding the right institution to navigating the application requirements. That’s why we are committed to offering a centralized solution that simplifies these processes and empowers students to achieve their academic goals.
    </p>

    <h2 class="text-2xl font-bold mb-6 text-center">Our Values</h2>
    <ul class="list-disc list-inside mb-4">
        <li class="text-gray-700">Accessibility: Making higher education opportunities available to everyone.</li>
        <li class="text-gray-700">Support: Providing guidance and resources throughout the application journey.</li>
        <li class="text-gray-700">Innovation: Continuously improving our platform to meet the needs of our users.</li>
    </ul>

    <h2 class="text-2xl font-bold mb-6 text-center">Join Us</h2>
    <p class="text-gray-700 mb-4">
        Whether you are a student looking to apply to universities or an institution wanting to reach a wider audience, UniApply is here to support you. Together, we can make the university application process a transformative experience for all.
    </p>
</div>

<!-- Footer -->

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
                <h4 class="text-xl font-semibold mb-4">Resources</h4>
                <ul>
                    <li><a href="faq.php" class="hover:text-gray-400">FAQ</a></li>
                    <li><a href="terms.php" class="hover:text-gray-400">Terms & Conditions</a></li>
                    <li><a href="privacy.php" class="hover:text-gray-400">Privacy Policy</a></li>
                    <li><a href="blog.php" class="hover:text-gray-400">Our Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-4">Stay Connected</h4>
                <p>Follow us on social media</p>
                <div class="flex space-x-4 mt-4">
        <a href="https://facebook.com" target="_blank" class="text-gray-400 hover:text-white">Facebook</a>
        <a href="https://x.com" target="_blank" class="text-gray-400 hover:text-white">X</a>
        <a href="https://linkedin.com" target="_blank" class="text-gray-400 hover:text-white">LinkedIn</a>
        <a href="https://instagram.com" target="_blank" class="text-gray-400 hover:text-white">Instagram</a>
    </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
    const dropdown = document.getElementById("user-dropdown");
    dropdown.classList.toggle("hidden");
}

window.onclick = function(event) {
    const dropdown = document.getElementById("user-dropdown");
    const button = document.getElementById("user-menu-button");
    if (!button.contains(event.target) && !dropdown.classList.contains("hidden")) {
        dropdown.classList.add("hidden");
    }
}
</script>
</body>
</html>
