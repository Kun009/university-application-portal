<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if the user is logged in
if(strlen($_SESSION['ologin']) == "") {   
    header("Location: index.php"); 
} else {
    // Retrieve the appid from the query string
    $appid = intval($_GET['appid']);

    // Check if appid is valid
    if ($appid <= 0) {
        die("Invalid application ID.");
    }

    // Fetch the details of the admitted applicant from tbl_applications using the appid
    $sql = "SELECT * FROM tbl_applications WHERE id = :appid AND admissionstatus = 'admitted'";
    $query = $dbh->prepare($sql);
    $query->bindParam(':appid', $appid, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Check if any result was returned
    if (!$result) {
        die("No admitted applicant found with the given ID.");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admitted Applicant Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">

        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php');?> 

        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php');?>  

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Admitted Applicant Details</h2>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <h3>Applicant Information</h3>
                                                <table class="table table-bordered table-striped">
                                                    <tr>
                                                        <th>First Name</th>
                                                        <td><?php echo htmlentities($result->firstName); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Middle Name</th>
                                                        <td><?php echo htmlentities($result->middleName); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Name</th>
                                                        <td><?php echo htmlentities($result->lastName); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email</th>
                                                        <td><?php echo htmlentities($result->email); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Program</th>
                                                        <td><?php echo htmlentities($result->program); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Degree Type</th>
                                                        <td><?php echo htmlentities($result->degreeType); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Session</th>
                                                        <td><?php echo htmlentities($result->session); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Disciplinary Issues</th>
                                                        <td><?php echo htmlentities($result->disciplinaryIssues); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Felony Charge</th>
                                                        <td><?php echo htmlentities($result->felonyCharge); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Admission Status</th>
                                                        <td><?php echo htmlentities($result->admissionstatus); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Admission Letter</th>
                                                        <td>
                                                            <?php if ($result->admissionLetter) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->admissionLetter); ?>" target="_blank">Download Admission Letter</a>
                                                            <?php } else { ?>
                                                                No admission letter uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <h3>Uploaded Documents</h3>
                                                <table class="table table-bordered table-striped">
                                                    <tr>
                                                        <th>Transcripts</th>
                                                        <td>
                                                            <?php if ($result->transcripts) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->transcripts); ?>" target="_blank">Download Transcripts</a>
                                                            <?php } else { ?>
                                                                No transcripts uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Diploma</th>
                                                        <td>
                                                            <?php if ($result->diploma) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->diploma); ?>" target="_blank">Download Diploma</a>
                                                            <?php } else { ?>
                                                                No diploma uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Statement of Purpose</th>
                                                        <td>
                                                            <?php if ($result->statement_of_purpose) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->statement_of_purpose); ?>" target="_blank">Download Statement of Purpose</a>
                                                            <?php } else { ?>
                                                                No statement of purpose uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Recommendation Letters</th>
                                                        <td>
                                                            <?php if ($result->recommendation_letters) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->recommendation_letters); ?>" target="_blank">Download Recommendation Letters</a>
                                                            <?php } else { ?>
                                                                No recommendation letters uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resume</th>
                                                        <td>
                                                            <?php if ($result->resume) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->resume); ?>" target="_blank">Download Resume</a>
                                                            <?php } else { ?>
                                                                No resume uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Proof of English Proficiency</th>
                                                        <td>
                                                            <?php if ($result->proof_of_english_proficiency) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->proof_of_english_proficiency); ?>" target="_blank">Download Proof of English Proficiency</a>
                                                            <?php } else { ?>
                                                                No proof of English proficiency uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Financial Documents</th>
                                                        <td>
                                                            <?php if ($result->financial_documents) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->financial_documents); ?>" target="_blank">Download Financial Documents</a>
                                                            <?php } else { ?>
                                                                No financial documents uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Passport Copy</th>
                                                        <td>
                                                            <?php if ($result->passport_copy) { ?>
                                                                <a href="../admission/uploads/<?php echo htmlentities($result->passport_copy); ?>" target="_blank">Download Passport Copy</a>
                                                            <?php } else { ?>
                                                                No passport copy uploaded.
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <a href="admit.php" class="btn btn-primary">Back to Admitted Applications</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Files -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

<?php } ?>
