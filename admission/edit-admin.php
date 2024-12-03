<?php 
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {

    // Fetch admin details based on the ID passed in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $result = mysqli_query($con, "SELECT * FROM tbl_login WHERE id = '$id'");
        $admin = mysqli_fetch_assoc($result);

        if (!$admin) {
            echo '<script>alert("Admin not found.")</script>';
            echo "<script>window.location.href='manage-admin.php'</script>";
        }
    }

    // Handle form submission to update admin details
    if (isset($_POST['update'])) {
        $fullName = $_POST['full-name'];
        $adminEmail = $_POST['admin-email'];
        $loginId = $_POST['login-id'];

        $updateQuery = mysqli_query($con, "UPDATE tbl_login SET FullName='$fullName', AdminEmail='$adminEmail', loginid='$loginId' WHERE id='$id'");

        if ($updateQuery) {
            echo '<script>alert("Admin updated successfully")</script>';
            echo "<script>window.location.href='manage-admin.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again")</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Admin</title>
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
<form method="post" action="">
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
                        <div class="panel-heading">Edit Admin</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <!-- Admin Details -->
                                    <div class="form-group">
                                        <label for="full-name">Full Name<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="full-name" id="full-name" value="<?php echo $admin['FullName']; ?>" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="admin-email">Email<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="admin-email" id="admin-email" value="<?php echo $admin['AdminEmail']; ?>" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="login-id">Login ID<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="login-id" id="login-id" value="<?php echo $admin['loginid']; ?>" required="required">
                                    </div>

                                    <!-- Update Button -->
                                    <div class="form-group">
                                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                                        <a href="manage-admin.php" class="btn btn-default">Cancel</a>
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

</body>
</html>

<?php } ?>
