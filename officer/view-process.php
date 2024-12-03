<?php 
session_start();
error_reporting(0);
include('includes/config.php');

// Check if the user is logged in
if(strlen($_SESSION['ologin']) == "") {   
    header("Location: index.php"); 
} else {
    // Retrieve the email of the logged-in officer from the session
    $email = $_SESSION['ologin']; 
    
    // Fetch the unid of the officer
    $sql = "SELECT unid FROM tbl_officer WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $officer = $query->fetch(PDO::FETCH_OBJ);
    
    // Check if the officer was found and retrieve the unid
    if ($officer) {
        $unid = $officer->unid; 
    } else {
        die("Officer not found.");
    }

    // Check if the application ID is provided
    if(isset($_GET['id'])) {
        $appId = intval($_GET['id']);
        
        // Fetch application details based on ID
        $sql = "SELECT * FROM tbl_applications WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $appId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if (!$result) {
            die("Application not found.");
        }
        
        // Handle form submission for updating admission status
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $uploadDir = '../admission/uploads/';
            $uploadFile = $uploadDir . basename($_FILES['admissionLetter']['name']);
            $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

            // Check file type and upload
            if (in_array($fileType, ['pdf', 'doc', 'docx'])) {
                if (move_uploaded_file($_FILES['admissionLetter']['tmp_name'], $uploadFile)) {
                    // Update admission status and upload path in database
                    $sql = "UPDATE tbl_applications SET admissionstatus = :status, admissionLetter = :admissionLetter WHERE id = :id";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':status', $status, PDO::PARAM_STR);
                    $query->bindParam(':admissionLetter', $uploadFile, PDO::PARAM_STR);
                    $query->bindParam(':id', $appId, PDO::PARAM_INT);
                    $query->execute();
                    $message = "Application status updated successfully.";
                    header("Location: submit.php");
                    exit; 
                } else {
                    $error = "Failed to upload admission letter.";
                }
            } else {
                $error = "Invalid file type. Only PDF and Word documents are allowed.";
            }
        }
    }
?>
<?php
if (isset($_GET['message'])) {
    echo "<div class='success'>" . htmlspecialchars($_GET['message']) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Process Application</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <div class="main-wrapper">

        <?php include('includes/topbar.php');?> 

        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php');?>  

                <div class="main-page">
                    <div class="container-fluid">
                        <h2 class="title">Process Application</h2>

                        <?php if (isset($message)) { ?>
                            <div class="succWrap"><?php echo htmlentities($message); ?></div>
                        <?php } elseif (isset($error)) { ?>
                            <div class="errorWrap"><?php echo htmlentities($error); ?></div>
                        <?php } ?>

                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" value="<?php echo htmlentities($result->firstName); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" value="<?php echo htmlentities($result->lastName); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Program</label>
                                <input type="text" class="form-control" value="<?php echo htmlentities($result->program); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Degree Type</label>
                                <input type="text" class="form-control" value="<?php echo htmlentities($result->degreeType); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Admission Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="admitted">Admit</option>
                                    <option value="denied">Deny</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Upload Admission Letter (PDF or Word Document)</label>
                                <input type="file" name="admissionLetter" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
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
