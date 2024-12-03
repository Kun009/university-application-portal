<?php
session_start();
include('includes/config.php');

// Check if the officer is logged in
if(strlen($_SESSION['ologin']) == 0){   
    header('location:index.php');
} else {
    // Fetch denied applications from the database
    $sql = "SELECT * FROM tbl_applications WHERE admissionstatus = 'denied'";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Denied Applications</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
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
                            <div class="col-md-6">
                                <h2 class="title">Denied Applications</h2>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Email</th>
                                                            <th>Program</th>
                                                            <th>Degree Type</th>
                                                            <th>Admission Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $cnt=1;
                                                        if($query->rowCount() > 0) {
                                                            foreach($results as $result) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                                    <td><?php echo htmlentities($result->firstName); ?></td>
                                                                    <td><?php echo htmlentities($result->lastName); ?></td>
                                                                    <td><?php echo htmlentities($result->email); ?></td>
                                                                    <td><?php echo htmlentities($result->program); ?></td>
                                                                    <td><?php echo htmlentities($result->degreeType); ?></td>
                                                                    <td><?php echo htmlentities($result->admissionstatus); ?></td>
                                                                    <td>
                                                                        <!-- View Button -->
                                                                        <a href="view-deny.php?appid=<?php echo htmlentities($result->id); ?>" class="btn btn-info btn-sm">View</a>
                                                                    </td>
                                                                </tr>
                                                        <?php $cnt++; } 
                                                        } ?>
                                                    </tbody>
                                                </table>
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
