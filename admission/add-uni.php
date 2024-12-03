<?php 
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']==0)) {
    header('location:logout.php');
} else {
    if(isset($_POST['submit'])){
        $universityName = $_POST['university-name'];
        $aboutUniversity = strip_tags($_POST['about-university']);
        $programs = strip_tags($_POST['programs']);
        $faculties = strip_tags($_POST['faculties']);
        $degreesOffered = strip_tags($_POST['degrees-offered']);
        $universityAddress = $_POST['university-address'];
        $email = $_POST['email'];
        $schoolWebsite = $_POST['website'];
        $phoneNumber = $_POST['phone-number'];
        $admissionRequirements = strip_tags($_POST['admission-requirements']);
        $creationDate = date('Y-m-d');
    
        // Handle file upload for university logo
        if(isset($_FILES['university-logo']) && $_FILES['university-logo']['error'] == 0) {
            $logo = $_FILES['university-logo']['name']; // Access the uploaded file's name from $_FILES
            $extension = substr($logo, strlen($logo) - 4, strlen($logo));
            $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
    
            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Invalid file format. Only jpg, jpeg, png, gif allowed');</script>";
            } else {
                $logoNew = md5($logo) . time() . $extension;
                $targetFilePath = "uploads/university_logos/" . $logoNew;
    
                // Move the file to the target directory
                if (move_uploaded_file($_FILES["university-logo"]["tmp_name"], $targetFilePath)) {
                    // Insert into database
                    $query = mysqli_query($con, "INSERT INTO tbl_university 
                        (schoolWebsite, universityName, aboutUniversity, programs, faculties, degreesOffered, universityAddress, email, phoneNumber, admissionRequirements, logoPath, creationDate) 
                        VALUES ('$schoolWebsite', '$universityName', '$aboutUniversity', '$programs', '$faculties', '$degreesOffered', '$universityAddress', '$email', '$phoneNumber', '$admissionRequirements', '$logoNew', '$creationDate')");
    
                    if ($query) {
                        echo '<script>alert("University added successfully")</script>';
                        echo "<script>window.location.href='manage-uni.php'</script>";
                    } else {
                        echo '<script>alert("Something went wrong. Please try again")</script>';
                    }
                } else {
                    echo "<script>alert('File could not be uploaded. Please try again.');</script>";
                }
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
<title>Add University</title>
<!-- Bootstrap Core CSS -->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<script src="https://cdn.tiny.cloud/1/6iefhgdiimxb0bkkv7a3tliyyx7m0if1ve6hw8nlu49ahkef/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
tinymce.init({
    selector: 'textarea',
    plugins: ['anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'],
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    setup: function (editor) {
        editor.on('change', function () {
            editor.save(); // This will update the textarea with the content from the editor
        });
    }
});
</script>


</head>

<body>
<form method="post" action="" enctype="multipart/form-data">


    <div id="wrapper">

        <!-- Navigation -->
        <?php include('leftbar.php'); ?>

        <!-- Page Wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header"> <?php echo strtoupper("welcome" . " " . htmlentities($_SESSION['login'])); ?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Add University</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label for="university-name">University Name<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="university-name" id="university-name" required="required" onblur="universityAvailability()">
                                        <span id="university-availability-status" style="font-size:12px;"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="about-university">About University<span style="font-size:11px;color:red">*</span></label>
                                        <textarea class="form-control" name="about-university" id="about-university" required="required"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="programs">Programmes<span style="font-size:11px;color:red">*</span></label>
                                        <textarea class="form-control" name="programs" id="programs" required="required"> </textarea>
                                    </div>
									<div class="form-group">
                                        <label for="faculties">Faculties<span style="font-size:11px;color:red">*</span></label>
                                        <textarea class="form-control" name="faculties" id="faculties" required="required"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="degrees-offered">Degrees Offered<span style="font-size:11px;color:red">*</span></label>
                                        <textarea class="form-control" name="degrees-offered" id="degrees-offered" required="required"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="university-address">University Address<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="university-address" id="university-address" required="required">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email<span style="font-size:11px;color:red">*</span></label>
                                        <input class="form-control" name="email" id="email" required="required">
                                    </div>

                                    <div class="form-group">
    <label for="phone-number">Phone Number<span style="font-size:11px;color:red">*</span></label>
    <input class="form-control" name="phone-number" id="phone-number" required="required"
           type="tel" pattern="^\+?[0-9]{10,13}$" 
           title="Please enter a valid phone number (10-13 digits, optionally starting with +).">
</div>


                                    <div class="form-group">
    <label for="admission-requirements">Admission Requirements<span style="font-size:11px;color:red">*</span></label>
    <textarea class="form-control" name="admission-requirements" id="admission-requirements" required="required"></textarea>
</div>


									<div class="form-group">
    <label for="website">University Website</label>
    <input class="form-control" name="website" id="website" required="required">
</div>

<div class="form-group">
        <label for="university-logo">University Logo<span style="font-size:11px;color:red">*</span></label>
        <input type="file" class="form-control" name="university-logo" id="university-logo" required="required" accept="image/*">
    </div>
                                    <div class="form-group">
                                        <label for="date">Creation Date</label>
                                        <input class="form-control" value="<?php echo date('d-m-Y');?>" readonly="readonly" name="cdate">
                                    </div>

									<div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <a href="manage-uni.php" class="btn btn-default">Cancel</a>
                            </div>
                                </div>
                            </div>                        
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

    <script>
    function universityAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "university_availability.php",
            data: 'university-name=' + $("#university-name").val(),
            type: "POST",
            success: function(data) {
                $("#university-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }

    
    </script>
</form>
</body>
</html>

