<?php 
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
} else {

    // Handle the delete request
    if (isset($_GET['delid'])) {
        $id = intval($_GET['delid']);
        $query = mysqli_query($con, "DELETE FROM tbl_officer WHERE officer_id='$id'");
        if ($query) {
            echo '<script>alert("Officer deleted successfully.")</script>';
            echo "<script>window.location.href='manage-officer.php'</script>";  // Refresh the page after deletion
        } else {
            echo '<script>alert("Something went wrong. Please try again.")</script>';
        }
    }

    // Fetch all officer records from tbl_officer
    $query = mysqli_query($con, "SELECT officer_id, university_name, first_name, last_name, email, phone_number, photo FROM tbl_officer");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manage Officers</title>
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
                    <div class="panel-heading">Manage Officers</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>University</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;  // Counter for row numbering
                                    while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo $row['university_name']; ?></td>
                                        <td><?php echo $row['first_name']; ?></td>
                                        <td><?php echo $row['last_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone_number']; ?></td>
                                        <td>
                                            <!-- Action Buttons -->
                                            <a href="view-officer.php?id=<?php echo $row['officer_id']; ?>" class="btn btn-info btn-sm">View</a>
                                            <a href="edit-officer.php?id=<?php echo $row['officer_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                            <!-- Delete button triggers deletion via GET parameter -->
                                            <a href="manage-officer.php?delid=<?php echo $row['officer_id']; ?>" class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure you want to delete this officer?');">Delete</a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $cnt++;
                                    } 
                                    ?>
                                </tbody>
                            </table>
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
