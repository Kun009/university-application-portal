<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if officer is logged in
if (strlen($_SESSION['ologin']) == "") {   
    header("Location: index.php"); 
} else {
    // Fetch officer's university ID from session
    $officer_unid = $_SESSION['unid']; // Assuming the officer's unid is stored in the session

    // Count total applications where the officer's university matches
    $sqlTotalApplications = "SELECT COUNT(*) as totalApplications 
                             FROM tbl_applications
                             WHERE universityId = (SELECT unid FROM tbl_officer WHERE email = :officer_email)";
    $queryTotalApplications = $dbh->prepare($sqlTotalApplications);
    $queryTotalApplications->bindParam(':officer_email', $_SESSION['ologin'], PDO::PARAM_STR); // Assuming the officer's email is used for session login
    $queryTotalApplications->execute();
    $totalApplications = $queryTotalApplications->fetch(PDO::FETCH_OBJ)->totalApplications;

    // Count applications by admissionstatus where universityId matches officer's unid
    $sqlStatusCounts = "SELECT admissionstatus, COUNT(*) as statusCount 
                        FROM tbl_applications
                        WHERE universityId = (SELECT unid FROM tbl_officer WHERE email = :officer_email)
                        GROUP BY admissionstatus";
    $queryStatusCounts = $dbh->prepare($sqlStatusCounts);
    $queryStatusCounts->bindParam(':officer_email', $_SESSION['ologin'], PDO::PARAM_STR);
    $queryStatusCounts->execute();
    $statusCounts = $queryStatusCounts->fetchAll(PDO::FETCH_OBJ);

    // Initialize counts for each admission status
    $submittedCount = 0;
    $processingCount = 0;
    $deniedCount = 0;
    $admittedCount = 0;

    // Map the results to the respective counts
    foreach ($statusCounts as $status) {
        if ($status->admissionstatus == 'submitted') {
            $submittedCount = $status->statusCount;
        } elseif ($status->admissionstatus == 'processing') {
            $processingCount = $status->statusCount;
        } elseif ($status->admissionstatus == 'denied') {
            $deniedCount = $status->statusCount;
        } elseif ($status->admissionstatus == 'admitted') {
            $admittedCount = $status->statusCount;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Management System | Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/toastr/toastr.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>  

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-sm-6">
                                <h2 class="title">Dashboard</h2>
                            </div>
                        </div>
                    </div>

                    <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- Total Applications -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <a class="dashboard-stat bg-primary" href="#">
                                        <span class="number counter"><?php echo htmlentities($totalApplications); ?></span>
                                        <span class="name">Total Applications</span>
                                        <span class="bg-icon"><i class="fa fa-users"></i></span>
                                    </a>
                                </div>

                                <!-- Submitted Applications -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <a class="dashboard-stat bg-warning" href="#">
                                        <span class="number counter"><?php echo htmlentities($submittedCount); ?></span>
                                        <span class="name">Submitted Applications</span>
                                        <span class="bg-icon"><i class="fa fa-file-text"></i></span>
                                    </a>
                                </div>

                                <!-- Processing Applications -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-top:1%;">
                                    <a class="dashboard-stat bg-info" href="#">
                                        <span class="number counter"><?php echo htmlentities($processingCount); ?></span>
                                        <span class="name">Processing Applications</span>
                                        <span class="bg-icon"><i class="fa fa-spinner"></i></span>
                                    </a>
                                </div>

                                <!-- Denied Applications -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-top:1%;">
                                    <a class="dashboard-stat bg-danger" href="#">
                                        <span class="number counter"><?php echo htmlentities($deniedCount); ?></span>
                                        <span class="name">Denied Applications</span>
                                        <span class="bg-icon"><i class="fa fa-times"></i></span>
                                    </a>
                                </div>

                                <!-- Admitted Applications -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-top:1%;">
                                    <a class="dashboard-stat bg-success" href="#">
                                        <span class="number counter"><?php echo htmlentities($admittedCount); ?></span>
                                        <span class="name">Admitted Applications</span>
                                        <span class="bg-icon"><i class="fa fa-check"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/counterUp/jquery.counterup.min.js"></script>
    <script>
        $(function() {
            $('.counter').counterUp({
                delay: 10,
                time: 1000
            });
        });
    </script>
</body>
</html>

<?php } ?>
