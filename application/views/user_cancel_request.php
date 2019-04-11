        <style>
			@media (min-width: 768px)
			{
			.custom-modal .modal-dialog 
			{
			width: 800px;
			}
			}
			.invoices td.invoice-text .invoice-info
			{
				font-size:13px !important;
			}
			.table th.dash-list-icon
			{
				text-align:center !important;
			}
			.dashboard-listing .dash-listing-heading
			{
				font-size:15px;
			}
			.table-responsive tr th
			{
				background:#E8E6E4;
				text-align:center;
			}
			.table-responsive tr:nth-child(even) {background: #f5f5f5}
            .table-responsive tr:nth-child(odd) {background: #FFF}
			.dashboard-listing table td.dash-list-icon 
			{
				width: auto; 
				padding-left:0;
				font-size:12px;
			}
			h3 span
			{
				font-size:15px;
				font-weight:normal;
			}
		</style>
		<?php
		 $ctr=1;
		 $total=0;
		?>
        <section class="page-cover dashboard">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Cancel Request</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>user">My Account</a></li>
                            <li class="active">Cancel Request</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
                
        <section class="innerpage-wrapper">
        	<div id="dashboard" class="innerpage-section-padding" style="padding-top:0">
                <div class="container">
                    <div class="row">
					        
                            <div class="col-xs-12 col-sm-12 col-md-12">                        	                            
                                            <div id="dashboard-tabs">                            	                            	
												<div class="tab-content">
													<div id="dsh-dashboard" class="tab-pane in active fade" style="border-top: 1px solid #e6e7e8;">
													    
														<div class="dashboard-content" style="padding-top:0">
														    <div class="row" style="margin-top:40px">
														    <!--<div class="col-sm-3 col-md-3">
															<a href="<?php echo base_url()?>user/add-oneway-ticket" class="btn btn-orange">Add One Way Ticket</a>
															</div>
															
															<div class="col-sm-3 col-md-3">
															<a href="<?php echo base_url()?>user/add-return-ticket" class="btn btn-orange">Add Return Ticket</a>
															</div>-->
															</div>
															
															<div class="row">
															<div class="dashboard-listing invoices">
																<!--<h3 class="dash-listing-heading">Invoices</h3>-->
																<div class="table-responsive">
																	<table class="table table-hover">
																		<tbody>
																			<tr>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">SI</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Booking No</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">PNR</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Request Date</h3></th>																																																		
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Total Fare</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Reason</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Status</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Action</h3></th>																				
																			</tr>
																			 <?php
																			 if(is_array($sale_order))
																			 {
																			  foreach($sale_order as $key=>$value)
																			  {
																			  ?>                                             																							
																				<tr>
																					<td class="dash-list-icon invoice-icon"><center><?php echo $ctr; ?></center></td>
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["id"];?></td>
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["pnr"]; ?></td>
																					<td class="dash-list-icon invoice-icon"><?php echo date("d/m/Y",strtotime($sale_order[$key]["date"]))."<br>".date("h:i:s",strtotime($sale_order[$key]["date"])); ?></td>
																																																		
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["total"]; ?></td>																				
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["reason_for_cancellation"]; ?></td>
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["status"]; ?></td>
																					<td class="dash-list-icon invoice-icon">
																					
																					<?php if($sale_order[$key]["supplier_cancel_request"]==0){ ?>
																						<a class="btn btn-orange" href="<?php echo base_url() ?>user/edit-booking/<?php echo $sale_order[$key]["id"]; ?>" style="cursor:pointer;font-size:11px">Approve</a>																				
																					<?php } ?>
																					<a class="btn btn-orange" href="<?php echo base_url() ?>search/thankyou/<?php echo $sale_order[$key]["id"]; ?>" style="cursor:pointer;font-size:11px">View</a></td>
																				</tr>	
                                                               
																				<?php 
																				$ctr++;
																			  }
																			 }
																			?>                                               
																			</tbody>
																		</table>
																	</div><!-- end table-responsive -->
															</div><!-- end invoices -->
															</div>																																		
														</div><!-- end dashboard-content -->
													</div><!-- end dsh-dashboard -->
																																																																																				  
												</div><!-- end tab-content -->
										    </div><!-- end dashboard-tabs -->
                            </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->   
            </div><!-- end contact-us -->
        </section><!-- end innerpage-wrapper -->						