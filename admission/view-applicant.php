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
    <title>View Applicant</title>
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
                        <div class="panel-heading">
                            Applicant Details
                        </div>
                        <div class="panel-body">
                        <table class="table table-bordered">
                        <tr>
    <th>Passport Photograph</th>
    <td>
        <?php
        // Ensure the passport field is not empty and the file exists in the specified path
        $passportPath = 'uploads/' . htmlentities($applicant['passport_photo']);
        if (!empty($applicant['passport_photo']) && file_exists($passportPath)) {
            // Display the passport photograph
            echo '<img src="' . $passportPath . '" alt="Passport Photograph" width="150" height="150">';
        } else {
            // Display a placeholder image or message if the passport is missing
            echo '<img src="uploads/default-placeholder.png" alt="No Passport Available" width="150" height="150">';
        }
        ?>
    </td>
</tr>
    <tr>
        <th>First Name</th>
        <td><?php echo htmlentities(ucwords($applicant['first_name'])); ?></td>
    </tr>
    <tr>
        <th>Middle Name</th>
        <td><?php echo htmlentities(ucwords($applicant['middle_name'])); ?></td>
    </tr>
    <tr>
        <th>Last Name</th>
        <td><?php echo htmlentities(ucwords($applicant['last_name'])); ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?php echo htmlentities($applicant['email']); ?></td>
    </tr>
    <tr>
        <th>Age</th>
        <td><?php echo htmlentities($applicant['age']); ?></td>
    </tr>
    <tr>
        <th>Mobile No</th>
        <td><?php echo htmlentities($applicant['mobile_no']); ?></td>
    </tr>
    <tr>
        <th>Address</th>
        <td><?php echo htmlentities($applicant['address']); ?></td>
    </tr>
    <tr>
        <th>Country</th>
        <td><?php echo htmlentities($applicant['country']); ?></td>
    </tr>
    <tr>
        <th>Date of Birth</th>
        <td><?php echo htmlentities($applicant['date_of_birth']); ?></td>
    </tr>
    <tr>
        <th>Gender</th>
        <td><?php echo htmlentities($applicant['gender']); ?></td>
    </tr>
    <tr>
        <th>Qualification</th>
        <td><?php echo htmlentities($applicant['qualification']); ?></td>
    </tr>
    <tr>
        <th>Marital Status</th>
        <td><?php echo htmlentities($applicant['marital_status']); ?></td>
    </tr>
    <tr>
    

    </tr>
</table>

                            <a href="manage-applicants.php" class="btn btn-default">Back to Manage Applicants</a>
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
