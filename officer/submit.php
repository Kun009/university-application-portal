<?php 
session_start();
error_reporting(0);
include('includes/config.php');

// Check if the user is logged in
if(strlen($_SESSION['ologin']) == "") {   
    header("Location: index.php"); 
} else {
    // Retrieve the email of the logged-in officer from the session
    $email = $_SESSION['ologin']; // Assuming email is stored in the session upon login
    
    // Fetch the unid of the officer
    $sql = "SELECT unid FROM tbl_officer WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $officer = $query->fetch(PDO::FETCH_OBJ);
    
    // Check if the officer was found and retrieve the unid
    if ($officer) {
        $unid = $officer->unid; // Store the retrieved unid
    } else {
        // Handle case where officer is not found
        die("Officer not found.");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Submitted Applications</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
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

        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php');?> 

        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php');?>  

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Manage Submitted Applications</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li> Applications</li>
                                    <li class="active">Manage Submitted Applications</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <h5>View Submitted Applications</h5>
                                            </div>
                                        </div>

                                        <div class="panel-body p-20">
                                            <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Program</th>
                                                        <th>Degree Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            
                                                <tbody>
                                                    <?php 
                                                    // Fetching data from the tbl_applications where universityId = :unid and admissionstatus = 'submitted'
                                                    $sql = "SELECT * FROM tbl_applications WHERE universityId = :unid AND admissionstatus = 'submitted'";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':unid', $unid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt = 1;

                                                    if($query->rowCount() > 0) {
                                                        foreach($results as $result) {   
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt);?></td>
                                                        <td><?php echo htmlentities($result->firstName);?></td>
                                                        <td><?php echo htmlentities($result->lastName);?></td>
                                                        <td><?php echo htmlentities($result->program);?></td>
                                                        <td><?php echo htmlentities($result->degreeType);?></td>
                                                        <td>
                                                            <a href="view-submit.php?id=<?php echo htmlentities($result->id);?>" class="btn btn-info btn-xs"> View </a>
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

<?php } ?>
