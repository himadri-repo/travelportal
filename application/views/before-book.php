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
			.dashboard-listing table td.dash-list-icon
			{
				padding-left: 35px;
                font-size: 13px;
				width:auto !important;

			}
			.invoices td.invoice-text .invoice-info li
			{
				color:#000 !important;
			}
			th
			{
				background:#E8E6E4;
				text-align:center;
			}
			td
			{
				
				text-align:center;
			}
			.table-responsive tr:nth-child(even) {background: #f5f5f5}
            .table-responsive tr:nth-child(odd) {background: #FFF}
			.dashboard-listing table td.dash-list-icon
			{
				padding-left:0 !important;
			}
			.invoices td.invoice-text .invoice-info li:after
			{
				height:0 !important;
			}
		</style>
        <section class="page-cover dashboard">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Ticket Details</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>user">My Account</a></li>
                            <li class="active">Ticket Details</li>
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
                        	<!--<div class="dashboard-heading">
                                <h2>OXY <span>TRA</span></h2>								
                                <p>Hi <?php echo $user_details[0]["name"];?>, Welcome to OXY TRA</p>
                                
                            </div> -->
                        	
                            
                            <div id="dashboard-tabs">                            	                            	
                                <div class="tab-content">
                                	<div id="dsh-dashboard" class="tab-pane in active fade" style="border-top: 1px solid #e6e7e8;">
                                		<div class="dashboard-content" style="padding-top:0">
                                           
											<form action="<?php echo base_url(); ?>search/book/<?php echo $flight[0]["id"];?>" method="POST">
											<input type="hidden" name="source" value="<?php echo $flight[0]["source"]; ?>">
											<input type="hidden" name="destination" value="<?php echo $flight[0]["destination"]; ?>">
											<input type="hidden" name="source1" value="<?php echo $flight[0]["source1"]; ?>">
											<input type="hidden" name="destination1" value="<?php echo $flight[0]["destination1"]; ?>">
											<input type="hidden" name="class" value="<?php echo $flight[0]["class"]; ?>">
											<input type="hidden" name="departure_date_time" value="<?php echo $flight[0]["departure_date_time"]; ?>">
											<input type="hidden" name="arrival_date_time" value="<?php echo $flight[0]["arrival_date_time"]; ?>">
											<input type="hidden" name="flight_no" value="<?php echo $flight[0]["flight_no"]; ?>">
											<input type="hidden" name="terminal" value="<?php echo $flight[0]["terminal"]; ?>">
											<input type="hidden" name="departure_date_time1" value="<?php echo $flight[0]["departure_date_time1"]; ?>">
											<input type="hidden" name="arrival_date_time1" value="<?php echo $flight[0]["arrival_date_time1"]; ?>">
											<input type="hidden" name="flight_no1" value="<?php echo $flight[0]["flight_no1"]; ?>">
											<input type="hidden" name="terminal1" value="<?php echo $flight[0]["terminal1"]; ?>">
											<input type="hidden" name="trip_type" value="<?php echo $flight[0]["trip_type"]; ?>">
											<input type="hidden" name="ticket_no" value="<?php echo $flight[0]["ticket_no"]; ?>">
											<input type="hidden" name="pnr" value="<?php echo $flight[0]["pnr"]; ?>">
											<input type="hidden" name="airline" value="<?php echo $flight[0]["airline"]; ?>">
											<input type="hidden" name="sale_type" value="<?php echo $flight[0]["sale_type"]; ?>">
											<input type="hidden" name="ticket_type" value="<?php echo $flight[0]["ticket_type"]; ?>">
											<input type="hidden" name="price" value="<?php echo $flight[0]["price"]; ?>">
											<input type="hidden" name="markup" value="<?php echo $flight[0]["markup"]; ?>">
											<input type="hidden" name="total" value="<?php echo $flight[0]["total"]; ?>">
											<input type="hidden" name="qty" value="<?php echo $flight[0]["qty"]; ?>">
											<input type="hidden" name="refundable" value="<?php echo $flight[0]["refundable"]; ?>">
											<input type="hidden" name="date" value="<?php echo date("Y-m-d"); ?>">
											
											<div class="dashboard-listing invoices">                                                
													<div class="table-responsive">
														<table class="table table-hover">
															<tbody>
																<tr>
																
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">SI</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Description</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Rate</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Qty</h3></th> 
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Amount</h3></th>
																	
																</tr>
																
																	<tr>
																		<td class="dash-list-icon invoice-icon">1</td>
																		<td class="dash-list-icon invoice-text">
																			<ul class="list-unstyled list-inline invoice-info">
																					<li class="invoice-order">From <?php echo $flight[0]["source_city"]; ?> To <?php echo $flight[0]["destination_city"]; ?> </li><br> 
																					<li class="invoice-order"><?php if($flight[0]["trip_type"]=="ONE") echo $flight[0]["trip_type"]." Way"; if($flight[0]["trip_type"]=="ROUND") echo $flight[0]["trip_type"]." Trip";  ?>  </li> <br> 
																					<?php if($flight[0]["trip_type"]=="ONE") {?>
																					   <li class="invoice-order">Departure Date Time : <?php echo date("jS M y",strtotime($flight[0]["departure_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[0]["departure_date_time"])); ?>)</li><br> 
																					   <li class="invoice-order">Arrival Date Time : <?php echo date("jS M y",strtotime($flight[0]["arrival_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time"])); ?>)</li>
																					<?php } else {?>
																						<li class="invoice-order">Departure Date Time : <?php echo date("jS M y",strtotime($flight[0]["departure_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[0]["departure_date_time"])); ?>)</li><br>  
																						<li class="invoice-order">Arrival Date Time : <?php echo date("jS M y",strtotime($flight[0]["arrival_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time"])); ?>)</li>
																						<li class="invoice-order">Return Departure Date Time : <?php echo date("jS M y",strtotime($flight[0]["departure_date_time1"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[0]["departure_date_time1"])); ?>)</li><br>  																					
																						<li class="invoice-order">Return Arrival Date Time : <?php echo date("jS M y",strtotime($flight[0]["arrival_date_tim"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[0]["arrival_date_tim"])); ?>)</li>
																					<?php } ?>
																			</ul>
																		</td>
																		<td class="dash-list-icon invoice-text"><?php echo number_format($flight[0]["price"],2,".",","); ?></td>
																		<td class="dash-list-icon invoice-text"><?php echo $flight[0]["qty"]; ?></td>
																		<td class="dash-list-icon invoice-text"><?php echo number_format($flight[0]["price"]*$flight[0]["qty"],2,".",","); ?></td>
																		
																	</tr>
																	
																	<tr>
																		<td class="dash-list-icon invoice-icon">2</td>
																		<td  class="dash-list-icon invoice-text">SERVICE CHARGE</td>																																			
																		<td class="dash-list-icon invoice-text"><?php echo number_format($setting[0]["service_charge"],2,".",","); ?></td>
																		<td class="dash-list-icon invoice-text"></td>
																		<td class="dash-list-icon invoice-text"><?php echo number_format($setting[0]["service_charge"],2,".",","); ?></td>
																		
																	</tr>
																	
																	<tr>
																		<td class="dash-list-icon invoice-icon">3</td>
																		<td class="dash-list-icon invoice-text">SGST</td>																																			
																		<td class="dash-list-icon invoice-text"><?php echo $setting[0]["sgst"];?> %</td>	
																		<td class="dash-list-icon invoice-text"></td>
																		<td class="dash-list-icon invoice-text"><?php echo number_format(($setting[0]["service_charge"]*$setting[0]["sgst"]/100),2,".",","); ?></td>
																		
																	</tr>
																	
																	<tr>
																		<td class="dash-list-icon invoice-icon">4</td>
																		<td class="dash-list-icon invoice-text">CGST</td>																																			
																		<td class="dash-list-icon invoice-text"><?php echo $setting[0]["cgst"];?> %</td>	
																		<td class="dash-list-icon invoice-text"></td>
																		<td class="dash-list-icon invoice-text"><?php echo number_format(($setting[0]["service_charge"]*$setting[0]["cgst"]/100),2,".",","); ?></td>
																		
																	</tr>
																	
																	<tr>
																		<td class="dash-list-icon invoice-icon">5</td>
																		<td class="dash-list-icon invoice-text">IGST</td>																																			
																		<td class="dash-list-icon invoice-text"><?php echo $setting[0]["igst"];?> %</td>	
																		<td class="dash-list-icon invoice-text"></td>
																		<td class="dash-list-icon invoice-text"><?php echo number_format(($setting[0]["service_charge"]*$setting[0]["igst"]/100),2,".",","); ?></td>
																		
																	</tr>
																	
																 <tr>
																    <?php $grand_total=($flight[0]["price"]*$flight[0]["qty"])+$setting[0]["service_charge"]+($setting[0]["service_charge"]*$setting[0]["igst"]/100)?>				
																	<td colspan="4" class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Total</h3></td>                                                                
																	<td class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px"><?php echo number_format($grand_total,2,".",","); ?></h3></td>
																</tr>
															</tbody>
														</table>
													</div><!-- end table-responsive -->
												 
												
                                            </div><!-- end invoices -->
                                            <div class="row" style="margin-top:30px">
													<div class="col-md-4 col-md-offset-4">
													<button type="submit" class="btn btn-block btn-orange" style="padding:15px;border: 4px solid #E0CCAC;">Confirm Booking</button>
													</div>
											</div>
											<input type="hidden" name="service_charge" value="<?php echo $setting[0]["service_charge"];?>">
											<input type="hidden" name="sgst" value="<?php echo $setting[0]["service_charge"]*$setting[0]["sgst"]/100;?>">
                                            <input type="hidden" name="cgst" value="<?php echo $setting[0]["service_charge"]*$setting[0]["cgst"]/100;?>">							
											<input type="hidden" name="igst" value="<?php echo $setting[0]["service_charge"]*$setting[0]["igst"]/100;?>">
											<input type="hidden" name="grand_total" value="<?php echo $grand_total;?>">
											</form>
                                        </div><!-- end dashboard-content -->
                                    </div><!-- end dsh-dashboard -->
                                                                                                                                                																		                                                                                                      
                                </div><!-- end tab-content -->
                            </div><!-- end dashboard-tabs -->
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->   
            </div><!-- end contact-us -->
        </section><!-- end innerpage-wrapper -->