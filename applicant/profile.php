<?php
session_start();
include('../officer/includes/config.php'); // Adjust path as needed

// Check if the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>alert('Please log in to view your profile.'); window.location.href='login.php';</script>";
    exit();
}

// Get logged-in applicant's ID
$applicantId = $_SESSION['applicant_id'];

// Fetch applicant details from tbl_applicant
$applicantSql = "SELECT * FROM tbl_applicant WHERE id = :id";
$applicantQuery = $dbh->prepare($applicantSql);
$applicantQuery->bindParam(':id', $applicantId, PDO::PARAM_INT);
$applicantQuery->execute();
$applicant = $applicantQuery->fetch(PDO::FETCH_OBJ);

// Fetch additional profile details from tbl_profile
$profileSql = "SELECT * FROM tbl_profile WHERE applicant_id = :applicant_id";
$profileQuery = $dbh->prepare($profileSql);
$profileQuery->bindParam(':applicant_id', $applicantId, PDO::PARAM_INT);
$profileQuery->execute();
$profile = $profileQuery->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Profile</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .background-circles {
            position: absolute; /* Changed to absolute to overlay circles properly */
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            top: 0; /* Align to the top */
            left: 0; /* Align to the left */
            z-index: -1; /* Behind other content */
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.3; /* Adjust opacity for visibility */
        }

        /* Circles positioning */
        .circle-1 { width: 200px; height: 200px; top: 10%; left: 5%; background-color: #cfe2f3; }
        .circle-2 { width: 150px; height: 150px; top: 20%; right: 10%; background-color: #b6d7a8; }
        .circle-3 { width: 250px; height: 250px; top: 30%; left: 50%; transform: translateX(-50%); background-color: #c9daf8; }
        .circle-4 { width: 180px; height: 180px; bottom: 10%; left: 10%; background-color: #d9ead3; }
        .circle-5 { width: 220px; height: 220px; bottom: 20%; right: 5%; background-color: #f6b26b; }
        .circle-6 { width: 170px; height: 170px; top: 40%; right: 15%; background-color: #f9cb9c; }
        .circle-7 { width: 200px; height: 200px; bottom: 30%; left: 30%; background-color: #cfe2f3; }
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
    <div class="container my-5">
        <h2 class="text-center">Profile</h2>

        <div class="row">
            <div class="col-md-6">
                <h3>Applicant Details</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlentities($applicant->first_name); ?></li>
                    <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlentities($applicant->last_name); ?></li>
                    <li class="list-group-item"><strong>Middle Name:</strong> <?php echo htmlentities($applicant->middle_name); ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo htmlentities($applicant->email); ?></li>
                    <li class="list-group-item"><strong>Mobile No:</strong> <?php echo htmlentities($applicant->mobile_no); ?></li>
                    <li class="list-group-item"><strong>Address:</strong> <?php echo htmlentities($applicant->address); ?></li>
                    <li class="list-group-item"><strong>Country:</strong> <?php echo htmlentities($applicant->country); ?></li>
                    <li class="list-group-item"><strong>Date of Birth:</strong> <?php echo htmlentities($applicant->date_of_birth); ?></li>
                    <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlentities($applicant->gender); ?></li>
                    <li class="list-group-item"><strong>Marital Status:</strong> <?php echo htmlentities($applicant->marital_status); ?></li>
                    <li class="list-group-item"><strong>Qualification:</strong> <?php echo htmlentities($applicant->qualification); ?></li>
                    <li class="list-group-item"><strong>Passport Photo:</strong> <img src="../admission/uploads/<?php echo htmlentities($applicant->passport_photo); ?>" alt="Passport Photo" style="width: 100px; height: auto;"></li>
                </ul>
                <a href="edit.php" class="btn btn-warning btn-block">Edit Profile</a>
            </div>
            <div class="background-circles">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="circle circle-4"></div>
        <div class="circle circle-5"></div>
        <div class="circle circle-6"></div>
        <div class="circle circle-7"></div>
    </div>

            <div class="col-md-6">
                <h3>Document Requirements</h3>
                <?php if ($profile): ?>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Transcripts:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->transcripts); ?>" target="_blank">View</a></li>
                        <li class="list-group-item"><strong>Diploma:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->diploma); ?>" target="_blank">View</a></li>
                        <li class="list-group-item"><strong>Recommendation Letters:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->recommendation_letters); ?>" target="_blank">View</a></li>
                        <li class="list-group-item"><strong>Standardized Test Scores:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->standardized_test_scores); ?>" target="_blank">View</a></li>
                        <li class="list-group-item"><strong>Resume:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->resume); ?>" target="_blank">View</a></li>
                        <li class="list-group-item"><strong>Proof of English Proficiency:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->proof_of_english_proficiency); ?>" target="_blank">View</a></li>
                        <li class="list-group-item"><strong>Financial Documents:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->financial_documents); ?>" target="_blank">View</a></li>
                        <li class="list-group-item"><strong>Passport Copy:</strong> <a href="../admission/uploads/<?php echo htmlentities($profile->passport_copy); ?>" target="_blank">View</a></li>
                        
                        <!-- New fields for disciplinary issues and felony charges -->
                        <li class="list-group-item"><strong>Disciplinary Issue:</strong> <?php echo ($profile->disciplinaryIssues); ?></li>
                        <li class="list-group-item"><strong>Felony Charges:</strong> <?php echo ($profile->felonyCharge);  ?></li>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        Your document requirements are not filled. Please <a href="update-profile.php" class="alert-link">fill in your profile</a>.
                    </div>
                <?php endif; ?>
            </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
