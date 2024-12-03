<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['ologin']) == "") {
    header("Location: index.php");
} else {
    // Get application details
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM tbl_applications WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    if ($query->rowCount() > 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Application Details</title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5>Application Details</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body p-20">
                                        <table class="table table-bordered">
                                            <tr><th>First Name</th><td><?php echo htmlentities($result->firstName); ?></td></tr>
                                            <tr><th>Middle Name</th><td><?php echo htmlentities($result->middleName); ?></td></tr>
                                            <tr><th>Last Name</th><td><?php echo htmlentities($result->lastName); ?></td></tr>
                                            <tr><th>Email</th><td><?php echo htmlentities($result->email); ?></td></tr>
                                            <tr><th>Program</th><td><?php echo htmlentities($result->program); ?></td></tr>
                                            <tr><th>Degree Type</th><td><?php echo htmlentities($result->degreeType); ?></td></tr>
                                            <tr><th>Session</th><td><?php echo htmlentities($result->session); ?></td></tr>
                                            <tr><th>University ID</th><td><?php echo htmlentities($result->universityId); ?></td></tr>
                                            <tr><th>Disciplinary Issues</th><td><?php echo htmlentities($result->disciplinaryIssues); ?></td></tr>
                                            <tr><th>Felony Charge</th><td><?php echo htmlentities($result->felonyCharge); ?></td></tr>
                                            <tr><th>Upload Status</th><td><?php echo htmlentities($result->admissionstatus); ?></td></tr>
                                            <tr><th>Created At</th><td><?php echo htmlentities($result->created_at); ?></td></tr>
                                            <tr><th>Updated At</th><td><?php echo htmlentities($result->updated_at); ?></td></tr>
                                            <tr><th>Transcripts</th><td><a href="../admission/uploads/<?php echo htmlentities($result->transcripts); ?>" target="_blank">View Transcript</a></td></tr>
<tr><th>Diploma</th><td><a href="../admission/uploads/<?php echo htmlentities($result->diploma); ?>" target="_blank">View Diploma</a></td></tr>
<tr><th>Statement of Purpose</th><td><a href="../admission/uploads/<?php echo htmlentities($result->statement_of_purpose); ?>" target="_blank">View Statement of Purpose</a></td></tr>
<tr><th>Recommendation Letters</th><td><a href="../admission/uploads/<?php echo htmlentities($result->recommendation_letters); ?>" target="_blank">View Recommendation Letters</a></td></tr>
<tr><th>Standardized Test Scores</th><td><a href="../admission/uploads/<?php echo htmlentities($result->standardized_test_scores); ?>" target="_blank">View Standardized Test Scores</a></td></tr>
<tr><th>Resume</th><td><a href="../admission/uploads/<?php echo htmlentities($result->resume); ?>" target="_blank">View Resume</a></td></tr>
<tr><th>Personal Statement</th><td><a href="../admission/uploads/<?php echo htmlentities($result->personal_statement); ?>" target="_blank">View Personal Statement</a></td></tr>
<tr><th>Proof of English Proficiency</th><td><a href="../admission/uploads/<?php echo htmlentities($result->proof_of_english_proficiency); ?>" target="_blank">View Proof of English Proficiency</a></td></tr>
<tr><th>Financial Documents</th><td><a href="../admission/uploads/<?php echo htmlentities($result->financial_documents); ?>" target="_blank">View Financial Documents</a></td></tr>
<tr><th>Passport Copy</th><td><a href="../admission/uploads/<?php echo htmlentities($result->passport_copy); ?>" target="_blank">View Passport Copy</a></td></tr>
<tr><th>Admission Status</th><td><?php echo htmlentities($result->admissionstatus); ?></td></tr>
                                        </table>

                                        <button onclick="window.print()" class="btn btn-primary">Print</button>

                                        <form method="POST">
    <input type="submit" name="process" class="btn btn-warning" value="Begin Admission Processing">
</form>

<?php
if (isset($_POST['process'])) {
    $sql = "UPDATE tbl_applications SET admissionstatus = 'processing' WHERE id = :id"; // Fixed table name
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    echo "
    <script>
        $(document).ready(function() {
            $('#processModal').modal('show');
        });
    </script>";
}
?>



<!-- Modal -->
<div id="processModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="processModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Admission processing started.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.location.href='submit.php'">OK</button>
            </div>
        </div>
    </div>
</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <!-- JS Files -->
 <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/DataTables/datatables.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        $(function($) {
            $('#example').DataTable();
        });
    </script>
</body>
</html>
<?php 
    } 
} 
?>
