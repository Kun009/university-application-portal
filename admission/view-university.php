<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']==0)) {
    header('location:logout.php');
} else {

    if (isset($_GET['unid'])) {
        $unid = $_GET['unid'];
        $query = mysqli_query($con, "SELECT * FROM tbl_university WHERE unid='$unid'");
        $res = mysqli_fetch_array($query);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View University Details</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <?php include('leftbar.php'); ?>

    <nav>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
               <h4 class="page-header"> <?php echo strtoupper("welcome " . htmlentities($_SESSION['login'])); ?></h4>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        University Details
                    </div>
                    <div class="panel-body">
                        <?php if ($res) { ?>
                              <!-- Displaying the University Logo -->
                              <div class="row">
                                <div class="col-lg-12">
                                    <h5><strong>University Logo:</strong></h5>
                                    <p>
                                        <?php if (!empty($res['logoPath'])) { ?>
                                            <img src="uploads/university_logos/<?php echo htmlentities($res['logoPath']); ?>" alt="University Logo" style="max-width: 150px; max-height: 150px;">
                                        <?php } else { ?>
                                            <p>No logo available</p>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5><strong>University Name:</strong></h5>
                                    <p><?php echo htmlentities($res['universityName']); ?></p>
                                </div>
                                <div class="col-lg-6">
                                    <h5><strong>University Address:</strong></h5>
                                    <p><?php echo htmlentities($res['universityAddress']); ?></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <h5><strong>University Email:</strong></h5>
                                    <p><?php echo htmlentities($res['email']); ?></p>
                                </div>
                                <div class="col-lg-6">
                                    <h5><strong>University Phone:</strong></h5>
                                    <p><?php echo htmlentities($res['phoneNumber']); ?></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <h5><strong>Website:</strong></h5>
                                    <p><?php echo htmlentities($res['schoolWebsite']); ?></p>
                                </div>
                                <div class="col-lg-6">
                                    <h5><strong>Creation Date:</strong></h5>
                                    <p><?php echo htmlentities($res['creationDate']); ?></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <h5><strong>About University:</strong></h5>
                                    <p><?php echo htmlentities($res['aboutUniversity']); ?></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="manage-uni.php" class="btn btn-primary">Back</a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-danger">
                                <strong>No university found with the given ID.</strong>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

</body>
</html>

<?php } ?>
