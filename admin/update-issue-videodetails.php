<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['return']))
{
$rid=intval($_GET['rid']);
$fine=$_POST['fine'];
$rstatus=1;
$videoid=$_POST['videoid'];
$sql="update tblissuedvideodetails set fine=:fine,RetrunStatus=:rstatus where id=:rid;
update tblvideos set isIssued=0 where id=:videoid";
$query = $dbh->prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->bindParam(':fine',$fine,PDO::PARAM_STR);
$query->bindParam(':rstatus',$rstatus,PDO::PARAM_STR);
$query->bindParam(':videoid',$videoid,PDO::PARAM_STR);
$query->execute();

$_SESSION['msg']="Video Returned successfully";
header('location:manage-issued-video.php');



}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Lend A Book Library Management System | Issued Video Details</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<script>

// function for get student name
function getstudent() {
$("#loaderIcon").show();
jQuery.ajax({
url: "get_student.php",
data:'studentid='+$("#studentid").val(),
type: "POST",
success:function(data){
$("#get_student_name").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

//function for video details
function getvideo() {
$("#loaderIcon").show();
jQuery.ajax({
url: "get-video.php",
data:'videoid='+$("#videoid").val(),
type: "POST",
success:function(data){
$("#get_video_name").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

</script> 
<style type="text/css">
  .others{
    color:red;
}

</style>


</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Issued Video Details</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
<div class="panel panel-info">
<div class="panel-heading">
Issued Video Details
</div>
<div class="panel-body">
<form role="form" method="post">
<?php 
$rid=intval($_GET['rid']);
$sql = "SELECT tblstudents.StudentId ,tblstudents.FullName,tblstudents.EmailId,tblstudents.MobileNumber,tblvideos.VideoName,tblvideos.VideoNumber,tblissuedvideodetails.IssuesDate,tblissuedvideodetails.ReturnDate,tblissuedvideodetails.id as rid,tblissuedvideodetails.fine,tblissuedvideodetails.RetrunStatus,tblvideos.id as vid,tblvideos.videoimage from  tblissuedvideodetails join tblstudents on tblstudents.StudentId=tblissuedvideodetails.StudentId join tblvideos on tblvideos.id=tblissuedvideodetails.Videoid where tblissuedvideodetails.id=:rid";
$query = $dbh -> prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                   


<input type="hidden" name="videoid" value="<?php echo htmlentities($result->vid);?>">
<h4>Student Details</h4>
<hr />
<div class="col-md-6"> 
<div class="form-group">
<label>Student ID :</label>
<?php echo htmlentities($result->StudentId);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Student Name :</label>
<?php echo htmlentities($result->FullName);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Student Email Id :</label>
<?php echo htmlentities($result->EmailId);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Student Contact No :</label>
<?php echo htmlentities($result->MobileNumber);?>
</div></div>



<h4>Video Details</h4>
<hr />

<div class="col-md-6"> 
<div class="form-group">
<label>Video Image :</label>
<img src="bookimg/<?php echo htmlentities($result->videoimage); ?>" width="120">
</div></div>


<div class="col-md-6"> 
<div class="form-group">
<label>Video Name :</label>
<?php echo htmlentities($result->VideoName);?>
</div>
</div>
<div class="col-md-6"> 
<div class="form-group">
<label>Video Number :</label>
<?php echo htmlentities($result->VideoNumber);?>
</div>
</div>

<div class="col-md-6"> 
<div class="form-group">
<label>Video Issued Date :</label>
<?php echo htmlentities($result->IssuesDate);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Video Returned Date :</label>
<?php if($result->ReturnDate=="")
                                            {
                                                echo htmlentities("Not Return Yet");
                                            } else {


                                            echo htmlentities($result->ReturnDate);
}
                                            ?>
</div>
</div>

<div class="col-md-12"> 
<div class="form-group">
<label>Fine (in LKR) :</label>
<?php 
if($result->fine=="")
{?>
<input class="form-control" type="text" name="fine" id="fine"  required />

<?php }else {
echo htmlentities($result->fine);
}
?>
</div>
</div>
 <?php if($result->RetrunStatus==0){?>

<button type="submit" name="return" id="submit" class="btn btn-info">Return Video </button>

 </div>

<?php }}} ?>
                                    </form>
                            </div>
                        </div>
                            </div>

        </div>
   
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>

</body>
</html>
<?php } ?>
