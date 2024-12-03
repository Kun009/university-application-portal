<?php
session_start();
include('includes/config.php');

// Check if the officer is logged in
if(strlen($_SESSION['ologin']) == 0){   
    header('location:index.php');
} else {
    // Fetch officer details from session (unid and university_name)
    $email = $_SESSION['ologin'];
    $sql = "SELECT unid, university_name FROM tbl_officer WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $officer = $query->fetch(PDO::FETCH_OBJ);

    if (isset($_POST['submit'])) {
        // Handle form submission
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $position = $_POST['position'];
        $joining_date = $_POST['joining_date'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        
        // Password generation and hashing
        $password = $_POST['password'];

        // Photo upload
        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_folder = "../admission/uploads/" . $photo;
        move_uploaded_file($photo_tmp, $photo_folder);

        // Insert staff into the database
        $sql = "INSERT INTO tbl_officer (unid, university_name, first_name, last_name, email, phone_number, photo, position, joining_date, address, status, created_at, updated_at, password) 
                VALUES (:unid, :university_name, :first_name, :last_name, :email, :phone_number, :photo, :position, :joining_date, :address, :status, :created_at, :updated_at, :password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':unid', $officer->unid, PDO::PARAM_INT);
        $query->bindParam(':university_name', $officer->university_name, PDO::PARAM_STR);
        $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $query->bindParam(':photo', $photo, PDO::PARAM_STR);
        $query->bindParam(':position', $position, PDO::PARAM_STR);
        $query->bindParam(':joining_date', $joining_date, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $query->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        
        if($query->execute()){
            echo "<script>alert('Staff added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding staff. Please try again.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
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
                                <h2 class="title">Add New Staff</h2>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <form method="POST" enctype="multipart/form-data">
                                                    <!-- Officer's University ID and Name (Auto-filled) -->
                                                    <div class="form-group">
                                                        <label for="university_name">University Name</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlentities($officer->university_name); ?>" readonly>
                                                    </div>

                                                    <!-- First Name -->
                                                    <div class="form-group">
                                                        <label for="first_name">First Name</label>
                                                        <input type="text" name="first_name" class="form-control" required>
                                                    </div>

                                                    <!-- Last Name -->
                                                    <div class="form-group">
                                                        <label for="last_name">Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" required>
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" name="email" class="form-control" required>
                                                    </div>

                                                    <!-- Phone Number -->
                                                    <div class="form-group">
                                                        <label for="phone_number">Phone Number</label>
                                                        <input type="text" name="phone_number" class="form-control" required>
                                                    </div>

                                                    <!-- Photo Upload -->
                                                    <div class="form-group">
                                                        <label for="photo">Photo</label>
                                                        <input type="file" name="photo" class="form-control" required>
                                                    </div>

                                                    <!-- Position -->
                                                    <div class="form-group">
                                                        <label for="position">Position</label>
                                                        <input type="text" name="position" class="form-control" required>
                                                    </div>

                                                    <!-- Joining Date -->
                                                    <div class="form-group">
                                                        <label for="joining_date">Joining Date</label>
                                                        <input type="date" name="joining_date" class="form-control" required>
                                                    </div>

                                                    <!-- Address -->
                                                    <div class="form-group">
                                                        <label for="address">Address</label>
                                                        <textarea name="address" class="form-control" required></textarea>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select name="status" class="form-control" required>
                                                            <option value="active">Active</option>
                                                            <option value="inactive">Inactive</option>
                                                        </select>
                                                    </div>

                                                    <!-- Password -->
                                                    <div class="form-group">
                                                        <label for="password">Password</label>
                                                        <input type="password" name="password" class="form-control" required>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="form-group">
                                                        <button type="submit" name="submit" class="btn btn-primary">Add Staff</button>
                                                    </div>
                                                </form>
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
