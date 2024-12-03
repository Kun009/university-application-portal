<?php
session_start();
include('../officer/includes/config.php'); // Adjust path as needed

// Initialize variables
$applicant = null;
$profile = null;
$showLoginModal = false;

// Ensure the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    $showLoginModal = true; // Set to true to show modal
} else {
    $applicantId = $_SESSION['applicant_id']; // Only access if set

    // Fetch applicant data
    $sqlApplicant = "SELECT first_name, last_name, middle_name, email FROM tbl_applicant WHERE id = :applicant_id";
    $queryApplicant = $dbh->prepare($sqlApplicant);
    $queryApplicant->bindParam(':applicant_id', $applicantId, PDO::PARAM_INT);
    $queryApplicant->execute();
    $applicant = $queryApplicant->fetch(PDO::FETCH_OBJ);
    
    // Check if the applicant was found
    if (!$applicant) {
        $showLoginModal = true; // Set to true to show modal
    } else {
        // Proceed with fetching profile data
        $sqlProfile = "SELECT disciplinaryIssues, felonyCharge, transcripts, diploma, 
        recommendation_letters, standardized_test_scores, resume, 
        proof_of_english_proficiency, financial_documents, passport_copy 
        FROM tbl_profile WHERE applicant_id = :applicant_id";
        $queryProfile = $dbh->prepare($sqlProfile);
        $queryProfile->bindParam(':applicant_id', $applicantId, PDO::PARAM_INT);
        $queryProfile->execute();
        $profile = $queryProfile->fetch(PDO::FETCH_OBJ);
    }
}

// Check if the profile was found
if (!$profile) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { $('#profileModal').modal('show'); });</script>";
}

// Fetch universities in the applicant's cart
$sqlCart = "SELECT u.unid, u.universityName FROM tbl_cart c 
JOIN tbl_university u ON c.university_id = u.unid 
WHERE c.applicant_id = :applicant_id";
$queryCart = $dbh->prepare($sqlCart);
$queryCart->bindParam(':applicant_id', $applicantId, PDO::PARAM_INT);
$queryCart->execute();
$universities = $queryCart->fetchAll(PDO::FETCH_OBJ);

// Fetch available sessions
$sqlSessions = "SELECT sid, session FROM session";
$querySessions = $dbh->prepare($sqlSessions);
$querySessions->execute();
$sessions = $querySessions->fetchAll(PDO::FETCH_OBJ);

// Handle form submission for each university
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $universityId = $_POST['unid'];
    $program = $_POST['program'];
    $degreeType = $_POST['degreeType'];
    $session = $_POST['session'];

    // File upload handling for statement of purpose
    if (isset($_FILES['personal_statement']) && $_FILES['personal_statement']['error'] == 0) {
        $statementOfPurpose = $_FILES['personal_statement']['name'];
        $targetDirectory = "../admission/uploads/";
        $targetFile = $targetDirectory . basename($statementOfPurpose);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['personal_statement']['tmp_name'], $targetFile)) {
            // Insert application data into tbl_applications
            $sqlInsert = "INSERT INTO tbl_applications 
            (applicant_id, universityId, program, degreeType, session, personal_statement, 
            firstName, middleName, lastName, email, disciplinaryIssues, felonyCharge, 
            transcripts, diploma, recommendation_letters, standardized_test_scores, 
            resume, proof_of_english_proficiency, financial_documents, passport_copy, 
            created_at, updated_at) 
            VALUES 
            (:applicant_id, :universityId, :program, :degreeType, :session, :personal_statement, 
            :firstName, :middleName, :lastName, :email, :disciplinaryIssues, :felonyCharge, 
            :transcripts, :diploma, :recommendation_letters, :standardized_test_scores, 
            :resume, :proof_of_english_proficiency, :financial_documents, :passport_copy, 
            NOW(), NOW())";
            $queryInsert = $dbh->prepare($sqlInsert);

            // Bind parameters with applicant profile and input values
            $queryInsert->execute([
                ':applicant_id' => $applicantId,
                ':universityId' => $universityId,
                ':program' => $program,
                ':degreeType' => $degreeType,
                ':session' => $session,
                ':personal_statement' => $statementOfPurpose,
                ':firstName' => $applicant->first_name ?? '',
                ':middleName' => $applicant->middle_name ?? '',
                ':lastName' => $applicant->last_name ?? '',
                ':email' => $applicant->email ?? '',
                ':disciplinaryIssues' => $profile->disciplinaryIssues ?? '',
                ':felonyCharge' => $profile->felonyCharge ?? '',
                ':transcripts' => $profile->transcripts ?? '',
                ':diploma' => $profile->diploma ?? '',
                ':recommendation_letters' => $profile->recommendation_letters ?? '',
                ':standardized_test_scores' => $profile->standardized_test_scores ?? '',
                ':resume' => $profile->resume ?? '',
                ':proof_of_english_proficiency' => $profile->proof_of_english_proficiency ?? '',
                ':financial_documents' => $profile->financial_documents ?? '',
                ':passport_copy' => $profile->passport_copy ?? '',
            ]);

            // Check if the insertion was successful
            if ($queryInsert->rowCount() > 0) {
                // Delete the application from the cart
                $sqlDeleteCart = "DELETE FROM tbl_cart WHERE applicant_id = :applicant_id AND university_id = :university_id";
                $queryDeleteCart = $dbh->prepare($sqlDeleteCart);
                $queryDeleteCart->bindParam(':applicant_id', $applicantId, PDO::PARAM_INT);
                $queryDeleteCart->bindParam(':university_id', $universityId, PDO::PARAM_INT);
                $queryDeleteCart->execute();

                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    $('#successModal').modal('show');
                });
                </script>";
            } else {
                echo "<script>alert('Failed to submit application. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload the Statement of Purpose. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No Statement of Purpose uploaded or there was an upload error.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Apply to Universities</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    html, body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    .container {
        flex: 1; /* Allows the container to grow and fill the available space */
    }

    footer {
        margin-top: auto; /* Pushes the footer to the bottom */
    }
</style>

</head>
<body>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Please log in or create an account to apply.
                </div>
                <div class="modal-footer">
                    <a href="login.php" class="btn btn-primary">Go to Login</a>
                </div>
            </div>
        </div>
</div>
    <!-- Profile Missing Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Profile Missing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Profile not found. Please complete your profile.
                </div>
                <div class="modal-footer">
                    <a href="profile.php" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
        </div>
    </div>
 <!-- Success Modal -->
 <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Application Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Application submitted successfully and removed from cart!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='apply-to-university.php'">OK</button>
                </div>
            </div>
        </div>
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

    <!-- Application Form Section -->
<div class="container my-5">
    <h2 class="text-center">Apply to Universities</h2>
    <?php if (empty($universities)): // Check if there are no universities in the cart ?>
        <div class="alert alert-warning text-center" role="alert">
            No university has been selected. Please go back to find universities to select an university.
        </div>
    <?php else: // If there are universities, display the application form ?>
        <?php foreach ($universities as $university): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlentities($university->universityName); ?></h5>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="unid" value="<?php echo $university->unid; ?>">
                        <div class="form-group">
    <label for="program">Program</label>
    <select class="form-control" name="program" required>
        <option value="">Select a program</option>
        <option value="Computer Science">Computer Science</option>
        <option value="Information Technology">Information Technology</option>
        <option value="Software Engineering">Software Engineering</option>
        <option value="Data Science">Data Science</option>
        <option value="Cybersecurity">Cybersecurity</option>
        <option value="Mechanical Engineering">Mechanical Engineering</option>
        <option value="Electrical Engineering">Electrical Engineering</option>
        <option value="Civil Engineering">Civil Engineering</option>
        <option value="Chemical Engineering">Chemical Engineering</option>
        <option value="Business Administration">Business Administration</option>
        <option value="Economics">Economics</option>
        <option value="Mathematics">Mathematics</option>
        <option value="Physics">Physics</option>
        <option value="Biology">Biology</option>
        <option value="Environmental Science">Environmental Science</option>
        <option value="Nursing">Nursing</option>
        <option value="Education">Education</option>
        <option value="Psychology">Psychology</option>
        <option value="Graphic Design">Graphic Design</option>
        <option value="Marketing">Marketing</option>
        <!-- Add more programs as needed -->
    </select>
</div>

                        
                        <div class="form-group">
                            <label for="degreeType">Degree Type</label>
                            <select class="form-control" name="degreeType" required>
                                <option value="Bachelor" <?php if (isset($_POST['degreeType']) && $_POST['degreeType'] == 'Bachelor') echo 'selected'; ?>>Bachelor</option>
                                <option value="Masters" <?php if (isset($_POST['degreeType']) && $_POST['degreeType'] == 'Masters') echo 'selected'; ?>>Masters</option>
                                <option value="PhD" <?php if (isset($_POST['degreeType']) && $_POST['degreeType'] == 'PhD') echo 'selected'; ?>>PhD</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="session">Session</label>
                            <select class="form-control" name="session" required>
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?php echo $session->session; ?>">
                                        <?php echo htmlentities($session->session); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="personal_statement">Statement of Purpose</label>
                            <input type="file" class="form-control-file" name="personal_statement" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


                        

                        

                  
    </div>
<!-- Footer -->
<footer class="bg-gray-800 text-white py-10 mt-auto">
        <div class="container mx-auto px-4 text-center">
        
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
            </div>
            <p class="text-center mt-10">&copy; 2024 UniApply. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
    // Show the login modal if needed
    $(document).ready(function() {
        <?php if ($showLoginModal): ?>
            $('#loginModal').modal('show');
        <?php endif; ?>
    });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
