<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {

    // Get the officer ID from the URL parameter
    if (isset($_GET['id'])) { // Change 'officer_id' to 'id'
        $officer_id = intval($_GET['id']);
        
        // Fetch the officer's details from the database
        $query = mysqli_query($con, "SELECT unid, university_name, first_name, last_name, email, phone_number, position, photo FROM tbl_officer WHERE officer_id='$officer_id'");
        $officer = mysqli_fetch_array($query);
        
        if (!$officer) {
            echo '<script>alert("Officer not found.")</script>';
            echo "<script>window.location.href='manage-officer.php'</script>";
        }
    } else {
        echo '<script>alert("Invalid request.")</script>';
        echo "<script>window.location.href='manage-officer.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Officer Details</title>
    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
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
                <div class="panel-heading">Officer Details</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>University Name</td>
                                        <td><?php echo htmlentities($officer['university_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>First Name</td>
                                        <td><?php echo htmlentities($officer['first_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Last Name</td>
                                        <td><?php echo htmlentities($officer['last_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td><?php echo htmlentities($officer['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number</td>
                                        <td><?php echo htmlentities($officer['phone_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Position (Officer Role)</td>
                                        <td><?php echo htmlentities($officer['position']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Photo</td>
                                        <td>
                                            <?php if ($officer['photo']) { ?>
                                                <img src="uploads/<?php echo htmlentities($officer['photo']); ?>" alt="Officer Photo" width="200" height="200">
                                            <?php } else { ?>
                                                No photo available
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                </div>

                                <!-- Back Button -->
                                <a href="manage-officer.php" class="btn btn-default">Back to List</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<!-- Bootstrap and jQuery scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
<script src="dist/js/sb-admin-2.js"></script>

</body>
</html>

<?php } ?>
