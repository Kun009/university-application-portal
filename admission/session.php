<?php 
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {

    // Fetch all sessions from the database
    $sessions = mysqli_query($con, "SELECT * FROM session");

    // Handle adding a new session
    if (isset($_POST['add'])) {
        $newSession = $_POST['session'];

        $addQuery = mysqli_query($con, "INSERT INTO session (session) VALUES ('$newSession')");

        if ($addQuery) {
            echo '<script>alert("Session added successfully")</script>';
            echo "<script>window.location.href='session.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again")</script>';
        }
    }

    // Handle deleting a session
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $deleteQuery = mysqli_query($con, "DELETE FROM session WHERE sid = '$id'");

        if ($deleteQuery) {
            echo '<script>alert("Session deleted successfully")</script>';
            echo "<script>window.location.href='session.php'</script>";
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
    <title>Manage Sessions</title>
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
<form method="post" action="">
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
                        <div class="panel-heading">Manage Admission Sessions</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <!-- Input for new session -->
                                    <div class="form-group">
                                        <label for="session">New Admission Session<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="session" id="session" required="required" placeholder="e.g. Fall 2025, Spring 2025">
                                    </div>

                                    <!-- Add Button -->
                                    <div class="form-group">
                                        <button type="submit" name="add" class="btn btn-primary">Add Session</button>
                                    </div>

                                    <!-- Existing Sessions Table -->
                                    <h4>Existing Sessions</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                
                                                <th>Session</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_array($sessions)) { ?>
                                                <tr>
                                                    
                                                    <td><?php echo $row['session']; ?></td>
                                                    <td>
                                                        <a href="session.php?delete=<?php echo $row['sid']; ?>" onclick="return confirm('Are you sure you want to delete this session?');" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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
