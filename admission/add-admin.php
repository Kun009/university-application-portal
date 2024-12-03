<?php 
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {
        $fullName = $_POST['full-name'];
        $email = $_POST['admin-email'];
        $phoneNumber = $_POST['phone-number'];
        $password = $_POST['password']; // Consider hashing the password before storing it
        $loginId = $_POST['login-id'];
        
        // Insert admin details into tbl_login
        $query = mysqli_query($con, "INSERT INTO tbl_login (FullName, AdminEmail, password, phone_number, loginid) 
        VALUES ('$fullName', '$email', '$password', '$phoneNumber', '$loginId')");
    
        if ($query) {
            echo '<script>alert("Admin added successfully")</script>';
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
    <title>Add Admin</title>
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
                        <div class="panel-heading">Add Admin</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <!-- Admin Details -->
                                    <div class="form-group">
                                        <label for="full-name">Full Name<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="full-name" id="full-name" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="admin-email">Email<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="admin-email" id="admin-email" required="required">
                                    </div>
                                    <div class="form-group">
                      <label for="phone-number">Phone Number<span style="font-size:11px;color:red">*</span></label>
                      <input class="form-control" name="phone-number" id="phone-number" required="required"
                      type="tel" pattern="^\+?[0-9]{10,13}$" 
                       title="Please enter a valid phone number (10-13 digits, optionally starting with +).">
                    </div>

                                    <div class="form-group">
                                        <label for="password">Password<span style="font-size:11px;color:red">*</span></label>
                                        <input type="password" class="form-control" name="password" id="password" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="login-id">Login ID<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="login-id" id="login-id" required="required">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
