<?php 
include_once('header.php');
$error="";
$country_name=isset($_POST['country'])? trim($_POST['country']):"";

if(isset($_POST["save"]))
{
   $sql_check="SELECT * FROM country WHERE  trim(country_name)='$country_name'";
   $result=mysql_query($sql_check);
   if(mysql_num_rows($result)>0)
   {
	   $error="This Country already Exist";
	   
   }
  else
   {
	   $sql="INSERT INTO country(id,country_name)VALUES('','$country_name')";
	   if(mysql_query($sql))
					 $_SESSION['country_msg']="Country Added Successfully"; 
	   else
					 $_SESSION['country_msg']=mysql_error();
			   ?>
			<script>
			 window.location.href="country.php"; 
			</script>
			<?php	
   }	
}

if(isset($_POST["update"]))
{
   $sql_check="SELECT * FROM country WHERE trim(country_name)='$country_name' AND id!='".$_GET['id']."'";
   $result=mysql_query($sql_check);
   if(mysql_num_rows($result)>0)
   {
	   $error="This Country already Exist";
   }
   else
   {
		$sql="UPDATE country SET country_name='$country_name' WHERE id='".$_GET['id']."'";
		 if(mysql_query($sql))
				$_SESSION['country_msg']="Country Updated Successfully"; 
		 else
			$_SESSION['country_msg']=mysql_error();
		  ?>
			<script>
			 window.location.href="country.php"; 
			</script>
			<?php	
			
   }	
}

if(isset($_GET['id']) && is_numeric($_GET['id'])) 
{
	$sql="SELECT * FROM country WHERE id='".$_GET['id']."'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result)>0)
    {
	 $row=mysql_fetch_array($result);	 
	 $country_name=$row['country_name'];	 
	 }
	 else
	 {
	    ?>
		<script>
		 window.location.href="country.php"; 
		</script>
	<?php	
	 }
}
?>
<title> Country | <?php echo $row_top['site_title']; ?></title>
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
									    echo "Add Country";
								   }
								   else
								   {
									   echo "Edit Country";
								   }
								  ?>						
							</h1>							
						</div><!-- /.page-header -->
                        <form  id="frm_country" enctype="multipart/form-data" action="" method="POST">							
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
								<a href="country.php" class="pull-left btn btn-sm btn-default col-md-10" >															
									<span class="bigger-110">Cancel</span>
								</a>																		
							  </div>
							  
						</div>
						
						<div class="row">
							<div class="col-xs-12 col-sm-8"><!--Widget col-md-8 start-->
							    <div class="widget-box" style="float:left"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Enter Details</small>
												</h4>
									</div>
									<div class="widget-body"><!--Widget Body start-->
										
										
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Country Name (required)</label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" id="country" maxlength="100" name="country" placeholder="Country Name" class="col-xs-12 col-sm-12" value="<?php echo $country_name;?>" required>
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
 $("#country").addClass('active');
</script>