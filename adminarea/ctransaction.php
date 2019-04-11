<?php 
include_once('header.php');
if(!in_array(10,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
?>

<?php 
$uid = 0;
if(isset($_POST["user_id"])) {
	$uid = $_POST["user_id"];
}
?>
<title>User Transaction | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>				
								Agent Transaction Table								
							</h1>
                          						
						</div><!-- /.page-header -->                      
						
						
						<div class="row">
							<div class="col-xs-12">																				
								<div class="table-header" id="result">								
								</div>
                                <div class="form-group has-info col-sm-12" style="float:left;margin-top:15px">																											
									
									<form action="" method="POST">
										<div class="col-sm-3" id="div_user" >
										  <select  class="col-xs-12 col-sm-12" name="user_id" id="user_id">
											 <option value="">Select Agent</option>
											<?php 
											$sql="SELECT * FROM user_tbl WHERE 1";
											$result=mysql_query($sql);
											while($row=mysql_fetch_array($result))
											{										
										   ?>
											<option <?php if($uid==$row["id"] ) echo "selected";?> value="<?php echo $row["id"];?>"><?php echo $row["name"]." ( ".$row["user_id"]." ) "; ?></option>
											<?php
											}
											?>
										  </select>
										</div>
										
										<!--<div class="col-sm-2" id="div_date_from">
										  <input type="text"  id="dt_date_from" name="dt_date_from" placeholder="Date From" class="col-xs-12 col-sm-12 dpd3">									
										</div>
								
										<div class="col-sm-2" id="div_date_to">
										  <input type="text"  id="dt_date_to" name="dt_date_to" placeholder="Date To" class="col-xs-12 col-sm-12 dpd3">														
										</div>-->
										
										<div class="col-sm-3" >
										  <button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_search">															
												<span class="bigger-110">Search</span>
										 </button>
										</div>
									</form>
									
								</div>
					
								<div style="background:#EFF3F8;border:1px solid #ccc" >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>SI No.</th>													
												<th>Name</th>
												<th>Date</th>
												<th>Purpose</th>                                                
												<th>Credit</th>	
												<th>Debit</th>
											</tr>
										</thead>
										<tbody id="grid">
										    <?php
											  $ctr=1;
											  $total=0;
											  $sql="SELECT w.*,u.name FROM wallet_tbl w INNER JOIN user_tbl u ON w.user_id=u.id 
											  WHERE w.user_id='".$uid."'";
											  $result=mysql_query($sql);
											  while($row=mysql_fetch_array($result))
											  {
											?>
												<tr>
												   <td><?php echo $ctr; ?></td>
												   <td><?php echo $row["name"];?></td>
												   <td><?php echo date("jS M y h:i a",strtotime($row["date"]))?></td>
												   <td>
												    <?php
													  if($row["refrence"]>0)
													  {
														  $sql_get="SELECT * FROM payment_request_tbl WHERE id='".$row["refrence"]."'";
														  $result_get=mysql_query($sql_get);
														  $row_get=mysql_fetch_array($result_get);	
														  
														  if($row_get["payment_type"]=="NEFT")
															  echo "NEFT, REFRENCE ID : ".$row_get["refrence_id"]."";
														  
														  if($row_get["payment_type"]=="CHEQUE")
															  echo "CHEQUE PAYMENT, CHEQUE NO : ".$row_get["cheque_no"]."";
														  
														   if($row_get["payment_type"]=="BANK")
															  echo "BANK";
													  }
													  
													  if($row["booking_id"]>0)
													  {
														  
															  echo "BOOKING NO. ".$row["booking_id"]."";
													  }
													?>
												   </td>
												   
												    <?php if($row["type"]=="CR") {?>
													   <td><?php echo  number_format($row["amount"],2,".",",");?></td>
													   <td></td>
												   <?php } ?>
												   
												   <?php if($row["type"]=="DR") {?>
												       <td></td>
												       <td><?php echo  number_format((0-$row["amount"]),2,".",",");?></td>
												 
												   <?php } ?>
												   
												  
												   
                                                </tr>												
											<?php
											   $total=$total+$row["amount"];
											   $ctr++;
											  }
											?>
											
											<tr>
											   <td colspan="5">Total</td>
											   <td><?php echo  number_format($total,2,".",",");?></td>
											</tr>
										</tbody>
									</table>

									

									
								</div>
							</div>
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<?php include_once('footer.php');?> 
<link rel="stylesheet" href="../calendar/base/jquery.ui.all.css">
<script src="../calendar/jquery-1.5.1.js"></script>
<script src="../calendar/jquery.ui.core.js"></script>
<script src="../calendar/jquery.ui.widget.js"></script>
<script src="../calendar/jquery.ui.datepicker.js"></script> 
 <script>
	$(function() 
	{
		$("#menu_ctransaction").addClass("active");		
		$("#dt_date_from" ).datepicker();
		$("#dt_date_to" ).datepicker();
		$("#dt_date_from" ).datepicker("option", "dateFormat","dd-mm-yy");
		$("#dt_date_to" ).datepicker("option", "dateFormat","dd-mm-yy");
	});
</script>