<?php 
session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        // Fetch form data
        $firstName = mysqli_real_escape_string($con, $_POST['first-name']);
        $lastName = mysqli_real_escape_string($con, $_POST['last-name']);
        $middleName = mysqli_real_escape_string($con, $_POST['middle-name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $age = mysqli_real_escape_string($con, $_POST['age']);
        $mobileNo = mysqli_real_escape_string($con, $_POST['mobile-no']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $country = mysqli_real_escape_string($con, $_POST['country']);
		$dateOfBirth = mysqli_real_escape_string($con, $_POST['date_of_birth']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $maritalStatus = mysqli_real_escape_string($con, $_POST['marital_status']);
        $qualification = mysqli_real_escape_string($con,$_POST['qualification']);
        
      // Handle file upload for passport photograph
$passport = $_FILES['passport']['name'];
$extension = substr($passport, strlen($passport) - 4, strlen($passport));
$allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

if (!in_array($extension, $allowed_extensions)) {
    echo "<script>alert('Invalid file format. Only jpg, jpeg, png, gif allowed');</script>";
} else {
    $passportNew = md5($passport) . time() . $extension;
    $targetFilePath = "uploads/" . $passportNew;

    // Check for upload errors
    if ($_FILES['passport']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Error uploading file. Code: " . $_FILES['passport']['error'] . "');</script>";
    } elseif (move_uploaded_file($_FILES["passport"]["tmp_name"], $targetFilePath)) {
        // Insert into database
        $query = mysqli_query($con, "INSERT INTO tbl_applicant (first_name, middle_name, last_name, email, age, mobile_no, address, country, date_of_birth, gender, marital_status, qualification, passport_photo, password) VALUES ('$firstName', '$middleName', '$lastName', '$email', '$age', '$mobileNo', '$address', '$country', '$dateOfBirth', '$gender', '$maritalStatus', '$qualification', '$passportNew', '$password')");
        if ($query) {
            echo '<script>alert("Applicant added successfully")</script>';
            echo "<script>window.location.href='manage-applicants.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again")</script>';
        }
    } else {
        echo "<script>alert('File could not be uploaded. Please try again.');</script>";
    }
}

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <div id="wrapper">
            <!-- Navigation -->
            <?php include('leftbar.php'); ?>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="page-header"> <?php echo strtoupper("Welcome " . htmlentities($_SESSION['login']));?></h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Add Applicant</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" name="first-name" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last-name" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input class="form-control" name="middle-name">
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <div class="form-group">
                                                        <label for="password">Password</label>
                                                        <input type="password" name="password" class="form-control" required>
                                                    </div>

                                        <div class="form-group">
                                            <label>Age</label>
                                            <input type="number" class="form-control" name="age" required>
                                        </div>

                                        
                                        <div class="form-group">
    <label for="mobile-no">Phone Number<span style="font-size:11px;color:red">*</span></label>
    <input class="form-control" name="mobile-no" id="mobile-no" required="required"
           type="tel" pattern="^\+?[0-9]{10,13}$" 
           title="Please enter a valid phone number (10-13 digits, optionally starting with +).">
</div>

                                        <div class="form-group">
                                            <label>Passport Photograph</label>
                                            <input type="file" class="form-control" name="passport" accept=".jpg,.jpeg,.png,.gif" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Address</label>
                                            <input class="form-control" name="address" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Country of Residence</label>
                                            <input class="form-control" name="country" required>
                                        </div>
										<div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" required>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Marital Status</label>
                                    <select class="form-control" name="marital_status" required>
                                        <option value="" disabled selected>Select Marital Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Qualification</label>
                                    <input type="text" class="form-control" name="qualification" required>
                                </div>
                              
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" name="submit" value="Add Applicant">
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

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js" type="text/javascript"></script>

    <script>
        function coursefullAvail() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "course_availability.php",
                data: 'cfull1=' + $("#cfull").val(),
                type: "POST",
                success: function(data) {
                    $("#course-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }

        function courseAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "course_availability.php",
                data: 'cshort1=' + $("#cshort").val(),
                type: "POST",
                success: function(data) {
                    $("#course-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }
    </script>
</body>
</html>
