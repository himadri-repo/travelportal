<?php 
//This is added just to keep mysql function calls as it is but still using mysqki library
include_once("./mysql2mysqli.php");
include_once('../config.php');
if(isset($_SESSION['oxytra_admin_id']))
{
$sql="SELECT * FROM admin_tbl WHERE admin_id=".$_SESSION['oxytra_admin_id']."";
$result=mysql_query($sql);
$row_admin=mysql_fetch_array($result);
$auth=explode(",",$_SESSION["authority"]);
}
else
{
?>
<script>window.location.href="index.php"</script>
<?php
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />		
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />		
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/font-awesome/4.2.0/css/font-awesome.min.css" />	
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/fonts/fonts.googleapis.com.css" />		
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/style.min.css" />
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/custom.css" />
	
        <!--<script type="text/javascript" src="ckeditor/ckeditor.js"></script> -->		
		<script type="text/javascript">
		 var baseurl=<?php echo "'".$baseurl."'"; ?>
		</script>
	
	