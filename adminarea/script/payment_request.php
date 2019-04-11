<?php 
include_once('header.php');
?>
<title>Payment Request | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>				
								Payment Request							
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
									<label class="col-sm-3 control-label no-padding-right" for="form-field-username">Search With Name </label>									
									<div class="col-sm-6" id="div_txt_name">
									  <span class="block input-icon input-icon-left">
									  <input class="col-xs-12 col-sm-12" type="text" name="txt_search" id="txt_search" placeholder="Search..." value="" >														 
									</div>
								</div>
					
								<div style="background:#EFF3F8;border:1px solid #ccc" >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>SI No.</th>	                                                	                                                																							           
                                                <th>Name</th>
												<th>Request Date</th>
												<th>Payment Type</th>
												<th>Payment Details</th>
												<th>Amount</th>												
												<th>Status</th>	
                                                <th>Pay</th>												
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

 <script src="<?php echo $baseurl;?>/adminarea/script/payment_request.js"></script>  			
 <link rel="stylesheet" href="../calendar/base/jquery.ui.all.css">
 <script src="../calendar/jquery-1.5.1.js"></script>
 <script src="../calendar/jquery.ui.core.js"></script>
 <script src="../calendar/jquery.ui.widget.js"></script>
 <script src="../calendar/jquery.ui.datepicker.js"></script> 
 <script>
	$(function() 
	{
		$("#menu_payment_request").addClass("active");
		$( "#dt_date_from" ).datepicker();
		$( "#dt_date_to" ).datepicker();
		$( "#dt_date_from" ).datepicker("option", "dateFormat","dd-mm-yy");
		$( "#dt_date_to" ).datepicker("option", "dateFormat","dd-mm-yy");
	});
</script> 