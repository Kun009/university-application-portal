<?php
session_start();
include('../officer/includes/config.php');

// Fetch university names from the database for the search bar
$sql = "SELECT universityName FROM tbl_university";
$query = $dbh->prepare($sql);
$query->execute();
$universities = $query->fetchAll(PDO::FETCH_OBJ);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['applicant_email']);
$user_first_name = $isLoggedIn ? $_SESSION['applicant_first_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>University Application Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5;
        }
        /* Add a z-index to ensure circles are behind content */
        .background-circles {
            z-index: 0;
        }
        /* Set the z-index for the content */
        .content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body>
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
    <h1 class="text-4xl font-bold">Welcome to UniApply</h1>
    <p class="mt-4 text-lg">Streamline your university application process with ease.</p>
</div>
<div class="relative bg-light-blue-100 py-20 overflow-hidden">
    <div class="absolute inset-0 background-circles">
        <!-- Circles -->
        <div class="circle w-32 h-32 bg-blue-200 left-5 top-10"></div>
        <div class="circle w-32 h-32 bg-green-200 right-5 top-20"></div>
        <div class="circle w-32 h-32 bg-yellow-200 left-1/4 top-30"></div>
        <div class="circle w-32 h-32 bg-red-200 right-1/4 top-40"></div>
        <div class="circle w-32 h-32 bg-grey-200 left-1/3 bottom-20"></div>
        <div class="circle w-32 h-32 bg-blue-200 right-1/3 bottom-15"></div>
        <div class="circle w-32 h-32 bg-purple-200 left-40 top-60"></div>
        <div class="circle w-32 h-32 bg-red-200 right-40 top-70"></div>
        <div class="circle w-32 h-32 bg-yellow-200 left-1/2 top-40"></div>
        <div class="circle w-32 h-32 bg-green-200 left-10 bottom-10"></div>
        <div class="circle w-32 h-32 bg-blue-200 right-10 bottom-10"></div>
        <div class="circle w-32 h-32 bg-blue-200 left-1/4 bottom-5"></div>
        <div class="circle w-32 h-32 bg-blue-200 right-1/4 bottom-5"></div>
        <div class="circle w-32 h-32 bg-red-200 left-45 top-70"></div>
        <div class="circle w-32 h-32 bg-yellow-200 right-45 top-80"></div>
        <div class="circle w-32 h-32 bg-green-200 left-2/3 top-60"></div>
    </div>
    <div class="container mx-auto text-center content">
    

    <!-- How It Works Section -->
    <div class="container mx-auto my-10 px-4">
        <h1 class="text-4xl font-bold text-center mb-8">How It Works</h1>
        <p class="text-gray-700 text-center mb-10">
            Our platform streamlines the university application process into three simple steps. Follow these steps to get started on your journey!
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Step 1 -->
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-2xl font-semibold mb-4">Step 1: Sign Up</h2>
                <p class="text-gray-600 mb-4">
                    Create your free account by providing some basic information. It's quick and easy!
                </p>
                <div class="flex justify-center mb-4">
                    <i class="fas fa-user-plus text-4xl text-blue-600"></i>
                </div>
                <ul class="list-disc list-inside text-gray-600">
                    <li>Fill out your profile.</li>
                    <li>Verify your email address.</li>
                    <li>Start exploring universities!</li>
                </ul>
            </div>
            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-2xl font-semibold mb-4">Step 2: Find Your University</h2>
                <p class="text-gray-600 mb-4">
                    Browse through a wide selection of universities that match your interests and career goals.
                </p>
                <div class="flex justify-center mb-4">
                    <i class="fas fa-university text-4xl text-blue-600"></i>
                </div>
                <ul class="list-disc list-inside text-gray-600">
                    <li>Filter by country, program, and rankings.</li>
                    <li>Read about university culture and facilities.</li>
                    <li>Save your favorites for easy access.</li>
                </ul>
            </div>
            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-2xl font-semibold mb-4">Step 3: Start Your Application</h2>
                <p class="text-gray-600 mb-4">
                    Once you’ve chosen your university, begin your application with all the guidance you need.
                </p>
                <div class="flex justify-center mb-4">
                    <i class="fas fa-file-signature text-4xl text-blue-600"></i>
                </div>
                <ul class="list-disc list-inside text-gray-600">
                    <li>Complete application forms easily.</li>
                    <li>Upload necessary documents.</li>
                    <li>Track your application status in real time.</li>
                </ul>
            </div>
        </div>
        
        <div class="mt-10 text-center">
            <a href="apply-to-university.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition duration-300">
                Get Started Now
            </a>
        </div>
    </div>
    </div>
    </div>

   <!-- Footer -->
 <footer class="bg-gray-800 text-white py-10 mt-auto">
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
            <p class="text-center mt-10">&copy; 2024 UniApply. All rights reserved.</p>
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
</script>
</body>
</html>
