<?php
session_start();
include('../officer/includes/config.php'); // Adjust the path if necessary

// Retrieve the search query from the URL
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

$universities = [];
$suggestedUniversity = null;

if ($searchQuery) {
    // Search the database for universities that exactly match the search query
    $sql = "SELECT * FROM tbl_university WHERE universityName LIKE :query";
    $query = $dbh->prepare($sql);
    $searchTerm = '%' . $searchQuery . '%';
    $query->bindParam(':query', $searchTerm, PDO::PARAM_STR);
    $query->execute();
    $universities = $query->fetchAll(PDO::FETCH_OBJ);
    
    // If no exact matches found, look for similar university names
    if (count($universities) === 0) {
        // Use SOUNDEX to find close matches for misspelled university names
        $sql = "SELECT universityName FROM tbl_university WHERE SOUNDEX(universityName) = SOUNDEX(:searchQuery) LIMIT 1";
        $query = $dbh->prepare($sql);
        $query->bindParam(':searchQuery', $searchQuery, PDO::PARAM_STR);
        $query->execute();
        $suggestedUniversity = $query->fetch(PDO::FETCH_OBJ);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Search Results - University Application Platform</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles for circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.3; /* Make circles semi-transparent */
        }
        .circle1 { background: rgba(30, 144, 255, 0.5); width: 100px; height: 100px; top: 20%; left: 10%; }
        .circle2 { background: rgba(30, 144, 255, 0.5); width: 150px; height: 150px; top: 60%; left: 70%; }
        .circle3 { background: rgba(30, 144, 255, 0.5); width: 200px; height: 200px; top: 30%; right: 20%; }
        .circle4 { background: rgba(30, 144, 255, 0.5); width: 120px; height: 120px; top: 80%; left: 15%; }
        .circle5 { background: rgba(30, 144, 255, 0.5); width: 180px; height: 180px; top: 15%; right: 5%; }
        .circle6 { background: rgba(30, 144, 255, 0.5); width: 130px; height: 130px; top: 50%; right: 25%; }
        .circle7 { background: rgba(30, 144, 255, 0.5); width: 110px; height: 110px; top: 65%; left: 40%; }
    </style>
</head>
<body class="bg-light-blue-100 relative overflow-hidden">
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
     <!-- Search Results Section -->
     <div class="container mt-5 z-10 relative">
        <h2 class="mb-4">Search Results for "<?php echo htmlentities($searchQuery); ?>"</h2>

        <?php if (count($universities) > 0): ?>
            <div class="list-group">
                <?php foreach ($universities as $university): ?>
                    <a href="university-details.php?id=<?php echo $university->unid; ?>" class="list-group-item list-group-item-action">
                        <h5 class="mb-1"><?php echo htmlentities($university->universityName); ?></h5>
                        <p class="mb-1">Location: <?php echo htmlentities($university->universityAddress); ?></p>
                        <small>Programs available: <?php echo htmlentities($university->programs); ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No universities found matching "<?php echo htmlentities($searchQuery); ?>"</p>
            <?php if ($suggestedUniversity): ?>
                <p>Did you mean: <a href="?query=<?php echo urlencode($suggestedUniversity->universityName); ?>"><?php echo htmlentities($suggestedUniversity->universityName); ?></a>?</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Circles -->
    <div class="absolute inset-0">
        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>
        <div class="circle circle4"></div>
        <div class="circle circle5"></div>
        <div class="circle circle6"></div>
        <div class="circle circle7"></div>
    </div>
<!-- Footer -->
<footer class="bg-gray-800 text-white py-10 fixed bottom-0 left-0 w-full">
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
                    <a href="#" class="text-gray-400 hover:text-white">Facebook</a>
                    <a href="#" class="text-gray-400 hover:text-white">Twitter</a>
                    <a href="#" class="text-gray-400 hover:text-white">Instagram</a>
                </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleDropdown() {
            $('#user-dropdown').toggleClass('hidden');
        }
    </script>
</body>
</html>
