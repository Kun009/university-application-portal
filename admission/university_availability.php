<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['university-name'])) {
    $universityName = mysqli_real_escape_string($con, $_POST['university-name']);
    
    // Query to check if the university name already exists
    $query = "SELECT * FROM tbl_university WHERE universityName='$universityName'";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        // Check if any row was returned
        if (mysqli_num_rows($result) > 0) {
            // University name exists
            echo "<span style='color:red;'>University name already exists. Please choose a different name.</span>";
        } else {
            // University name is available
            echo "<span style='color:green;'>University name is available.</span>";
        }
    } else {
        // Query failed
        echo "<span style='color:red;'>Error checking university name. Please try again.</span>";
    }
} else {
    echo "<span style='color:red;'>Invalid request.</span>";
}
?>
