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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Officer Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
</head>
<body>
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>  <!-- Include left sidebar -->

                <div class="main-page">
                    <div class="container">
                        <h1 class="mt-4">Profile Information</h1>
                        
                        <div class="profile-info">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="2" class="text-center">
                                    <div class="profile-info">
            <img src="../admission/uploads/<?php echo htmlentities($officer['photo']); ?>" alt="Profile Photo" class="profile-img" style="width: 150px; height: 150px; border-radius: 50%;">
            </td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo htmlentities($officer['first_name'] . ' ' . $officer['last_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo htmlentities($officer['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td><?php echo htmlentities($officer['phone_number']); ?></td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td><?php echo htmlentities($officer['position']); ?></td>
                                </tr>
                                <tr>
                                    <th>Joining Date</th>
                                    <td><?php echo htmlentities($officer['joining_date']); ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo htmlentities($officer['address']); ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?php echo htmlentities($officer['status']); ?></td>
                                </tr>
                                <tr>
                                    <th>University Name</th>
                                    <td><?php echo htmlentities($officer['university_name']); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a> <!-- Edit button -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>

<?php
$query->closeCursor(); // Close the cursor to free the connection
$dbh = null; // Close the database connection
?>
