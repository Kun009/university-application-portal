<?php
session_start();
include('../officer/includes/config.php'); // Adjust the path if necessary

// Get the university ID from the URL
$unid = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch university details
$sqlUniversity = "SELECT * FROM tbl_university WHERE unid = :unid";
$queryUniversity = $dbh->prepare($sqlUniversity);
$queryUniversity->bindParam(':unid', $unid, PDO::PARAM_INT);
$queryUniversity->execute();
$university = $queryUniversity->fetch(PDO::FETCH_OBJ);

// Fetch university staff
$sqlStaff = "SELECT first_name, last_name, email, phone_number, photo, position 
             FROM tbl_officer 
             WHERE unid = :unid";
$queryStaff = $dbh->prepare($sqlStaff);
$queryStaff->bindParam(':unid', $unid, PDO::PARAM_INT);
$queryStaff->execute();
$staffMembers = $queryStaff->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlentities($university->universityName); ?> - University Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Basic Styling */
        .navbar-nav .nav-item {
            margin-left: 20px;
        }
        .banner {
            background: url('../admission/uploads/university_logos/<?php echo htmlentities($university->logoPath); ?>') no-repeat center center;
            background-size: cover;
            height: 300px;
            position: relative;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .banner-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .banner-content {
            z-index: 1;
            color: white;
        }
        .staff-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
        .card-body p, .card-body h5 {
            color: #555;
        }
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
    <div class="banner">
        <div class="banner-overlay"></div>
        <div class="banner-content">
            <h1><?php echo htmlentities($university->universityName); ?></h1>
            <p><?php echo htmlentities($university->universityAddress); ?></p>
        </div>
    </div>
    <div class="relative bg-light-blue-100 py-20 overflow-hidden">
    <div class="absolute inset-0 background-circles">
        <!-- Circles -->
        <div class="circle w-32 h-32 bg-blue-900 left-5 top-10"></div>
        <div class="circle w-32 h-32 bg-blue-900 right-5 top-20"></div>
        <div class="circle w-32 h-32 bg-blue-900 left-1/4 top-30"></div>
        <div class="circle w-32 h-32 bg-blue-900 right-1/4 top-40"></div>
        <div class="circle w-32 h-32 bg-blue-900 left-1/3 bottom-20"></div>
        <div class="circle w-32 h-32 bg-blue-900 right-1/3 bottom-15"></div>
        <div class="circle w-32 h-32 bg-blue-900 left-40 top-60"></div>
        <div class="circle w-32 h-32 bg-blue-900 right-40 top-70"></div>
        <div class="circle w-32 h-32 bg-blue-900 left-1/2 top-40"></div>
       
    </div>
    <div class="container mx-auto text-center content">
    <!-- University Details Section -->
    <div class="container my-5">
        <div class="card mb-5">
            <div class="card-header bg-primary text-white">About <?php echo htmlentities($university->universityName); ?></div>
            <div class="card-body">
                <p><strong>Programs Offered:</strong> <?php echo htmlentities($university->programs); ?></p>
                <p><strong>Degrees Offered:</strong> <?php echo htmlentities($university->degreesOffered); ?></p>
                <p><strong>Faculties:</strong> <?php echo htmlentities($university->faculties); ?></p>
                <p><strong>Admission Requirements:</strong> <?php echo htmlentities($university->admissionRequirements); ?></p>
                <p><strong>Contact Email:</strong> <?php echo htmlentities($university->email); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlentities($university->phoneNumber); ?></p>
                <p><strong>Website:</strong> <a href="<?php echo htmlentities($university->schoolWebsite); ?>" target="_blank"><?php echo htmlentities($university->schoolWebsite); ?></a></p>
                <p><strong>About the University:</strong> <?php echo htmlentities($university->aboutUniversity); ?></p>
                <p><small class="text-muted">Established on <?php echo htmlentities($university->creationDate); ?></small></p>
            </div>
        </div>

        <!-- Staff Section -->
        <?php if (count($staffMembers) > 0): ?>
            <div class="staff-section">
                <h3 class="mb-4">University Staff</h3>
                <div class="row">
                    <?php foreach ($staffMembers as $staff): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="../admission/uploads/<?php echo htmlentities($staff->photo); ?>" alt="Staff Photo" class="card-img-top staff-photo mx-auto mt-3">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlentities($staff->first_name . ' ' . $staff->last_name); ?></h5>
                                    <p><strong>Position:</strong> <?php echo htmlentities($staff->position); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlentities($staff->email); ?></p>
                                    <p><strong>Phone:</strong> <?php echo htmlentities($staff->phone_number); ?></p>
                                    <p><em>For more details, please contact.</em></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted mt-5">No staff information available for this university.</p>
        <?php endif; ?>
    </div>
    </div>
    </div>
   <!-- Footer -->
   <!-- Footer -->
<footer class="bg-gray-800 text-white py-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
function toggleDropdown() {
    const dropdown = document.getElementById("user-dropdown");
    dropdown.classList.toggle("hidden");
    console.log("Dropdown toggled"); // Debugging line
}

    // Close the dropdown if clicking outside of it
    window.onclick = function(event) {
        const dropdown = document.getElementById("user-dropdown");
        const button = document.getElementById("user-menu-button");
        if (!button.contains(event.target) && !dropdown.classList.contains("hidden")) {
            dropdown.classList.add("hidden");
        }
    }
    </script>
    <!-- JS and Bootstrap Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
