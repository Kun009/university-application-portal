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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5;
        }
        .background-circles {
            z-index: 0;
        }
        .content {
            position: relative;
            z-index: 10;
        }
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
<!-- Modal for Login Notification -->
<div id="loginModal" class="modal fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
    <div class="modal-content bg-white rounded-lg shadow-lg p-6 w-11/12 sm:w-96">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Please Log In</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                &times;
            </button>
        </div>
        <p class="text-gray-600 mt-4">
            You need to log in to access this feature. Click the button below to log in.
        </p>
        <div class="mt-6">
            <a href="login.php" class="w-full inline-block bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg shadow transition duration-300">
                Log In
            </a>
        </div>
    </div>
</div>

<!-- Banner Section with Fixed Height -->
<div class="w-full" style="height: 50vh; min-height: 500px;">
    <!-- Video Container -->
    <div class="relative w-full h-full">
        <!-- Video Background -->
        <video 
            class="absolute inset-0 w-full h-full object-cover"
            autoplay 
            muted 
            loop 
            playsinline
        >
            <source src="../video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Content -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to UniApply</h1>
                <p class="text-lg md:text-xl">Streamline your university application process with ease.</p>
            </div>
        </div>
    </div>
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
        <!-- University Search Section -->
        <div class="search-bar py-10 text-center">
            <h2 class="text-2xl mb-6">Find Your Desired University</h2>
            <form action="search-university.php" method="get" class="w-1/2 mx-auto">
                <input type="text" name="query" class="w-full p-3 border border-gray-300 rounded mb-4" placeholder="Search by University Name" list="universities" required>
                <datalist id="universities">
                    <?php foreach($universities as $university): ?>
                        <option value="<?php echo htmlentities($university->universityName); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded">Search</button>
            </form>
        </div>

        <!-- Additional Information Section with Tailwind -->
        <div class="container mx-auto my-10 px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 border rounded-lg shadow-md hover:shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Why Choose Us?</h3>
                    <p class="text-gray-700">We provide a seamless experience for students applying to universities across the globe, all from one platform.</p>
                </div>
                <div class="p-6 border rounded-lg shadow-md hover:shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Wide Range of Universities</h3>
                    <p class="text-gray-700">Explore universities that fit your educational needs, including various programs and courses.</p>
                </div>
                <div class="p-6 border rounded-lg shadow-md hover:shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Expert Guidance</h3>
                    <p class="text-gray-700">Our team is here to assist you with every step of the application process.</p>
                </div>
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

</body>

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
<!-- JavaScript to handle modal logic -->
<script>
    $(document).ready(function() {
        // Check if user is not logged in
        var isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        if (!isLoggedIn) {
            // Show the login modal
            $('#loginModal').css('display', 'block');
        }

        // Close modal when 'x' is clicked
        $('#closeModal').click(function() {
            $('#loginModal').css('display', 'none');
        });

        // Optional: Close the modal when clicking outside of it
        $(window).click(function(event) {
            if ($(event.target).is('#loginModal')) {
                $('#loginModal').css('display', 'none');
            }
        });
    });

    // Define the toggleDropdown function
    function toggleDropdown() {
        const dropdown = document.getElementById('user-dropdown');
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    }
</script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</html>
