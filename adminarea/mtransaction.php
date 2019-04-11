<?php 
include_once('header.php');
if(!in_array(11,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
$msg="";
$user_id=isset($_POST['user_id'])? $_POST['user_id']:"";
$amount=isset($_POST['amount'])? $_POST['amount']:"";
$narration=isset($_POST['narration'])? $_POST['narration']:"";
$type=isset($_POST['type'])? $_POST['type']:"";
$date=date("Y-m-d h:i:s");
if($type=="DR")
	$amount=(0-$amount);

if(isset($_POST["submit"]))
{	
	$sql="INSERT INTO wallet_tbl (date,user_id,amount,narration,type) VALUES('$date','$user_id','$amount','$narration','$type')";
	$result=mysql_query($sql);   
	if($result)
	{
		$msg="Transaction Completed Successfully";
		
	}
	
}


?>
<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/select2.min.css" />	
<link href="<?php echo $baseurl;?>/adminassets/css/datepicker/datepicker.css" rel="stylesheet" type="text/css" media="all"/>
<title>Manage Account | <?php echo $row_top['site_title']; ?></title>
<style>
.pink
{
  min-height:78px;
}
#img img
{
	width:200px;
	height:auto;
}
</style>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<form id="frm_product" action="" enctype="multipart/form-data" method="post" >
						<div class="page-header">
							<h1>
							  Manage Account
								
							</h1>
							<?php
							if(!empty($msg))
							{
								echo "<div class='col-md-12 alert alert-success'>".$msg."</div>";
							}
							?>
						</div><!-- /.page-header -->
                        <div class="row">
							   
							    <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_create" name="submit">															
									<span class="bigger-110">Submit</span>
								</button>																		
							    </div>
								
							 
							  <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<a href="dashboard.php" class="pull-left btn btn-sm btn-default col-md-10" id="btn_cancel">															
									<span class="bigger-110">Cancel</span>
								</a>																		
							  </div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-8"><!--Widget col-md-8 start-->
							    <div class="widget-box" style="float:left;width:100%"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Enter  Details</small>
												</h4>
									</div>
									<div class="widget-body"><!--Widget Body start-->																											
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Agent  </label>
													<div class="col-xs-12 col-sm-12">
														<select name="user_id" class="col-xs-12 col-sm-12" required>	
														  <option value="" >----Select Agent----</option>
														  <?php
														  $sql="SELECT * FROM user_tbl WHERE active=1";
														  $result=mysql_query($sql);
														  while($row=mysql_fetch_array($result))
														  {
															  ?>
															   <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]." ( ".$row["user_id"]." ) "; ?></option>
															  <?php
														  }
														  ?>
														</select>
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Purpose  </label>
													<div class="col-xs-12 col-sm-12">
														<select name="type" class="col-xs-12 col-sm-12" required>	
														  <option value="CR">Credit</option>
														  <option value="DR">Debit</option>
														</select>											
													</div>
											</div>
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Narration</label>
													<div class="col-xs-12 col-sm-12">
															<input type="text" name="narration" class="col-xs-12 col-sm-12" placeholder="Narration" required>											
													</div>
											</div>
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Amount</label>
													<div class="col-xs-12 col-sm-12">
															<input type="number" name="amount" class="col-xs-12 col-sm-12" placeholder="Amount" required>											
													</div>
											</div>
																																																		
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-8 end-->
							
							
							

                          
					    </div><!-- row -->
						</form>
				    </div><!-- /page-content -->
				</div><!-- /.page-content -->
			</div>
			
<?php include_once('footer.php');?>
<script>
    $(document).ready(function()
	{	
        		$("#menu_mtransaction").addClass("active");				
		
	});
</script> 