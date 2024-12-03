<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit();
} else {
    // Get the officer ID from the URL parameter
    if (isset($_GET['id'])) {
        $officer_id = intval($_GET['id']);
        
        // Fetch the officer's details from the database
        $query = mysqli_query($con, "SELECT unid, university_name, first_name, last_name, email, phone_number, position, photo FROM tbl_officer WHERE officer_id='$officer_id'");
        
        if (!$query) {
            die('Query Error: ' . mysqli_error($con)); // Error handling
        }

        $officer = mysqli_fetch_array($query);
        
        if (!$officer) {
            echo '<script>alert("Officer not found.")</script>';
            echo "<script>window.location.href='manage-officer.php'</script>";
            exit();
        }
    } else {
        echo '<script>alert("Invalid request.")</script>';
        echo "<script>window.location.href='manage-officer.php'</script>";
        exit();
    }
    
    // Handle form submission to update officer details
    if (isset($_POST['update'])) {
        $unid = mysqli_real_escape_string($con, $_POST['unid']);
        $university_name = mysqli_real_escape_string($con, $_POST['university_name']);
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
        $position = mysqli_real_escape_string($con, $_POST['position']);
        
        // Handle photo upload
        $photo = $_FILES['photo']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);
        
        // Check if a new photo is being uploaded
        if (!empty($photo)) {
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
        } else {
            $photo = $officer['photo']; // Keep existing photo if none is uploaded
        }

        // Update officer details in the database
        $update_query = "UPDATE tbl_officer SET university_name='$university_name', first_name='$first_name', last_name='$last_name', email='$email', phone_number='$phone_number', position='$position', photo='$photo' WHERE officer_id='$officer_id'";
        
        if (mysqli_query($con, $update_query)) {
            echo '<script>alert("Officer details updated successfully.")</script>';
            echo "<script>window.location.href='manage-officer.php'</script>";
        } else {
            echo '<script>alert("Error updating officer details: ' . mysqli_error($con) . '")</script>';
        }
    }
    
    // Fetch the list of universities for the dropdown
    $university_query = mysqli_query($con, "SELECT unid, universityName FROM tbl_university");
    if (!$university_query) {
        die('University Query Error: ' . mysqli_error($con)); // Error handling
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Officer Details</title>
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
                    <div class="panel-heading">Edit Officer Details</div>
                    <div class="panel-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>University Name</label>
                                <select name="university_name" class="form-control" required>
                                    <option value="">Select University</option>
                                    <?php while ($university = mysqli_fetch_array($university_query)) { ?>
                                        <option value="<?php echo htmlentities($university['universityName']); ?>" <?php echo ($officer['university_name'] == $university['universityName']) ? 'selected' : ''; ?>>
                                            <?php echo htmlentities($university['universityName']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlentities($officer['first_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlentities($officer['last_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlentities($officer['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" value="<?php echo htmlentities($officer['phone_number']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Position (Officer Role)</label>
                                <input type="text" class="form-control" name="position" value="<?php echo htmlentities($officer['position']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Photo</label>
                                <input type="file" name="photo" class="form-control">
                                <?php if ($officer['photo']) { ?>
                                    <img src="uploads/<?php echo htmlentities($officer['photo']); ?>" alt="Officer Photo" width="100" height="100">
                                <?php } else { ?>
                                    No photo available
                                <?php } ?>
                            </div>
                            <input type="hidden" name="unid" value="<?php echo htmlentities($officer['unid']); ?>">
                            <button type="submit" name="update" class="btn btn-primary">Update Officer</button>
                            <a href="manage-officer.php" class="btn btn-default">Cancel</a>
                        </form>
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

<?php 
mysqli_close($con); // Close the database connection
?>
