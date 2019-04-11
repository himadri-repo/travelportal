<?php 
include_once('header.php');
if(!in_array(2,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
$error="";
$city=isset($_POST['city'])? trim($_POST['city']):"";
$city=mysql_real_escape_string($city);
$country=isset($_POST['country'])? trim($_POST['country']):"";
if(isset($_POST["save"]))
{
   $sql_check="SELECT * FROM city_tbl WHERE city='$city' AND country='$country'";
   $result=mysql_query($sql_check);
   if(mysql_num_rows($result)>0)
   {
	   $error="This City already Exist in this country";
	   
   }
  else
   {
	   $sql="INSERT INTO city_tbl(id,city,country)VALUES('','$city','$country')";
	   if(mysql_query($sql))
					 $_SESSION['city_msg']="City Added Successfully"; 
	   else
					 $_SESSION['city_msg']=mysql_error();
			   ?>
			<script>
			 window.location.href="city.php"; 
			</script>
			<?php	
   }	
}
if(isset($_POST["update"]))
{
   $sql_check="SELECT * FROM city_tbl WHERE city='$city' AND country='$country' AND id!='".$_GET['id']."'";
   $result=mysql_query($sql_check);
   if(mysql_num_rows($result)>0)
   {
	   $error="This City already Exist in this country";
   }
   else
   {
		$sql="UPDATE city_tbl SET city='$city',country='$country' WHERE id='".$_GET['id']."'";
		 if(mysql_query($sql))
				$_SESSION['city_msg']="City Updated Successfully"; 
		 else
			$_SESSION['city_msg']=mysql_error();
		 ?>
			<script>
			 window.location.href="city.php"; 
			</script>
			<?php	
			
   }	
}
if(isset($_GET['id']) && is_numeric($_GET['id'])) 
{
	$sql="SELECT * FROM city_tbl WHERE id='".$_GET['id']."'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result)>0)
    {
	 $row=mysql_fetch_array($result);
	 $city=mysql_real_escape_string($row['city']);
	 $country=$row['country'];	 
	 }
	 else
	 {
	    ?>
		<script>
		 window.location.href="city.php"; 
		</script>
		<?php	
	 }
}
?>
<title>City | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header"><!--.page-header -->
							<h1>
								 <?php 
								   if(!isset($_GET['id'])) 
								   {
									    echo "Add City";
								   }
								   else
								   {
									   echo "Edit City";
								   }
								  ?>
                                   						  
							</h1>							
						</div><!-- /.page-header -->
                        <form  id="frm_city" enctype="multipart/form-data" action="" method="post"> 						
                        <div class="row">
							    <?php 
								   if(!isset($_GET['id'])) 
								   {?>
								   <div class="form-group has-info col-sm-2 " >									
									<button type="submit" class="btn btn-sm btn-primary col-md-10"  name="save">															
										<span class="bigger-110">Add</span>
									</button>																		
									</div>
								   <?php
									}
									  else
									  {
									  ?>
									<div class="form-group has-info col-sm-2">									
									<button type="submit" class="btn btn-sm btn-primary col-md-10"  name="update">															
										<span class="bigger-110">Update</span>
									</button>																		
									</div>  
								 <?php
									}
								?>								 
							  <div class="form-group has-info col-sm-2" >									
								<a href="city.php" class="pull-left btn btn-sm btn-default col-md-10" >															
									<span class="bigger-110">Cancel</span>
								</a>																		
							  </div>
						</div>
						
						<div class="row">
							<div class="col-xs-12 col-sm-8"><!--Widget col-md-8 start-->
							    <div class="widget-box" style="float:left"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Enter City Details</small>
												</h4>
									</div>
									<div class="widget-body"><!--Widget Body start-->																									
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> City Name (required)</label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" id="city" maxlength="100" name="city" placeholder="City Name" class="col-xs-12 col-sm-12" required value="<?php echo $city;?>">
														<?php
														  if(!empty($error))
														  {
														?>	
														  <div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 "><?php echo $error; ?></div>
														<?php
														
														}
														?>
													</div>
											</div>

                                            <div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Country (required)</label>
													<div class="col-xs-12 col-sm-12">
														<select id="country" name="country" class="col-xs-12 col-sm-12" required>
														 <option value="">----Select Country----</option>
														<?php
														  $sql="SELECT * FROM country ORDER BY country_name ASC";
														  $result=mysql_query($sql);
														  while($row=mysql_fetch_array($result))
														  {
														 ?>
														   <option <?php if($country==$row['id']) echo "selected";?> value="<?php echo $row["id"];?>"><?php echo $row["country_name"];?></option>
													   <?php 
														  }
													   ?>			
														</select>
													</div>
											</div>											
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-8 end-->														                                               							
						</div><!-- /.row -->
						</form> 
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<?php include_once('footer.php');?>
<script>
 $("#master").addClass('active');	
 $("#menu_city").addClass('active');
</script>