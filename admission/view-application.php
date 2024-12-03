<?php 
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid'] == 0)) {
    header('location:logout.php');
} else {
    // Check if 'aid' is set in the URL
    if (isset($_GET['aid'])) {
        $applicantId = $_GET['aid'];

        // Fetch applicant details from the database
        $query = mysqli_query($con, "SELECT * FROM tbl_applications WHERE id='$applicantId'");
        $applicant = mysqli_fetch_array($query);

        // Check if the applicant exists
        if (!$applicant) {
            echo "<script>alert('No applicant found with the given ID.');</script>";
            echo "<script>window.location.href='manage-applicantion.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invalid applicant ID.');</script>";
        echo "<script>window.location.href='manage-applicantion.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Application</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="wrapper">
        <?php include('leftbar.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header"><?php echo strtoupper("Welcome " . htmlentities($_SESSION['login'])); ?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Applicant Details
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td><?php echo htmlentities($applicant['id']); ?></td>
                                </tr>
                                <tr>
                                    <th>First Name</th>
                                    <td><?php echo htmlentities(ucwords($applicant['firstName'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Middle Name</th>
                                    <td><?php echo htmlentities(ucwords($applicant['middleName'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td><?php echo htmlentities(ucwords($applicant['lastName'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Program</th>
                                    <td><?php echo htmlentities($applicant['program']); ?></td>
                                </tr>
                                <tr>
                                    <th>Degree Type</th>
                                    <td><?php echo htmlentities($applicant['degreeType']); ?></td>
                                </tr>
                                <tr>
                                    <th>Session</th>
                                    <td><?php echo htmlentities($applicant['session']); ?></td>
                                </tr>
                                <tr>
                                    <th>University ID</th>
                                    <td><?php echo htmlentities($applicant['universityId']); ?></td>
                                </tr>
                                <tr>
                                    <th>Disciplinary Issues</th>
                                    <td><?php echo htmlentities($applicant['disciplinaryIssues']); ?></td>
                                </tr>
                                <tr>
                                    <th>Felony Charge</th>
                                    <td><?php echo htmlentities($applicant['felonyCharge']); ?></td>
                                </tr>
                                <tr>
                                    <th>Admission Status</th>
                                    <td><?php echo htmlentities($applicant['admissionstatus']); ?></td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td><?php echo htmlentities($applicant['created_at']); ?></td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td><?php echo htmlentities($applicant['updated_at']); ?></td>
                                </tr>
                                <tr>
                                    <th>Transcripts</th>
                                    <td>
                                        <?php echo !empty($applicant['transcripts']) ? '<a href="uploads/' . htmlentities($applicant['transcripts']) . '" target="_blank">View Transcript</a>' : 'No Transcript Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diploma</th>
                                    <td>
                                        <?php echo !empty($applicant['diploma']) ? '<a href="uploads/' . htmlentities($applicant['diploma']) . '" target="_blank">View Diploma</a>' : 'No Diploma Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statement of Purpose</th>
                                    <td>
                                        <?php echo !empty($applicant['statement_of_purpose']) ? '<a href="uploads/' . htmlentities($applicant['statement_of_purpose']) . '" target="_blank">View Statement</a>' : 'No Statement Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Recommendation Letters</th>
                                    <td>
                                        <?php echo !empty($applicant['recommendation_letters']) ? '<a href="uploads/' . htmlentities($applicant['recommendation_letters']) . '" target="_blank">View Recommendations</a>' : 'No Recommendations Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Standardized Test Scores</th>
                                    <td>
                                        <?php echo !empty($applicant['standardized_test_scores']) ? '<a href="uploads/' . htmlentities($applicant['standardized_test_scores']) . '" target="_blank">View Scores</a>' : 'No Scores Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Resume</th>
                                    <td>
                                        <?php echo !empty($applicant['resume']) ? '<a href="uploads/' . htmlentities($applicant['resume']) . '" target="_blank">View Resume</a>' : 'No Resume Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Personal Statement</th>
                                    <td>
                                        <?php echo !empty($applicant['personal_statement']) ? '<a href="uploads/' . htmlentities($applicant['personal_statement']) . '" target="_blank">View Personal Statement</a>' : 'No Personal Statement Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Proof of English Proficiency</th>
                                    <td>
                                        <?php echo !empty($applicant['proof_of_english_proficiency']) ? '<a href="uploads/' . htmlentities($applicant['proof_of_english_proficiency']) . '" target="_blank">View Proof</a>' : 'No Proof Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Financial Documents</th>
                                    <td>
                                        <?php echo !empty($applicant['financial_documents']) ? '<a href="uploads/' . htmlentities($applicant['financial_documents']) . '" target="_blank">View Financial Docs</a>' : 'No Financial Documents Available'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Passport Copy</th>
                                    <td>
                                        <?php echo !empty($applicant['passport_copy']) ? '<a href="uploads/' . htmlentities($applicant['passport_copy']) . '" target="_blank">View Passport Copy</a>' : 'No Passport Copy Available'; ?>
                                    </td>
                                </tr>
                            </table>

                            <a href="manage-application.php" class="btn btn-default">Back to Manage Application</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="bower_components/metisMenu/dist/metisMenu.min.js" type="text/javascript"></script>
    <script src="dist/js/sb-admin-2.js" type="text/javascript"></script>
</body>
</html>
