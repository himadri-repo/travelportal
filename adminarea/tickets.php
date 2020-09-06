<?php 
include_once('header.php');
if(!in_array(5,$auth))
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
<title>Tickets | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>				
								Tickets Table								
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
						<form action="bulk_update.php" method="post" onsubmit="return validate_ids()">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 table-header" id="result">
											</div>
										</div>
										<div class="row">
											<div class="form-group has-info col-xs-12 col-sm-12 col-md-12" style="float:left;margin-top:15px;">
												<div class="col-sm-2" id="div_txt_name">
													<!--<span class="block input-icon input-icon-left">
													<input class="col-xs-12 col-sm-12" type="text" name="txt_search" id="txt_search" placeholder="Search..." value="" >-->
													<select  class="col-xs-12 col-sm-12" name="field" id="field">
														<option value="t.approved">Status</option>
														<option value="u.id">Agent ID</option>
														<option value="t.data_collected_from">Data Source</option>
													</select>
												</div>
												
												<div class="col-sm-2 col-xs-2 col-md-2" id="div_status">
													<select  class="col-xs-12 col-sm-12" name="status" id="status">	
														<option value="">All</option>
														<option value="0">Pending</option>
														<option value="1">Approved</option>
														<option value="2">Rejected</option>
														<option value="3">Freezed</option>
													</select>
												</div> 

												<div class="col-sm-2 col-xs-2 col-md-2" id="div_data_collected_from" style="display:none">
													<select  class="col-xs-12 col-sm-12" name="data_collected_from" id="data_collected_from">	
														<option value="">All</option>
														<option value="airiq">AirIQ</option>
														<option value="e2f">Ease2Fly</option>
														<option value="doshi">Doshi Travels</option>
														<option value="mair">Mittal Air</option>
														<option value="moh">Make Our Holiday</option>
														<option value="mpt">Metropolitan Travels</option>
														<option value="tmz">Tripmaza</option>
														<option value="indr">Inder Tours</option>
														<option value="cheap">Cheap FD</option>
														<option value="tudan">Travel Udaan</option>
														<option value="sng">SS Travels</option>
														<option value="rtt">Rose Tours & Travels</option>
													</select>
												</div> 												
												
												<div class="col-sm-2 col-xs-2 col-md-2" id="div_user" style="display:none">
													<select  class="col-xs-12 col-sm-12" name="user_id" id="user_id">
														<option value="">Select Agent</option>
														<?php 
														$sql="SELECT * FROM user_tbl WHERE is_supplier=1";
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
												
												<div class="col-sm-2 col-xs-2 col-md-2" id="div_date_from">
													<input type="text"  id="dt_date_from" name="dt_date_from" placeholder="Date From" class="col-xs-12 col-sm-12 dpd3">									
												</div>
										
												<div class="col-sm-2 col-xs-2 col-md-2" id="div_date_to">
													<input type="text"  id="dt_date_to" name="dt_date_to" placeholder="Date To" class="col-xs-12 col-sm-12 dpd3">														
												</div>
												
												<div class="col-sm-2 col-xs-2 col-md-2" >
													<button type="button" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_search">															
														<span class="bigger-110">Search</span>
													</button>
												</div>

												<div class="col-sm-2 col-xs-2 col-md-2" >
													<button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_search">															
														<span class="bigger-110">Bulk Update</span>
													</button>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
												<div style="margin: 0px 3px 5px 0px; float:right"><input type="checkbox" id="emptystock" name="emptystock" value="1"/>Show empty tickets ?</div>
											</div>
										</div>
										<div style="background:#EFF3F8;border:1px solid #ccc" >
											
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th><input type="checkbox" id="chk_all"></th>	
														<th>T.No.<br/>Source<br/>Last.Sync</th>
														<th>PNR</th>
														<th>Type</th>
														<th>Going Date</th>
														<th>Returning Date</th>
														<th>Journey</th>												
														<th>Rate</th>
														<th>Seats</th>
														<th>Agent</th>
														<th>Avl</th>	
														<th>Status</th>												
														<th>Edit</th>												
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
						</form>
									
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<?php include_once('footer.php');?>

 <script src="<?php echo $baseurl;?>/adminarea/script/tickets.js"></script>  
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