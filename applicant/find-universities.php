<?php
session_start();
include('../officer/includes/config.php'); // Adjust path as needed

// Check if the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>alert('Please log in or create an account to add universities to your cart.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch all universities from the database
$sql = "SELECT unid, universityName, aboutUniversity, logoPath FROM tbl_university";
$query = $dbh->prepare($sql);
$query->execute();
$universities = $query->fetchAll(PDO::FETCH_OBJ);

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Apply function
if (isset($_POST['add_to_cart'])) {
    $unid = intval($_POST['unid']);
    
    // Get logged in applicant's ID and email
    $applicantId = $_SESSION['applicant_id'];
    $applicantEmail = $_SESSION['applicant_email'];
    
    // Check if university is already in the cart
    $exists = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['unid'] == $unid) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $_SESSION['cart'][] = [
            'unid' => $unid,
            'applicant_id' => $applicantId,
            'email' => $applicantEmail,
        ];
    
        // Insert into the database
        $insertSql = "INSERT INTO tbl_cart (university_id, applicant_id, email) VALUES (:unid, :applicant_id, :email)";
        $insertQuery = $dbh->prepare($insertSql);
        $insertQuery->bindParam(':unid', $unid, PDO::PARAM_INT);
        $insertQuery->bindParam(':applicant_id', $applicantId, PDO::PARAM_INT);
        $insertQuery->bindParam(':email', $applicantEmail, PDO::PARAM_STR);
        $insertQuery->execute();
             // Trigger modal
             echo "<script>
             window.addEventListener('DOMContentLoaded', (event) => {
                 $('#successModal').modal('show');
             });
           </script>";
 }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Find Universities</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            position: relative;
        }
        .background-circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        .circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(10, 50, 100, 0.5);
        }
        .circle:nth-child(1) { width: 100px; height: 100px; top: 20%; left: 10%; }
        .circle:nth-child(2) { width: 120px; height: 120px; top: 40%; left: 30%; }
        .circle:nth-child(3) { width: 150px; height: 150px; top: 60%; left: 60%; }
        .circle:nth-child(4) { width: 130px; height: 130px; top: 80%; left: 20%; }
        .circle:nth-child(5) { width: 140px; height: 140px; top: 10%; left: 70%; }
        .circle:nth-child(6) { width: 160px; height: 160px; top: 50%; left: 50%; }
        .circle:nth-child(7) { width: 110px; height: 110px; top: 30%; left: 80%; }
        .fixed-size {
        width: 550px; /* Set your desired width */
        height: 300px; /* Set your desired height */
        object-fit: cover; /* Maintains aspect ratio and crops if necessary */
    }
    </style>
</head>
<body class="bg-light-blue-100">
    <div class="background-circles">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>

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
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                University has been added to your cart successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <div class="container my-5">
        <h2 class="text-center">Find Universities</h2>
        <div class="row">
            <?php foreach ($universities as $university): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                    <img src="../admission/uploads/university_logos/<?php echo htmlentities($university->logoPath); ?>" class="card-img-top fixed-size" alt="University Logo">
                    <div class="card-body">
                            <h5 class="card-title"><?php echo htmlentities($university->universityName); ?></h5>
                            <p class="card-text"><?php echo substr(htmlentities($university->aboutUniversity), 0, 100); ?>...</p>
                        </div>
                        <div class="card-footer">
                            <form method="post" action="">
                                <input type="hidden" name="unid" value="<?php echo $university->unid; ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Apply</button>
                                <a href="university-details.php?id=<?php echo $university->unid; ?>" class="btn btn-secondary">Read More</a>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

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
                    <h4 class="text-xl font-semibold mb-4">Stay Connected</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-gray-400"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="hover:text-gray-400"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-gray-400"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-gray-400"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Newsletter</h4>
                    <form>
                        <input type="email" placeholder="Enter your email" class="px-4 py-2 mb-2 w-full bg-gray-700 border border-gray-600 text-white">
                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white">Subscribe</button>
                    </form>
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
</body>
</html>
