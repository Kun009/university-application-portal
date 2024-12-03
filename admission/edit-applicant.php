<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid'] == 0)) {
    header('location:logout.php');
} else {
    // Check if 'aid' is set in the URL
    if (isset($_GET['aid'])) {
        $applicantId = $_GET['aid'];

        // Fetch applicant details from the database
        $query = mysqli_query($con, "SELECT * FROM tbl_applicant WHERE id='$applicantId'");
        $applicant = mysqli_fetch_array($query);

        // Check if the applicant exists
        if (!$applicant) {
            echo "<script>alert('No applicant found with the given ID.');</script>";
            echo "<script>window.location.href='manage-applicants.php';</script>";
            exit;
        }

        // Handle form submission
        if (isset($_POST['update'])) {
            $firstName = $_POST['first_name'];
			$middleName = $_POST['middle_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $age = $_POST['age'];
            $mobile = $_POST['mobile_no'];
            $address = $_POST['address'];
            $country = $_POST['country'];
            $dob = $_POST['date_of_birth'];
            $gender = $_POST['gender'];
            $qualification = $_POST['qualification'];
            $status = $_POST['status'];

            // Update passport photo if a new one is uploaded
            $passport = $_FILES['passport']['name'];
            $passportTmp = $_FILES['passport']['tmp_name'];

            // If passport is uploaded, move it to the 'uploads' directory
            if (!empty($passport)) {
                $passportPath = 'uploads/' . $passport;
                move_uploaded_file($passportTmp, $passportPath);
            } else {
                $passport = $applicant['passport']; // If no new passport is uploaded, keep the old one
            }

            // Update the applicant details in the database
            $updateQuery = mysqli_query($con, "UPDATE tbl_applicant SET 
                first_name='$firstName',
				  middle_name='$middleName',
                last_name='$lastName',
                email='$email',
                age='$age',
                mobile_no='$mobile',
                address='$address',
                country='$country',
                date_of_birth='$dob',
                gender='$gender',
                qualification='$qualification',
                marital_status='$status',
                passport_photo='$passport'
                WHERE id='$applicantId'");

            if ($updateQuery) {
                echo "<script>alert('Applicant details updated successfully.');</script>";
                echo "<script>window.location.href='manage-applicants.php';</script>";
            } else {
                echo "<script>alert('An error occurred while updating. Please try again.');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid applicant ID.');</script>";
        echo "<script>window.location.href='manage-applicants.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Applicant</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
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
                        <div class="panel-heading">Edit Applicant Details</div>
                        <div class="panel-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="<?php echo htmlentities($applicant['first_name']); ?>" required>
                                </div>
								<div class="form-group">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" value="<?php echo htmlentities($applicant['middle_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="<?php echo htmlentities($applicant['last_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlentities($applicant['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Age</label>
                                    <input type="text" class="form-control" name="age" value="<?php echo htmlentities($applicant['age']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Mobile No</label>
                                    <input type="text" class="form-control" name="mobile_no" value="<?php echo htmlentities($applicant['mobile_no']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" name="address" required><?php echo htmlentities($applicant['address']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="country" value="<?php echo htmlentities($applicant['country']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" value="<?php echo htmlentities($applicant['date_of_birth']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="Male" <?php if ($applicant['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                        <option value="Female" <?php if ($applicant['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Qualification</label>
                                    <input type="text" class="form-control" name="qualification" value="<?php echo htmlentities($applicant['qualification']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Marital Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="Single" <?php if ($applicant['marital_status'] == 'Single') echo 'selected'; ?>>Single</option>
                                        <option value="Married" <?php if ($applicant['marital_status'] == 'Married') echo 'selected'; ?>>Married</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Passport Photograph</label><br>
                                    <img src="uploads/<?php echo htmlentities($applicant['passport_photo']); ?>" alt="Passport Photograph" width="150" height="150"><br><br>
                                    <input type="file" name="passport" class="form-control">
                                    <small>If you don't upload a new passport, the existing one will be kept.</small>
                                </div>
                                <button type="submit" name="update" class="btn btn-primary">Update Applicant</button>
                                <a href="manage-applicants.php" class="btn btn-default">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
