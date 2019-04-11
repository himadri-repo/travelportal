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
                    	<h1 class="page-title">Booking Details</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>user">My Account</a></li>
                            <li class="active">Booking Details</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
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
															
															</div>
															
															<div class="row">
															<div class="dashboard-listing invoices">
															
																<div class="table-responsive">
																	<table class="table table-hover">
																		<tbody>
																			<tr>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">SI</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Booking No</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Booking Date</h3></th>
																				
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">PNR</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Journey</h3></th>																			
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Type</h3></th> 																																								
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Total Fare</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Seats</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Status</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:left"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:left">Customer</h3></th>																			
																			</tr>
																			 <?php
																			 if(is_array($sale_order))
																			 {
																			  foreach($sale_order as $key=>$value)
																			  {
																			  ?>                                             																							
																				<tr>
																					<td class="dash-list-icon invoice-icon"><center><?php echo $ctr; ?></center></td>
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["booking_id"];?></td>
																					<td class="dash-list-icon invoice-icon"><?php echo date("d/m/Y",strtotime($sale_order[$key]["date"]))."<br>".date("h:i:s",strtotime($sale_order[$key]["date"])); ?></td>
																					
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["pnr"]; ?></td>
																					<td class="dash-list-icon invoice-icon"><?php  echo $sale_order[$key]["source_city"]." <br> ".$sale_order[$key]["destination_city"];?></td>
																					<td class="dash-list-icon invoice-icon"><?php if($sale_order[$key]["trip_type"]=="ONE") {echo $sale_order[$key]["trip_type"]." WAY";}else{ echo $sale_order[$key]["trip_type"]." TRIP";} ?></td>
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["total"]; ?></td>																				
																					<td class="dash-list-icon invoice-icon"><?php echo $sale_order[$key]["qty"]; ?></td>
																					<td class="dash-list-icon invoice-icon">
																					<?php 																					
																						echo $sale_order[$key]["status"]."<br>";																																																															
																					?>
																					</td>
																					
																					<td class="dash-list-icon invoice-icon">
																					<?php 																					
																					if($sale_order[$key]["seller_id"]!=$sale_order[$key]["customer_id"])
																					{
																						?>
																						 <a class="btn btn-orange" href="#" style="cursor:pointer;font-size:11px">OXYTRA</a>
																						<?php
																					}
                                                                                    else
																					{
																						?>
																						<a class="btn btn-orange" href="<?php echo base_url() ?>search/thankyou/<?php echo $sale_order[$key]["booking_id"]; ?>" style="cursor:pointer;font-size:11px">View</a>
																						<?php
																					}																						
																					?>
																					 </td>
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
		
		
		
		<div id="approve-booking-cancel" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Approve Cancellation</h3>
                    </div>
                    
                    <div class="modal-body">
                        <form method="POST" action="" id="frm_approve_cancel">
                        	<div class="form-group">
                        		<label>Cancellation Amount</label>
								
                            	<input type="number" name="supplier_cancel_charge" id="supplier_cancel_charge" class="form-control" required>
                            </div>                                                    	
                            <button type="submit" class="btn btn-orange">Submit</button>
                        </form>
                    </div>
					
                </div>
            </div>
        </div>	

        <div id="update_pnr" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Update PNR</h3>
                    </div>
                    
                    <div class="modal-body">
                        <form method="POST" action="<?php echo base_url();?>user/updatepnr" id="frm_update_pnr">
                        	<div class="form-group">
                        		<label>Enter PNR</label>
								<input type="hidden" name="hid_refrence_booking_id" id="hid_refrence_booking_id">
                            	<input type="text" name="pnr" id="pnr" class="form-control" required>
                            </div>                                                    	
                            <button type="submit" class="btn btn-orange">Submit</button>
                        </form>
                    </div>
					
                </div>
            </div>
        </div>		