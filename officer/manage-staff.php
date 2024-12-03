<?php
session_start();
include('includes/config.php');

// Check if the officer is logged in
if(strlen($_SESSION['ologin']) == 0){   
    header('location:index.php');
} else {
    // Fetch officer details from session (unid)
    $email = $_SESSION['ologin'];
    $sql = "SELECT unid FROM tbl_officer WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $officer = $query->fetch(PDO::FETCH_OBJ);
    
    // Fetch staff members for the logged-in officer's university
    $sql = "SELECT first_name, last_name, email, position, phone_number FROM tbl_officer WHERE unid = :unid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':unid', $officer->unid, PDO::PARAM_INT);
    $query->execute();
    $staff_list = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Manage Staff</h2>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Email</th>
                                                            <th>Role</th>
                                                            <th>Phone Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($query->rowCount() > 0) {
                                                            foreach ($staff_list as $staff) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($staff->first_name); ?></td>
                                                                    <td><?php echo htmlentities($staff->last_name); ?></td>
                                                                    <td><?php echo htmlentities($staff->email); ?></td>
                                                                    <td><?php echo htmlentities($staff->position); ?></td>
                                                                    <td><?php echo htmlentities($staff->phone_number); ?></td>
                                                                </tr>
                                                            <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="5">No staff found.</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

<?php } ?>
