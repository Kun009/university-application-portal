<?php
session_start();
include('includes/config.php');

// Check if the user is logged in
if(strlen($_SESSION['ologin']) == 0){   
    header('location:index.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List</title>
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
                                <h2 class="title">Admin List</h2>
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
                                                        <th>Staff ID</th>
                                                            <th>Full Name</th>
                                                            <th>Email</th>
                                                            <th>Phone Number </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        // Fetch all admin details from tbl_login
                                                        $sql = "SELECT id, FullName, AdminEmail, phone_number FROM tbl_login";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $admins = $query->fetchAll(PDO::FETCH_OBJ);

                                                        if($query->rowCount() > 0) {
                                                            foreach ($admins as $admin) { ?>
                                                                <tr>
                                                                <td><?php echo htmlentities($admin->id); ?></td>
                                                                    <td><?php echo htmlentities($admin->FullName); ?></td>
                                                                    <td><?php echo htmlentities($admin->AdminEmail); ?></td>
                                                                    <td><?php echo htmlentities($admin->phone_number ); ?></td>
                                                                </tr>
                                                            <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="2">No admins found.</td>
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
