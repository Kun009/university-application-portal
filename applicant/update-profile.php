<?php
session_start();
include('../officer/includes/config.php'); // Adjust path as needed

// Check if the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>alert('Please log in to update your profile.'); window.location.href='login.php';</script>";
    exit();
}

// Get logged-in applicant's ID
$applicantId = $_SESSION['applicant_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $transcripts = $_FILES['transcripts']['name'] ? $_FILES['transcripts']['name'] : null;
    $diploma = $_FILES['diploma']['name'] ? $_FILES['diploma']['name'] : null;
    
    $recommendation_letters = $_FILES['recommendation_letters']['name'] ? $_FILES['recommendation_letters']['name'] : null;
    $standardized_test_scores = $_FILES['standardized_test_scores']['name'] ? $_FILES['standardized_test_scores']['name'] : null;
    $resume = $_FILES['resume']['name'] ? $_FILES['resume']['name'] : null;
    $proof_of_english_proficiency = $_FILES['proof_of_english_proficiency']['name'] ? $_FILES['proof_of_english_proficiency']['name'] : null;
    $financial_documents = $_FILES['financial_documents']['name'] ? $_FILES['financial_documents']['name'] : null;
    $passport_copy = $_FILES['passport_copy']['name'] ? $_FILES['passport_copy']['name'] : null;
     // Retrieve other form inputs
     $felonyCharge = isset($_POST['felonyCharge']) ? $_POST['felonyCharge'] : 'No';
     $disciplinaryIssues = isset($_POST['disciplinaryIssues']) ? $_POST['disciplinaryIssues'] : 'No';
 

    // Directory to store uploaded files
    $uploadDir = '../admission/uploads/';

    // Handle file uploads
    $fileUploadSuccess = true;
    foreach ($_FILES as $file) {
        if ($file['error'] == UPLOAD_ERR_OK) {
            move_uploaded_file($file['tmp_name'], $uploadDir . $file['name']);
        } else {
            $fileUploadSuccess = false;
            break;
        }
    }

   // Insert or update the profile in the database
if ($fileUploadSuccess) {
    // Prepare SQL statement with all placeholders
    $profileSql = "INSERT INTO tbl_profile (applicant_id, transcripts, diploma, recommendation_letters, standardized_test_scores, resume, proof_of_english_proficiency, financial_documents, passport_copy, felonyCharge, disciplinaryIssues)
    VALUES (:applicant_id, :transcripts, :diploma, :recommendation_letters, :standardized_test_scores, :resume, :proof_of_english_proficiency, :financial_documents, :passport_copy, :felonyCharge, :disciplinaryIssues)
    ON DUPLICATE KEY UPDATE 
    transcripts = :transcripts_update, 
    diploma = :diploma_update, 
    recommendation_letters = :recommendation_letters_update, 
    standardized_test_scores = :standardized_test_scores_update, 
    resume = :resume_update, 
    proof_of_english_proficiency = :proof_of_english_proficiency_update, 
    financial_documents = :financial_documents_update, 
    passport_copy = :passport_copy_update, 
    felonyCharge = :felonyCharge_update, 
    disciplinaryIssues = :disciplinaryIssues_update, 
    updated_at = CURRENT_TIMESTAMP";

    $profileQuery = $dbh->prepare($profileSql);

    // Bind parameters for insert
    $profileQuery->bindParam(':applicant_id', $applicantId, PDO::PARAM_INT);
    $profileQuery->bindParam(':transcripts', $transcripts, PDO::PARAM_STR);
    $profileQuery->bindParam(':diploma', $diploma, PDO::PARAM_STR);
    $profileQuery->bindParam(':recommendation_letters', $recommendation_letters, PDO::PARAM_STR);
    $profileQuery->bindParam(':standardized_test_scores', $standardized_test_scores, PDO::PARAM_STR);
    $profileQuery->bindParam(':resume', $resume, PDO::PARAM_STR);
    $profileQuery->bindParam(':proof_of_english_proficiency', $proof_of_english_proficiency, PDO::PARAM_STR);
    $profileQuery->bindParam(':financial_documents', $financial_documents, PDO::PARAM_STR);
    $profileQuery->bindParam(':passport_copy', $passport_copy, PDO::PARAM_STR);
    $profileQuery->bindParam(':felonyCharge', $felonyCharge, PDO::PARAM_STR);
    $profileQuery->bindParam(':disciplinaryIssues', $disciplinaryIssues, PDO::PARAM_STR);

    // Bind parameters for update
    $profileQuery->bindParam(':transcripts_update', $transcripts, PDO::PARAM_STR);
    $profileQuery->bindParam(':diploma_update', $diploma, PDO::PARAM_STR);
    $profileQuery->bindParam(':recommendation_letters_update', $recommendation_letters, PDO::PARAM_STR);
    $profileQuery->bindParam(':standardized_test_scores_update', $standardized_test_scores, PDO::PARAM_STR);
    $profileQuery->bindParam(':resume_update', $resume, PDO::PARAM_STR);
    $profileQuery->bindParam(':proof_of_english_proficiency_update', $proof_of_english_proficiency, PDO::PARAM_STR);
    $profileQuery->bindParam(':financial_documents_update', $financial_documents, PDO::PARAM_STR);
    $profileQuery->bindParam(':passport_copy_update', $passport_copy, PDO::PARAM_STR);
    $profileQuery->bindParam(':felonyCharge_update', $felonyCharge, PDO::PARAM_STR);
    $profileQuery->bindParam(':disciplinaryIssues_update', $disciplinaryIssues, PDO::PARAM_STR);

    // Execute query
    if ($profileQuery->execute()) {
        $message = "Profile updated successfully.";
        $modalType = "success";
    } else {
        $message = "Failed to update profile. Please try again.";
        $modalType = "error";
    }
    
}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Update Profile</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
/* Style for background circles */
.absolute {
    position: absolute;
    z-index: 0; /* Send to the background */
}

/* Ensure container is positioned properly over the background */
.container {
    position: relative;
    z-index: 1; /* Ensure it's above the background */
}
</style>

</head>
<body>
    <!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Profile updated successfully.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="window.location.href='profile.php';">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Failed to update profile. Please try again.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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
    <div class="relative bg-blue-100 py-10">
    <!-- Background circles -->
    <div class="absolute inset-0">
        <div class="absolute bg-blue-300 rounded-full opacity-50 w-32 h-32 top-5 left-10"></div>
        <div class="absolute bg-blue-400 rounded-full opacity-50 w-48 h-48 top-20 right-10"></div>
        <div class="absolute bg-blue-200 rounded-full opacity-50 w-24 h-24 bottom-10 left-5"></div>
        <div class="absolute bg-blue-300 rounded-full opacity-50 w-40 h-40 bottom-20 right-20"></div>
        <div class="absolute bg-blue-400 rounded-full opacity-50 w-36 h-36 top-36 left-1/2 transform -translate-x-1/2"></div>
        <div class="absolute bg-blue-200 rounded-full opacity-50 w-28 h-28 top-10 right-1/4"></div>
        <div class="absolute bg-blue-300 rounded-full opacity-50 w-32 h-32 bottom-10 right-1/3"></div>
    </div>
    
    <div class="container mx-auto px-4">
    <div class="container my-5">
        <h2 class="text-center">Update Profile</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="transcripts">Transcripts:</label>
                <input type="file" class="form-control-file" name="transcripts" id="transcripts">
            </div>
            <div class="form-group">
                <label for="diploma">Certificate:</label>
                <input type="file" class="form-control-file" name="diploma" id="diploma">
            </div>
          
            <div class="form-group">
                <label for="recommendation_letters">Recommendation Letters:</label>
                <input type="file" class="form-control-file" name="recommendation_letters" id="recommendation_letters">
            </div>
            <div class="form-group">
                <label for="standardized_test_scores">Standardized Test Scores:</label>
                <input type="file" class="form-control-file" name="standardized_test_scores" id="standardized_test_scores">
            </div>
            <div class="form-group">
                <label for="resume">Resume:</label>
                <input type="file" class="form-control-file" name="resume" id="resume">
            </div>
            <div class="form-group">
                <label for="proof_of_english_proficiency">Proof of English Proficiency:</label>
                <input type="file" class="form-control-file" name="proof_of_english_proficiency" id="proof_of_english_proficiency">
            </div>
            <div class="form-group">
                <label for="financial_documents">Financial Documents:</label>
                <input type="file" class="form-control-file" name="financial_documents" id="financial_documents">
            </div>
            <div class="form-group">
    <label for="felonyCharge">Felony Charge:</label>
    <select class="form-control" id="felonyCharge" name="felonyCharge" required>
        <option value="Yes" <?php echo (isset($profile->felonyCharge) && $profile->felonyCharge == 'Yes') ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo (isset($profile->felonyCharge) && $profile->felonyCharge == 'No') ? 'selected' : ''; ?>>No</option>
    </select>
</div>
<div class="form-group">
    <label for="disciplinaryIssues">Disciplinary Issues:</label>
    <select class="form-control" id="disciplinaryIssues" name="disciplinaryIssues" required>
        <option value="Yes" <?php echo (isset($profile->disciplinaryIssues) && $profile->disciplinaryIssues == 'Yes') ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo (isset($profile->disciplinaryIssues) && $profile->disciplinaryIssues == 'No') ? 'selected' : ''; ?>>No</option>
    </select>
</div>
            <div class="form-group">
                <label for="passport_copy">International Passport:</label>
                <input type="file" class="form-control-file" name="passport_copy" id="passport_copy">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
        </form>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
