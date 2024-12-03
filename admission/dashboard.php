<?php session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Dashboard</title>
<!-- Bootstrap Core CSS -->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="bower_components/metisMenu/dist/metisMenu.min.css"
	rel="stylesheet">
<!-- Custom CSS -->
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<form method="post" >
	<div id="wrapper">

		<!-- Navigation -->
		<?php include('leftbar.php')?>;

<!--nav-->
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h4 class="page-header"> <?php echo strtoupper("welcome"." ".htmlentities($_SESSION['login']));?></h4>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">Dashboard</div>
						<div class="panel-body">
							<div class="row">
						 	<div class="col-lg-12">
									
						
		<!---Universities ----->
      <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file fa-5x"></i>
                                </div>

<?php $query=mysqli_query($con,"SELECT unid FROM tbl_university");
$listeduniversity=mysqli_num_rows($query);

?>


                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($listeduniversity);?></div>
                                    <div>Listed Universities</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-uni.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
								

<!------------Applicants------------>

             <div class="col-lg-4 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-fw fa-5x"></i>
                                </div>
<?php 
$query1=mysqli_query($con,"SELECT id FROM tbl_applicant");
$applicant=mysqli_num_rows($query1);
?>

                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($applicant);?></div>
                                    <div>Applicants</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-applicants.php">
                            <div class="panel-footer">
                                <span class="pull-left">View details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>


                
<!------------Applications ------------>

             <div class="col-lg-4 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file fa-5x"></i>
                                </div>
<?php 
$query1=mysqli_query($con,"SELECT id FROM tbl_applications");
$application=mysqli_num_rows($query1);
?>

                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($application);?></div>
                                    <div>Applications</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-applicantion.php">
                            <div class="panel-footer">
                                <span class="pull-left">View details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
<!---- Students----->
       <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-fw fa-5x"></i>
                                </div>

<?php 
$query2=mysqli_query($con,"SELECT id FROM tbl_login");
$totalstudents=mysqli_num_rows($query2);
?>


                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($totalstudents);?></div>
                                    <div>Admins</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-admin.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

<!---Officer---------->
     <div class="col-lg-4 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-fw fa-5x "></i>
                                </div>
<?php 
$query3=mysqli_query($con,"SELECT officer_id FROM tbl_officer");
$officer=mysqli_num_rows($query3);
?>

                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlentities($officer);?></div>
                                    <div>Admission Officers</div>
                                </div>
                            </div>
                        </div>
                        <a href="manage-officer.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a> 
                    </div>
                </div>

	</div>	
										
	
		
			
													
				</div>

					</div>
								
							</div>
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		

	</div>
	
	<script src="bower_components/jquery/dist/jquery.min.js"
		type="text/javascript"></script>

	
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"
		type="text/javascript"></script>

	<!-- Metis Menu Plugin JavaScript -->
	<script src="bower_components/metisMenu/dist/metisMenu.min.js"
		type="text/javascript"></script>

	<!-- Custom Theme JavaScript -->
	<script src="dist/js/sb-admin-2.js" type="text/javascript"></script>
	
	<script>
function courseAvailability() {
	$("#loaderIcon").show();
jQuery.ajax({
url: "course_availability.php",
data:'cshort='+$("#cshort").val(),
type: "POST",
success:function(data){
$("#course-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

function coursefullAvail() {
	$("#loaderIcon").show();
jQuery.ajax({
url: "course_availability.php",
data:'cfull='+$("#cfull").val(),
type: "POST",
success:function(data){
$("#course-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>
</form>
</body>
</html>
<?php } ?>
