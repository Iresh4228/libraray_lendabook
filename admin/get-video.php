<?php 
require_once("includes/config.php");
if(!empty($_POST["videoid"])) {
  $videoid=$_POST["videoid"];
 
    $sql ="SELECT distinct tblvideos.VideoName,tblvideos.id,tblauthors.AuthorName,tblvideos.videoimage,tblvideos.isIssued FROM tblvideos
join tblauthors on tblauthors.id=tblvideos.AuthorId
     WHERE (VideoNumber=:videoid || VideoName like '%$videoid%')";
$query= $dbh -> prepare($sql);
$query-> bindParam(':videoid', $videoid, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query -> rowCount() > 0){
?>
<table border="1">

  <tr>
<?php foreach ($results as $result) {?>
    <th style="padding-left:5%; width: 10%;">
<img src="bookimg/<?php echo htmlentities($result->videoimage); ?>" width="120"><br />
      <?php echo htmlentities($result->VideoName); ?><br />
    <?php echo htmlentities($result->AuthorName); ?><br />
    <?php if($result->isIssued=='1'): ?>
<p style="color:red;">Video Already issued</p>
<?php else:?>
<input type="radio" name="videoid" value="<?php echo htmlentities($result->id); ?>" required>
<?php endif;?>
  </th>
    <?php  echo "<script>$('#submit').prop('disabled',false);</script>";
}
?>
  </tr>

</table>
</div>
</div>

<?php  
}else{?>
<p>Record not found. Please try again.</p>
<?php
 echo "<script>$('#submit').prop('disabled',true);</script>";
}
}
?>
