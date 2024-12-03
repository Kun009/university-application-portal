<?php 
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
} else {

    // Fetch all universities from tbl_university
    $universities = mysqli_query($con, "SELECT unid, universityName FROM tbl_university");

    if(isset($_POST['submit'])) {
        $unid = $_POST['university'];  // University ID from dropdown
        $firstName = $_POST['first-name'];
        $lastName = $_POST['last-name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phoneNumber = $_POST['phone-number'];
        $officerRole = $_POST['officer-role'];
        $photo = $_FILES["photo"]["name"];
        $uploadDir = "uploads/";  // Directory where photos will be saved
        move_uploaded_file($_FILES["photo"]["tmp_name"], $uploadDir.$photo);
    
        // Fetch university name using the unid
        $result = mysqli_query($con, "SELECT universityName FROM tbl_university WHERE unid = '$unid'");
        $row = mysqli_fetch_assoc($result);
        $university = $row['universityName'];  // Get the university name
        
        // Insert officer details into tbl_officer
        $query = mysqli_query($con, "INSERT INTO tbl_officer (unid, university_name, first_name, last_name, email, phone_number, position, photo, password) 
        VALUES ('$unid', '$university', '$firstName', '$lastName', '$email', '$phoneNumber', '$officerRole', '$photo', '$password')");
    
        if($query) {
            echo '<script>alert("Officer added successfully")</script>';
            echo "<script>window.location.href='manage-officer.php'</script>";
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
    <title>Add Officer</title>
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
<form method="post" enctype="multipart/form-data" action="">

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
                        <div class="panel-heading">Add Officer</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <!-- University Dropdown -->
                                    <div class="form-group">
                                        <label for="university">University<span style="font-size:11px;color:red">*</span></label>
                                        <select class="form-control" name="university" id="university" required>
                                            <option value="">Select University</option>
                                            <?php 
                                            while ($row = mysqli_fetch_array($universities)) {
                                                echo '<option value="'.$row['unid'].'">'.$row['universityName'].'</option>';
                                            } 
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Officer Details -->
                                    <div class="form-group">
                                        <label for="first-name">First Name<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="first-name" id="first-name" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="last-name">Last Name<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="last-name" id="last-name" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="email" id="email" required="required">
                                    </div>

                                    <div class="form-group">
    <label for="phone-number">Phone Number<span style="font-size:11px;color:red">*</span></label>
    <input class="form-control" name="phone-number" id="phone-number" required="required"
           type="tel" pattern="^\+?[0-9]{10,13}$" 
           title="Please enter a valid phone number (10-13 digits, optionally starting with +).">
</div>
<div class="form-group">
                                                        <label for="password">Password</label>
                                                        <input type="password" name="password" class="form-control" required>
                                                    </div>

                                    <div class="form-group">
                                        <label for="officer-role">Officer Role<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="officer-role" id="officer-role" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="photo">Upload Photo<span style="font-size:11px;color:red">*</span></label>
                                        <input type="file" name="photo" id="photo" required="required">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        <a href="manage-officers.php" class="btn btn-default">Cancel</a>
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
