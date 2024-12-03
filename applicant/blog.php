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
    <title>Our Blog - UniApply</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .bg-shape {
            position: relative;
            z-index: 0;
        }

        .bg-shape::before,
        .bg-shape::after {
            content: '';
            position: absolute;
            z-index: -1;
            width: 300px; /* Adjust size as necessary */
            height: 300px; /* Adjust size as necessary */
            border-radius: 50%; /* For circular shapes */
            opacity: 0.5; /* Adjust opacity as necessary */
        }

        .bg-shape::before {
            background: rgba(255, 0, 0, 0.2); /* Change color as needed */
            top: -100px; /* Adjust position */
            left: -100px; /* Adjust position */
        }

        .bg-shape::after {
            background: rgba(0, 0, 255, 0.2); /* Change color as needed */
            bottom: -100px; /* Adjust position */
            right: -100px; /* Adjust position */
        }
    </style>
</head>
<body class="bg-gray-100">
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
<div class="banner bg-cover bg-center text-center text-white py-20 bg-shape" style="background-image: url('./images/banner.jpg');">
    <h1 class="text-4xl font-bold">Our Blog</h1>
    <p class="mt-4 text-lg">Insights, tips, and stories for prospective students.</p>
</div>

<!-- Blog Content Section -->
<div class="container mx-auto my-10 px-4">
    <h2 class="text-2xl font-bold mb-6">Latest Posts</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Example Blog Post -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-2"><a href="blog-post.php?id=1" class="text-blue-600 hover:underline">The Ultimate Guide to University Applications</a></h3>
            <p class="text-gray-700">Discover everything you need to know about applying to universities, from documents to deadlines.</p>
            <p class="mt-4 text-gray-500">Posted on: October 20, 2024</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-2"><a href="blog-post.php?id=2" class="text-blue-600 hover:underline">Tips for Writing a Winning Personal Statement</a></h3>
            <p class="text-gray-700">Learn how to craft a personal statement that stands out to admissions committees.</p>
            <p class="mt-4 text-gray-500">Posted on: October 18, 2024</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-2"><a href="blog-post.php?id=3" class="text-blue-600 hover:underline">Navigating Student Life: What to Expect</a></h3>
            <p class="text-gray-700">Explore what student life is really like and how to make the most of your university experience.</p>
            <p class="mt-4 text-gray-500">Posted on: October 15, 2024</p>
        </div>

        <!-- More blog posts can be added here -->
    </div>
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
                <h4 class="text-xl font-semibold mb-4">Resources</h4>
                <ul>
                    <li><a href="faq.php" class="hover:text-gray-400">FAQ</a></li>
                    <li><a href="terms.php" class="hover:text-gray-400">Terms & Conditions</a></li>
                    <li><a href="privacy.php" class="hover:text-gray-400">Privacy Policy</a></li>
                    <li><a href="blog.php" class="hover:text-gray-400">Our Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-4">Follow Us</h4>
                <ul class="flex space-x-4">
                    <li><a href="#" class="hover:text-gray-400">Facebook</a></li>
                    <li><a href="#" class="hover:text-gray-400">Twitter</a></li>
                    <li><a href="#" class="hover:text-gray-400">Instagram</a></li>
                    <li><a href="#" class="hover:text-gray-400">LinkedIn</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
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
        $("#user-dropdown").toggleClass("hidden");
    }

    $(document).mouseup(function(e) {
        var container = $("#user-dropdown");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.addClass("hidden");
        }
    });
</script>
</body>
</html>
