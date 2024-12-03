<?php
session_start();
include('../officer/includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>alert('Please log in to edit your profile.'); window.location.href='login.php';</script>";
    exit();
}

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

// Initialize $uploadedFiles array to store file paths
$uploadedFiles = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $middleName = htmlspecialchars($_POST['middle_name']);
    $email = htmlspecialchars($_POST['email']);
    
    $mobileNo = htmlspecialchars($_POST['mobile_no']);
    $address = htmlspecialchars($_POST['address']);
    $country = htmlspecialchars($_POST['country']);
    $dateOfBirth = htmlspecialchars($_POST['date_of_birth']);
    $gender = htmlspecialchars($_POST['gender']);
    $maritalStatus = htmlspecialchars($_POST['marital_status']);
    $qualification = htmlspecialchars($_POST['qualification']);

    // Process file uploads, store paths in $uploadedFiles
    $fileFields = ['transcripts', 'diploma', 'recommendation_letters', 'standardized_test_scores', 'resume', 'proof_of_english_proficiency', 'financial_documents', 'passport_copy'];
    foreach ($fileFields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $targetDir = "../admission/uploads/";
            $targetFile = $targetDir . basename($_FILES[$field]['name']);
            if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetFile)) {
                $uploadedFiles[$field] = $_FILES[$field]['name'];
            }
        }
    }

    // Update tbl_applicant
    $updateApplicantSql = "UPDATE tbl_applicant SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name, email = :email,  mobile_no = :mobile_no, address = :address, country = :country, date_of_birth = :date_of_birth, gender = :gender, marital_status = :marital_status, qualification = :qualification WHERE id = :id";
    $updateApplicantQuery = $dbh->prepare($updateApplicantSql);
    $updateApplicantQuery->bindParam(':first_name', $firstName);
    $updateApplicantQuery->bindParam(':last_name', $lastName);
    $updateApplicantQuery->bindParam(':middle_name', $middleName);
    $updateApplicantQuery->bindParam(':email', $email);
    $updateApplicantQuery->bindParam(':mobile_no', $mobileNo);
    $updateApplicantQuery->bindParam(':address', $address);
    $updateApplicantQuery->bindParam(':country', $country);
    $updateApplicantQuery->bindParam(':date_of_birth', $dateOfBirth);
    $updateApplicantQuery->bindParam(':gender', $gender);
    $updateApplicantQuery->bindParam(':marital_status', $maritalStatus);
    $updateApplicantQuery->bindParam(':qualification', $qualification);
    $updateApplicantQuery->bindParam(':id', $applicantId);

    // Execute the applicant update query
    $applicantUpdated = $updateApplicantQuery->execute();

    // Update tbl_profile
    $updateProfileSql = "UPDATE tbl_profile SET 
    transcripts = :transcripts, 
    diploma = :diploma, 
    recommendation_letters = :recommendation_letters, 
    standardized_test_scores = :standardized_test_scores, 
    resume = :resume, 
    proof_of_english_proficiency = :proof_of_english_proficiency, 
    financial_documents = :financial_documents, 
    passport_copy = :passport_copy, 
    felonyCharge = :felonyCharge, 
    disciplinaryIssues = :disciplinaryIssues 
    WHERE applicant_id = :applicant_id";

    $updateProfileQuery = $dbh->prepare($updateProfileSql);

    // Set default values for each field
    $defaultValues = [
        ':transcripts' => $uploadedFiles['transcripts'] ?? '',
        ':diploma' => $uploadedFiles['diploma'] ?? '',
        ':recommendation_letters' => $uploadedFiles['recommendation_letters'] ?? '',
        ':standardized_test_scores' => $uploadedFiles['standardized_test_scores'] ?? '',
        ':resume' => $uploadedFiles['resume'] ?? '',
        ':proof_of_english_proficiency' => $uploadedFiles['proof_of_english_proficiency'] ?? '',
        ':financial_documents' => $uploadedFiles['financial_documents'] ?? '',
        ':passport_copy' => $uploadedFiles['passport_copy'] ?? ''
    ];

    // Bind each value
    foreach ($defaultValues as $placeholder => $value) {
        $updateProfileQuery->bindValue($placeholder, $value);
    }

    // Add additional fields if they exist
    $felonyCharge = isset($_POST['felonyCharge']) ? $_POST['felonyCharge'] : null;
    $disciplinaryIssues = isset($_POST['disciplinaryIssues']) ? $_POST['disciplinaryIssues'] : null;

    $updateProfileQuery->bindParam(':felonyCharge', $felonyCharge);
    $updateProfileQuery->bindParam(':disciplinaryIssues', $disciplinaryIssues);
    $updateProfileQuery->bindParam(':applicant_id', $applicantId);

    // Execute the profile update query
    $profileUpdated = $updateProfileQuery->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Profile</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-shape {
            position: absolute;
            opacity: 0.5;
            z-index: 0;
        }
        .shape-1 {
            width: 100px;
            height: 100px;
            background: rgba(255, 105, 180, 0.6);
            border-radius: 50%;
            top: -20px;
            left: -50px;
        }
        .shape-2 {
            width: 150px;
            height: 150px;
            background: rgba(0, 123, 255, 0.6);
            border-radius: 50%;
            top: 200px;
            right: -100px;
        }
        .shape-3 {
            width: 200px;
            height: 200px;
            background: rgba(40, 167, 69, 0.6);
            border-radius: 50%;
            bottom: -50px;
            left: -100px;
        }
        .content {
            position: relative;
            z-index: 1;
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
    <div class="container mt-5">
        <div class="content">
            <!-- Background Shapes -->
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>

            <h1 class="text-3xl font-bold mb-4">Edit Profile</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Personal Details</h2>
                    <label for="first_name" class="block">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($applicant->first_name); ?>" required>

                    <label for="last_name" class="block mt-2">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($applicant->last_name); ?>" required>

                    <label for="middle_name" class="block mt-2">Middle Name:</label>
                    <input type="text" id="middle_name" name="middle_name" class="form-control" value="<?php echo htmlspecialchars($applicant->middle_name); ?>">

                    <label for="email" class="block mt-2">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($applicant->email); ?>" required>

                    <label for="mobile_no" class="block mt-2">Mobile No:</label>
                    <input type="text" id="mobile_no" name="mobile_no" class="form-control" value="<?php echo htmlspecialchars($applicant->mobile_no); ?>" required>

                    <label for="address" class="block mt-2">Address:</label>
                    <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($applicant->address); ?>" required>

                    <label for="country" class="block mt-2">Country:</label>
                    <input type="text" id="country" name="country" class="form-control" value="<?php echo htmlspecialchars($applicant->country); ?>" required>

                    <label for="date_of_birth" class="block mt-2">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo htmlspecialchars($applicant->date_of_birth); ?>" required>

                    <label for="gender" class="block mt-2">Gender:</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="Male" <?php echo ($applicant->gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($applicant->gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>

                    <label for="marital_status" class="block mt-2">Marital Status:</label>
                    <select id="marital_status" name="marital_status" class="form-control" required>
                        <option value="Single" <?php echo ($applicant->marital_status == 'Single') ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?php echo ($applicant->marital_status == 'Married') ? 'selected' : ''; ?>>Married</option>
                    </select>

                    <label for="qualification" class="block mt-2">Qualification:</label>
                    <input type="text" id="qualification" name="qualification" class="form-control" value="<?php echo htmlspecialchars($applicant->qualification); ?>" required>
                </div>

                <div class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Upload Documents</h2>
                    <label for="transcripts" class="block">Transcripts:</label>
                    <input type="file" id="transcripts" name="transcripts" class="form-control">

                    <label for="diploma" class="block mt-2">Diploma:</label>
                    <input type="file" id="diploma" name="diploma" class="form-control">

                    <label for="recommendation_letters" class="block mt-2">Recommendation Letters:</label>
                    <input type="file" id="recommendation_letters" name="recommendation_letters" class="form-control">

                    <label for="standardized_test_scores" class="block mt-2">Standardized Test Scores:</label>
                    <input type="file" id="standardized_test_scores" name="standardized_test_scores" class="form-control">

                    <label for="resume" class="block mt-2">Resume:</label>
                    <input type="file" id="resume" name="resume" class="form-control">

                    <label for="proof_of_english_proficiency" class="block mt-2">Proof of English Proficiency:</label>
                    <input type="file" id="proof_of_english_proficiency" name="proof_of_english_proficiency" class="form-control">

                    <label for="financial_documents" class="block mt-2">Financial Documents:</label>
                    <input type="file" id="financial_documents" name="financial_documents" class="form-control">

                    <label for="passport_copy" class="block mt-2">Passport Copy:</label>
                    <input type="file" id="passport_copy" name="passport_copy" class="form-control">
                </div>
                <div class="mb-4">
    <h2 class="text-2xl font-semibold mb-2">Background Check</h2>
    <label for="felonyCharge" class="block">Have you ever been convicted of a felony?</label>
    <select id="felonyCharge" name="felonyCharge" class="form-control">
        <option value="No" <?php echo (empty($profile->felonyCharge) || $profile->felonyCharge == 'No') ? 'selected' : ''; ?>>No</option>
        <option value="Yes" <?php echo ($profile->felonyCharge == 'Yes') ? 'selected' : ''; ?>>Yes</option>
    </select>

    <label for="disciplinaryIssues" class="block mt-2">Have you ever had any disciplinary issues?</label>
    <select id="disciplinaryIssues" name="disciplinaryIssues" class="form-control">
        <option value="No" <?php echo (empty($profile->disciplinaryIssues) || $profile->disciplinaryIssues == 'No') ? 'selected' : ''; ?>>No</option>
        <option value="Yes" <?php echo ($profile->disciplinaryIssues == 'Yes') ? 'selected' : ''; ?>>Yes</option>
    </select>
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
            const dropdown = document.getElementById("dropdown");
            dropdown.classList.toggle("hidden");
        }
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle')) {
                const dropdowns = document.getElementsByClassName("dropdown");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
