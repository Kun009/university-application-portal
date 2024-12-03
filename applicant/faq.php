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
    <title>FAQ - University Application Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
       .background-shape {
           position: absolute;
           border-radius: 50%;
           background-color: rgba(30, 58, 138, 0.2); /* Adjust color and opacity as needed */
           z-index: -1;
       }

       /* Existing shapes */
       .shape-1 { width: 150px; height: 150px; top: 100px; left: 20px; }
       .shape-2 { width: 200px; height: 200px; top: 300px; right: 30px; }
       .shape-3 { width: 120px; height: 120px; bottom: 50px; left: 150px; }

       /* New shapes with different positions and sizes */
       .shape-4 { width: 180px; height: 180px; top: 200px; left: 250px; }
       .shape-5 { width: 220px; height: 220px; bottom: 100px; right: 200px; }
       .shape-6 { width: 140px; height: 140px; bottom: 200px; left: 300px; }

       /* FAQ answer toggle */
       .faq-answer {
           display: none;
           transition: max-height 0.3s ease;
       }

       .faq-question {
           cursor: pointer; /* Add cursor pointer to indicate it's clickable */
       }
    </style>
</head>
<body class="relative bg-light-blue-50 overflow-y-auto">

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
    <h1 class="text-4xl font-bold">Frequently Asked Questions</h1>
    <p class="mt-4 text-lg">Find answers to common questions below.</p>
</div>

<!-- Background Shapes for Visual Decoration -->
<div class="background-shape shape-1"></div>
<div class="background-shape shape-2"></div>
<div class="background-shape shape-3"></div>
<div class="background-shape shape-4"></div>
<div class="background-shape shape-5"></div>
<div class="background-shape shape-6"></div>

<<!-- FAQ Content Section -->
<div class="container mx-auto my-10 px-4 relative z-10">
    <h2 class="text-2xl font-bold mb-6 text-center">FAQs</h2>
    <div class="space-y-4 bg-light-blue-50 p-6 rounded-lg shadow-md">
        <div class="border border-gray-300 rounded-lg p-4">
            <h3 class="font-semibold text-lg faq-question flex justify-between items-center" onclick="toggleAnswer('answer1')">
                <span>1. How do I apply to a university?</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </h3>
            <div id="answer1" class="faq-answer text-gray-700">
                You can apply to a university through our application platform by creating an account and following the step-by-step instructions for submitting your application.
            </div>
        </div>
        <div class="border border-gray-300 rounded-lg p-4">
            <h3 class="font-semibold text-lg faq-question flex justify-between items-center" onclick="toggleAnswer('answer2')">
                <span>2. What documents do I need for my application?</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </h3>
            <div id="answer2" class="faq-answer text-gray-700">
                Generally, you will need your academic transcripts, a personal statement, letters of recommendation, and proof of English proficiency. Check the specific requirements for each university.
            </div>
        </div>
        <div class="border border-gray-300 rounded-lg p-4">
            <h3 class="font-semibold text-lg faq-question flex justify-between items-center" onclick="toggleAnswer('answer3')">
                <span>3. How can I check my application status?</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </h3>
            <div id="answer3" class="faq-answer text-gray-700">
                You can check your application status by logging into your account on our platform and navigating to the "Application Status" section.
            </div>
        </div>
        <div class="border border-gray-300 rounded-lg p-4">
            <h3 class="font-semibold text-lg faq-question flex justify-between items-center" onclick="toggleAnswer('answer4')">
                <span>4. What should I do if I forgot my password?</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </h3>
            <div id="answer4" class="faq-answer text-gray-700">
                If you forgot your password, click on the "Forgot Password?" link on the login page. You will receive an email with instructions to reset your password.
            </div>
        </div>
        <div class="border border-gray-300 rounded-lg p-4">
            <h3 class="font-semibold text-lg faq-question flex justify-between items-center" onclick="toggleAnswer('answer5')">
                <span>5. How can I contact support?</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </h3>
            <div id="answer5" class="faq-answer text-gray-700">
                You can contact support through the "Contact Us" page, where you will find our email address and phone number for further assistance.
            </div>
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
    function toggleAnswer(answerId) {
        $('#' + answerId).slideToggle();
    }
</script>
<script>
    function toggleDropdown() {
        $('#user-dropdown').toggleClass('hidden');
    }
</script>
</body>
</html>
