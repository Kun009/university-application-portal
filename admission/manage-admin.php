<?php 
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {

    // Handle delete request
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $deleteQuery = mysqli_query($con, "DELETE FROM tbl_login WHERE id = '$id'");

        if ($deleteQuery) {
            echo '<script>alert("Admin deleted successfully")</script>';
        } else {
            echo '<script>alert("Something went wrong. Please try again")</script>';
        }
    }

    // Fetch all admins from tbl_login
    $admins = mysqli_query($con, "SELECT * FROM tbl_login");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manage Admins</title>
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
                        <div class="panel-heading">Manage Admins</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Login ID</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        while ($row = mysqli_fetch_array($admins)) { 
                                        ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['FullName']; ?></td>
                                                <td><?php echo $row['phone_number']; ?></td>
                                                <td><?php echo $row['AdminEmail']; ?></td>
                                                <td><?php echo $row['loginid']; ?></td>
                                                <td>
                                                    <a href="edit-admin.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this admin?');" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                            </tr>
                                        <?php 
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <a href="add-admin.php" class="btn btn-primary">Add Admin</a>
                            </div>
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

<?php } ?>
