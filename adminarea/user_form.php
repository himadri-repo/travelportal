<?php 
include_once('header.php');
if(!in_array(12,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
$name=isset($_POST['name'])? $_POST['name']:"";
$user_name=isset($_POST['user_name'])? $_POST['user_name']:"";
$password=isset($_POST['password'])? $_POST['password']:"";
$authority=isset($_POST['authority'])? $_POST['authority']:"";


if(isset($_POST["btn_create"]))
{
	$tmp=1;
	$str="";
	foreach($authority as $key=>$value)
	{
		if($tmp<sizeof($authority))
		      $str.=$value.",";
		else
       		$str.=$value;	
		$tmp++;
	}
	$sql_check="SELECT * FROM admin_tbl WHERE user_name='$user_name'";
	$result_check=mysql_query($sql_check);
	if(mysql_num_rows($result_check)==0)
	{
		$sql_insert="INSERT INTO admin_tbl (name,user_name,password,authority)VALUES('$name','$user_name','$password','$str')";
		if(mysql_query($sql_insert))
			$msg="<div class='alert alert-success'>User Created Successfully </div>";
	}
	else
		$msg="<div class='alert alert-danger'>This username already exist please try another one</div>";
}
if(isset($_POST["btn_update"]))
{
	$tmp=1;
	$str="";
	foreach($authority as $key=>$value)
	{
		if($tmp<sizeof($authority))
		      $str.=$value.",";
		else
       		$str.=$value;	
		$tmp++;
	}
	$sql_check="SELECT * FROM admin_tbl WHERE user_name='$user_name'  WHERE admin_id!='".$_GET["id"]."'";
	$result_check=mysql_query($sql_check);
	if(mysql_num_rows($result_check)==0)
	{
		$sql_update="UPDATE admin_tbl SET name='$name',user_name='$user_name',password='$password',authority='$str' WHERE admin_id='".$_GET["id"]."'";
		if(mysql_query($sql_update))
			$msg="<div class='alert alert-success'>User Updated Successfully </div>";
	}
	else
		$msg="<div class='alert alert-danger'>This username already exist please try another one</div>";
}
if(isset($_GET['id'])) 
{
	$sql="SELECT * FROM admin_tbl WHERE admin_id='".$_GET["id"]."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$name=$row["name"];
	$user_name=$row["user_name"];
	$password=$row["password"];
	$authority=$row["authority"];
	
}
$arr=explode(",",$authority);
?>
<title>User | <?php echo $row_top['site_title']; ?></title>
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
							     echo "Add User";
							   }
							   else
							   {
							      echo "Edit User ";
							   }
							   if(!empty($msg)) echo $msg;
							   ?> 
							   
							</h1>							
						</div><!-- /.page-header -->
						<form  id="frm_customer" method="post" action="" enctype="multipart/form-data" autocomplete="off">	
                        <div class="row">
							   <?php 
							   if(!isset($_GET['id'])) 
							   {?>
							    <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_create" name="btn_create">															
									<span class="bigger-110">Add</span>
								</button>																		
							    </div>
								
							  <?php
							  }
							  else
							  {
							  ?>
							    <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_update" name="btn_update">															
									<span class="bigger-110">Update</span>
								</button>																		
							    </div>
								
							  <?php
							  }
							  ?>
							  
							  <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<a href="users.php" class="pull-left btn btn-sm btn-default col-md-10" id="btn_cancel">															
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
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left">  Name (required) </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" id="name" name="name" placeholder="Name" class="col-xs-12 col-sm-12" required value="<?php echo $name;?>" autocomplete="off">
													</div>
											</div>
																						 											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Username</label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" id="user_name" name="user_name" placeholder="Username" class="col-xs-12 col-sm-12" required value="<?php echo $user_name;?>" autocomplete="off">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Password</label>
													<div class="col-xs-12 col-sm-12">
														<input type="password" id="password" name="password" placeholder="Password" class="col-xs-12 col-sm-12"  required value="<?php echo $password;?>" autocomplete="off">
													</div>
											</div>	
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Authority</label>
													<div class="col-xs-12 col-sm-12">
														1) <input type="checkbox"  name="authority[]" value="1" <?php if(in_array(1,$arr)) echo "checked";?>>Airlines<br/>
														2) <input type="checkbox"  name="authority[]" value="2" <?php if(in_array(2,$arr)) echo "checked";?>>City<br/>
														3) <input type="checkbox"  name="authority[]" value="3" <?php if(in_array(3,$arr)) echo "checked";?>>Agents<br/>
														4) <input type="checkbox"  name="authority[]" value="4" <?php if(in_array(4,$arr)) echo "checked";?>>Payment Request<br/>
														5) <input type="checkbox"  name="authority[]" value="5" <?php if(in_array(5,$arr)) echo "checked";?>>Tickets<br/>
														6) <input type="checkbox"  name="authority[]" value="6" <?php if(in_array(6,$arr)) echo "checked";?>>Update PNR<br/>
														7) <input type="checkbox"  name="authority[]" value="7" <?php if(in_array(7,$arr)) echo "checked";?>>Book Ticket<br/>
														8) <input type="checkbox"  name="authority[]" value="8" <?php if(in_array(8,$arr)) echo "checked";?>>Booking Request<br/>
														9) <input type="checkbox"  name="authority[]" value="9" <?php if(in_array(9,$arr)) echo "checked";?>>Cancel Request<br/>
														10) <input type="checkbox"  name="authority[]" value="10" <?php if(in_array(10,$arr)) echo "checked";?>>Agents Account<br/>
														11) <input type="checkbox"  name="authority[]" value="11" <?php if(in_array(11,$arr)) echo "checked";?>>Mangage Agents Account<br/>																																																								
														12) <input type="checkbox"  name="authority[]" value="12" <?php if(in_array(12,$arr)) echo "checked";?>>Users<br/>
														13) <input type="checkbox"  name="authority[]" value="13" <?php if(in_array(13,$arr)) echo "checked";?>>Settings<br/>
														14) <input type="checkbox"  name="authority[]" value="13" <?php if(in_array(14,$arr)) echo "checked";?>>Booking<br/>
														15) <input type="checkbox"  name="authority[]" value="13" <?php if(in_array(15,$arr)) echo "checked";?>>Content<br/>
														16) <input type="checkbox"  name="authority[]" value="13" <?php if(in_array(16,$arr)) echo "checked";?>>Content<br/>
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
$(document).ready(function()
{
	$("#menu_users").addClass("active");
});
</script>  	