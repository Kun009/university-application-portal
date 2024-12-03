<?php
session_start();
error_reporting(0);
include('includes/config.php'); // Include your database connection

// Check if officer is logged in
if (strlen($_SESSION['ologin']) == "") {
    header("Location: index.php"); 
    exit();
} else {
    // Fetch officer's details based on logged-in officer's email
    $officer_email = $_SESSION['ologin'];
    
    $sql = "SELECT * FROM tbl_officer WHERE email = :officer_email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':officer_email', $officer_email, PDO::PARAM_STR);
    $query->execute();
    
    // Check if officer exists
    if ($query->rowCount() > 0) {
        $officer = $query->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "No officer found.";
        exit();
    }

    // Handle form submission for profile update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_number = $_POST['phone_number'];
        $position = $_POST['position'];
        $joining_date = $_POST['joining_date'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $university_name = $_POST['university_name'];

        // Handle file upload for photo
        $photo = $_FILES['photo']['name'];
        $target_dir = "../admission/uploads/";
        $target_file = $target_dir . basename($photo);
        $uploadOk = 1;

        // Check if file is an image
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES['photo']['size'] > 2000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Attempt to upload file if no errors
        if ($uploadOk === 1 && move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // Update officer details in the database
            $sql_update = "UPDATE tbl_officer SET 
                first_name = :first_name, 
                last_name = :last_name, 
                phone_number = :phone_number, 
                position = :position, 
                joining_date = :joining_date, 
                address = :address, 
                status = :status, 
                university_name = :university_name, 
                photo = :photo 
                WHERE email = :officer_email";

            $query_update = $dbh->prepare($sql_update);
            $query_update->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $query_update->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $query_update->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
            $query_update->bindParam(':position', $position, PDO::PARAM_STR);
            $query_update->bindParam(':joining_date', $joining_date, PDO::PARAM_STR);
            $query_update->bindParam(':address', $address, PDO::PARAM_STR);
            $query_update->bindParam(':status', $status, PDO::PARAM_STR);
            $query_update->bindParam(':university_name', $university_name, PDO::PARAM_STR);
            $query_update->bindParam(':photo', $photo, PDO::PARAM_STR);
            $query_update->bindParam(':officer_email', $officer_email, PDO::PARAM_STR);
            
            if ($query_update->execute()) {
                echo "Profile updated successfully.";
                header("Location: profile.php");
                exit();
            } else {
                echo "Error updating profile.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <style>
        body {
            display: flex;
            flex-direction: row;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?> 

    <div class="content">
        <h1>Edit Profile</h1>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlentities($officer['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlentities($officer['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" class="form-control" value="<?php echo htmlentities($officer['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="position">Position:</label>
                <input type="text" name="position" class="form-control" value="<?php echo htmlentities($officer['position']); ?>" required>
            </div>
            <div class="form-group">
                <label for="joining_date">Joining Date:</label>
                <input type="date" name="joining_date" class="form-control" value="<?php echo htmlentities($officer['joining_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlentities($officer['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" class="form-control" required>
                    <option value="active" <?php if ($officer['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($officer['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="university_name">University Name:</label>
                <input type="text" name="university_name" class="form-control" value="<?php echo htmlentities($officer['university_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="photo">Profile Photo:</label>
                <input type="file" name="photo" class="form-control">
                <small>Upload a new photo if you wish to change it.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>

<?php
$query->closeCursor(); // Close the cursor to free the connection
$dbh = null; // Close the database connection
?>
