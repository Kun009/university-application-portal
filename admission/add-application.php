<?php 
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $firstName = $_POST['first-name'];
        $middleName = $_POST['middle-name'];
        $lastName = $_POST['last-name'];
        $email = $_POST['email'];
        $program = $_POST['program'];
        $degreeType = $_POST['degree-type'];
        $session = $_POST['session'];
        $universityId = $_POST['university'];
        $disciplinaryIssues = $_POST['disciplinary-issues'];
        $felonyCharge = $_POST['felony-charge'];
        $uploadStatus = 'submitted';

        // Prepare to upload documents
        $uploads = [];
        $docTypes = ['transcripts', 'diploma', 'statement_of_purpose', 'recommendation_letters', 'standardized_test_scores', 'resume', 'personal_statement', 'proof_of_english_proficiency', 'financial_documents', 'passport_copy'];

        foreach ($docTypes as $doc) {
            if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] == UPLOAD_ERR_OK) {
                $uploads[$doc] = $_FILES[$doc]['name'];
                move_uploaded_file($_FILES[$doc]['tmp_name'], "uploads/" . $_FILES[$doc]['name']);
            }
        }

        $query = mysqli_query($con, "INSERT INTO tbl_applications (firstName, middleName, lastName, email, program, degreeType, session, universityId, disciplinaryIssues, felonyCharge, uploadStatus, transcripts, diploma, statement_of_purpose, recommendation_letters, standardized_test_scores, resume, personal_statement, proof_of_english_proficiency, financial_documents, passport_copy) VALUES ('$firstName', '$middleName', '$lastName', '$email', '$program', '$degreeType', '$session', '$universityId', '$disciplinaryIssues', '$felonyCharge', '$uploadStatus', '{$uploads['transcripts']}', '{$uploads['diploma']}', '{$uploads['statement_of_purpose']}', '{$uploads['recommendation_letters']}', '{$uploads['standardized_test_scores']}', '{$uploads['resume']}', '{$uploads['personal_statement']}', '{$uploads['proof_of_english_proficiency']}', '{$uploads['financial_documents']}', '{$uploads['passport_copy']}')");
    
        if ($query) {
            echo '<script>alert("Application submitted successfully")</script>';
            echo "<script>window.location.href='manage-application.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again")</script>';
            echo '<script>window.location.href="add-application.php"</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add Application</title>
    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
    <form method="post" action="" enctype="multipart/form-data">
        <div id="wrapper">
            <!-- Navigation -->
            <?php include('leftbar.php'); ?>

            <!-- Page Wrapper -->
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="page-header">Welcome, <?php echo htmlentities($_SESSION['login']); ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Add Application</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="form-group">
                                            <label for="first-name">First Name<span style="color:red">*</span></label>
                                            <input class="form-control" name="first-name" id="first-name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="middle-name">Middle Name</label>
                                            <input class="form-control" name="middle-name" id="middle-name">
                                        </div>
                                        <div class="form-group">
                                            <label for="last-name">Last Name<span style="color:red">*</span></label>
                                            <input class="form-control" name="last-name" id="last-name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email<span style="color:red">*</span></label>
                                            <input class="form-control" name="email" id="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="program">Program<span style="color:red">*</span></label>
                                            <input class="form-control" name="program" id="program" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="degree-type">Degree Type<span style="color:red">*</span></label>
                                            <select class="form-control" name="degree-type" id="degree-type" required>
                                                <option value="">Select Degree Type</option>
                                                <option value="bachelor">Bachelor's</option>
                                                <option value="master">Master's</option>
                                                <option value="phd">PhD</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="session">Session<span style="color:red">*</span></label>
                                            <select class="form-control" name="session" id="session" required>
                                                <?php
                                                $sessionQuery = mysqli_query($con, "SELECT session FROM session");
                                                while ($row = mysqli_fetch_assoc($sessionQuery)) {
                                                    echo "<option value='" . $row['session'] . "'>" . $row['session'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Select University<span style="color:red">*</span></label>
                                            <select class="form-control" name="university" id="university" required>
                                                <?php
                                                $universityQuery = mysqli_query($con, "SELECT unid, universityName FROM tbl_university");
                                                while ($row = mysqli_fetch_assoc($universityQuery)) {
                                                    echo "<option value='" . $row['unid'] . "'>" . $row['universityName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Have you ever been involved in any disciplinary issues?*</label>
                                            <select class="form-control" name="disciplinary-issues" required>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Have you ever been convicted of a felony charge?*</label>
                                            <select class="form-control" name="felony-charge" required>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>

                                        <h5>Upload Required Documents:</h5>
                                        <div class="form-group">
                                            <label for="transcripts">Transcripts</label>
                                            <input type="file" class="form-control" name="transcripts" id="transcripts">
                                        </div>
                                        <div class="form-group">
                                            <label for="diploma">Diploma/Certificate</label>
                                            <input type="file" class="form-control" name="diploma" id="diploma">
                                        </div>
                                        <div class="form-group">
                                            <label for="statement_of_purpose">Statement of Purpose</label>
                                            <input type="file" class="form-control" name="statement_of_purpose" id="statement_of_purpose">
                                        </div>
                                        <div class="form-group">
                                            <label for="recommendation_letters">Letters of Recommendation</label>
                                            <input type="file" class="form-control" name="recommendation_letters" id="recommendation_letters">
                                        </div>
                                        <div class="form-group">
                                            <label for="standardized_test_scores">Standardized Test Scores</label>
                                            <input type="file" class="form-control" name="standardized_test_scores" id="standardized_test_scores">
                                        </div>
                                        <div class="form-group">
                                            <label for="resume">Resume/CV</label>
                                            <input type="file" class="form-control" name="resume" id="resume">
                                        </div>
                                        <div class="form-group">
                                            <label for="personal_statement">Personal Statement</label>
                                            <input type="file" class="form-control" name="personal_statement" id="personal_statement">
                                        </div>
                                        <div class="form-group">
                                            <label for="proof_of_english_proficiency">Proof of English Proficiency</label>
                                            <input type="file" class="form-control" name="proof_of_english_proficiency" id="proof_of_english_proficiency">
                                        </div>
                                        <div class="form-group">
                                            <label for="financial_documents">Financial Documents</label>
                                            <input type="file" class="form-control" name="financial_documents" id="financial_documents">
                                        </div>
                                        <div class="form-group">
                                            <label for="passport_copy">Passport Copy</label>
                                            <input type="file" class="form-control" name="passport_copy" id="passport_copy">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                            <a href="manage-application.php" class="btn btn-danger">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="bower_components/metisMenu/dist/metisMenu.min.js" type="text/javascript"></script>
    <script src="dist/js/sb-admin-2.js" type="text/javascript"></script>

</html>
<?php 
}
?>
