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

    if (isset($_POST['submit'])) {
        // Getting updated data from the form
        $universityName = $_POST['universityName'];
        $universityAddress = $_POST['universityAddress'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $schoolWebsite = $_POST['schoolWebsite'];
        $aboutUniversity = $_POST['aboutUniversity'];
     // Handling file upload for the logo
    $logoFile = $_FILES['universityLogo']['name'];
    $logoTmpName = $_FILES['universityLogo']['tmp_name'];
    $logoFolder = "uploads/univeristy_logos";

    if (!empty($logoFile)) {
        // Move the uploaded file to the server directory
        $logoFilePath = $logoFolder . basename($logoFile);
        if (move_uploaded_file($logoTmpName, $logoFilePath)) {
            $logoFile = basename($logoFile); // Get the file name to store in the DB
        } else {
            echo '<script>alert("Error uploading logo.")</script>';
        }
    } else {
        $logoFile = $res['logoPath']; // Keep the old logo if no new file is uploaded
    }

    // Update query with the new values
    $updateQuery = "UPDATE tbl_university SET universityName='$universityName', universityAddress='$universityAddress', email='$email', phoneNumber='$phoneNumber', schoolWebsite='$schoolWebsite', aboutUniversity='$aboutUniversity', logoPath='$logoFile' WHERE unid='$unid'";

    if (mysqli_query($con, $updateQuery)) {
        echo '<script>alert("University details updated successfully!")</script>';
        echo '<script>window.location.href="manage-uni.php"</script>';
    } else {
        echo '<script>alert("Error updating university details. Please try again.")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit University Details</title>
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
                        Edit University Details
                    </div>
                    <div class="panel-body">
                    <form method="post" enctype="multipart/form-data">
                    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>University Logo</label>
                <input type="file" class="form-control" name="universityLogo">
            </div>
        </div>
        <div class="col-lg-6">
            <?php if (!empty($res['universityLogo'])) { ?>
                <label>Current Logo:</label><br>
                <img src="uploads/<?php echo $res['universityLogo']; ?>" width="100" height="100">
            <?php } ?>
        </div>
    </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>University Name</label>
                                        <input type="text" class="form-control" name="universityName" value="<?php echo htmlentities($res['universityName']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>University Address</label>
                                        <input type="text" class="form-control" name="universityAddress" value="<?php echo htmlentities($res['universityAddress']); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>University Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlentities($res['email']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>University Phone Number</label>
                                        <input type="text" class="form-control" name="phoneNumber" value="<?php echo htmlentities($res['phoneNumber']); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="url" class="form-control" name="schoolWebsite" value="<?php echo htmlentities($res['schoolWebsite']); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>About University</label>
                                        <textarea class="form-control" name="aboutUniversity" rows="3"><?php echo htmlentities($res['aboutUniversity']); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                <a href="manage-uni.php" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
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
