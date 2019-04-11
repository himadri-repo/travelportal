<?php 
include_once('header.php');
if(!in_array(16,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
?>
<style>
#dynamic-table td
{
 font-size:11px;   
}
</style>
<title>Quotation | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>				
								Quotation Table								
							</h1>
                           <?php
							  if(!empty($_SESSION['cel_msg']))
							  {
                            ?>	
							  <div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 "><?php echo $_SESSION['cel_msg']; ?></div>
                            <?php
							   $_SESSION['cel_msg']="";
							}
                            ?>							
						</div><!-- /.page-header -->                      
						
						
                        <!--<div class="row">
							   <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
								<a href="celebrity_form.php"  class="pull-left btn btn-sm btn-primary" >															
								<span class="bigger-110">Add New</span>
								</a>																		
							  </div>
						</div>-->
						<div class="row">
							<div class="col-xs-12">																				
								<div class="table-header" id="result">								
								</div>
                                <div class="form-group has-info col-sm-12" style="float:left;margin-top:15px">																											
									<div class="col-sm-2" id="div_txt_name">
									  <!--<span class="block input-icon input-icon-left">
									  <input class="col-xs-12 col-sm-12" type="text" name="txt_search" id="txt_search" placeholder="Search..." value="" >-->
                                      <select  class="col-xs-12 col-sm-12" name="field" id="field">	
									    <option value="">Select</option>
										<option value="q.customer_id">Customer (Requester)</option>
										<option value="q.supplier_id">Supplier</option>
									  </select>
									</div>
									
									<!--<div class="col-sm-3" id="div_status">
									  <select  class="col-xs-12 col-sm-12" name="status" id="status">	
									     <option value="">All</option>
										<option value="PENDING">Pending</option>
										<option value="CONFIRM">Confirm</option>
									  </select>
									</div> -->
									
									<div class="col-sm-3" id="div_user" style="display:block">
									  <select  class="col-xs-12 col-sm-12" name="user_id" id="user_id">
									     <option value="">Select </option>
									    <?php 
										$sql="SELECT * FROM user_tbl";
										$result=mysql_query($sql);
										while($row=mysql_fetch_array($result))
										{										
									   ?>
										<option value="<?php echo $row["id"];?>"><?php echo $row["name"]." ( ".$row["user_id"]." ) "; ?></option>
										<?php
										}
										?>
									  </select>
									</div>
									
									<div class="col-sm-2" id="div_date_from">
									  <input type="text"  id="dt_date_from" name="dt_date_from" placeholder="Date From" class="col-xs-12 col-sm-12 dpd3">									
									</div>
							
									<div class="col-sm-2" id="div_date_to">
									  <input type="text"  id="dt_date_to" name="dt_date_to" placeholder="Date To" class="col-xs-12 col-sm-12 dpd3">														
									</div>
									
									<div class="col-sm-3" >
									  <button type="button" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_search">															
											<span class="bigger-110">Search</span>
								     </button>
									</div>
									
								</div>
					
								<div style="background:#EFF3F8;border:1px solid #ccc" >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Sl No.</th>	
												<th>Request Date</th>												
                                                <th>Type</th>												
												<th>Source</th>												
												<th>Destination</th>
												<th>Departure Date</th>
												<th>Returning Date</th>
												<th>Seats</th>
												<th>Customer(Requester)</th>												
												<th>Supplier</th>	
												<th>Status</th>											
											</tr>
										</thead>
										<tbody id="grid">											                                                                                         									
										</tbody>
									</table>

									

									<div class="row">
										<div class="col-xs-6">
											<div class="dataTables_info" id="info" role="status" aria-live="polite" style="padding-left:20px">									
											</div>
										</div>
										<div class="col-xs-6">
											<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
												<ul class="pagination" id="div_pagination">

													
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<?php include_once('footer.php');?>

 <script src="<?php echo $baseurl;?>/adminarea/script/quotation.js"></script>  
 <link rel="stylesheet" href="../calendar/base/jquery.ui.all.css">
<script src="../calendar/jquery-1.5.1.js"></script>
 <script src="../calendar/jquery.ui.core.js"></script>
 <script src="../calendar/jquery.ui.widget.js"></script>
 <script src="../calendar/jquery.ui.datepicker.js"></script> 
 <script>
	$(function() 
	{
		
		$("#dt_date_from" ).datepicker();
		$("#dt_date_to" ).datepicker();
		$("#dt_date_from" ).datepicker("option", "dateFormat","dd-mm-yy");
		$("#dt_date_to" ).datepicker("option", "dateFormat","dd-mm-yy");
	});
</script>