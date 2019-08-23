
        <style>
		.thank-you-note
		{
			padding:0 !important
		}
		.thank-you-note p
		{
			margin-bottom:0 !important;
		}
		.thank-you-note span
		{
			margin-right:20px;
		}
		.pull-right
		{
			float:right;
			width:100%;
			text-align:right;
		}
		.center
		{
			text-align:center;
		}
		.thank-you-note span
		{
			margin-right:0 !important;
		}
		.thank-you-note span .fa
		{
			margin-right:20px;
		}

		.table {
			border: 1px solid #ccc;
		}

		.table th
		{
		  border-bottom:1px solid #ccc;
		  border-top:none !important;
		  padding:0;
		  /* background:#666;
		  color:#fff; */
		}
		.table td
		{
			border-bottom:1px solid #ccc;
			border-top:none !important;
			padding:6px !important;
		}
		.table tr td label
		{
			font-weight:normal !important;
		}
		
		.table-responsive
		{
			overflow-x:hidden;
		}
		.traveler-info
		{
			margin-top:50px;
		}
		.t-info-heading
		{
			margin:0 0 20px 0 !important;
			border-bottom: 4px solid #ccc;
		}
		.t-info-heading span
		{
			color:#ccc;
			border-bottom:none;
		}
		.t-info-heading span 
		{
			color: #333;
			border-bottom: none;
			font-size: 15px;
			font-weight: normal;
		}
		.t-info-heading span .fa
		{
			font-size:24px !important;
		}
		.table th h5
				{
					font-size:16px !important;
				}
				.table td
				{
					font-size:14px !important;
				}
		    @media print
			{    
				#top-bar,#mynavbar,#cover-thank-you,#newsletter-1,#newsletter-2,#footer,#editor
				{
					display: none !important;
				}
			   .traveler-info
				{
					top:0 !important;
					margin-top:200px;
					position:absolute !important;
				}
				.innerpage-section-padding
				{
					padding-top:0 !important;
				}
				.thank-you-note span .fa
				{
					margin-right:20px;
				}
				.center
				{
					text-align:center;
				}								
				.thank-you-note
				{
					 border-bottom:1px solid #000;
				}
				
				.traveler-info table tr th
				{
						border-bottom:1px solid #000 !important;
						border-top:none !important;
						 padding:0;
						 background:#666 !important;
		                color:#fff;
				}
				.traveler-info table tr td
				{
						border-bottom:1px solid #ccc !important;
						border-top:none !important;
						 padding:0;
				}
				table th
				{
					background:#666666 !important;
				}
				.t-info-heading span 
				{
						color: #333;
						border-bottom: none;
						font-size: 15px;
						font-weight: normal;
				}
				.t-info-heading span .fa
				{
					font-size:24px !important;
				}
				.table th h5
				{
					font-size:16px !important;
				}
				.table td
				{
					font-size:14px !important;
				}
				 @page {
    size: letter portrait;
    padding-left: 5in;
    padding-right: 0.25in;
    padding-top: 1in;
  }
			}
			.thank-you-note:before
			{
			  display:none;
			}
		</style>
        <!--================= PAGE-COVER ================-->
        <section class="page-cover" id="cover-thank-you">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Thank You</h1>
                        <ul class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li class="active">Thank You</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--==== INNERPAGE-WRAPPER =====-->
        <section class="innerpage-wrapper">
        	<div id="thank-you" class="innerpage-section-padding">
        		<div class="container">
        			<div class="row">

                    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-side">
                        	<div class="space-right">
								<hr/>
							    <div class="thank-you-note col-md-12 col-xs-12 col-sm-12 center">
								   <!-- <h3><span><i class="fa fa-plane"></i>OXY</span> TRA</h3> -->
								   <h3><span>E-TICKET</span></h3>
								    <p>&nbsp;</p>
								</div>
                            	<div class="thank-you-note col-md-6 col-xs-6 col-sm-6">
                                   <!-- <h3><?php echo $setting[0]["site_title"];?></h3> logo -->
								   <?php 
								 	if($details[0]["type"]=="B2B") {
								   ?>
									<h3><?php echo $details[0]["name"];?></h3> 
									<p><span><i class="fa fa-envelope"></i></span> <?php echo $details[0]["email"];?></p>
									<p><span><i class="fa fa-phone"></i></span><?php echo $details[0]["mobile"];?></p>
									<p><span><i class="fa fa-map-marker"></i></span><?php echo $details[0]["address"];?></p>
								   <?php 
									} else { ?>
									<img src='<?php echo base_url(); ?>upload/<?php echo $setting[0]["logo"]; ?>' alt='<?php echo $setting[0]["acc_name"]; ?>' style="width: 85px; height: 30px;"/><h3><?php echo $setting[0]["acc_name"];?></h3> 
									<p><span><i class="fa fa-envelope"></i></span> <?php echo $setting[0]["email"];?></p>
									<p><span><i class="fa fa-phone"></i></span><?php echo $setting[0]["phone_no"];?></p>
									<p><span><i class="fa fa-map-marker"></i></span><?php echo $setting[0]["address"];?></p>							   
								   <?php }
								   ?>
                                </div><!-- end thank-you-note -->
								
								<div class="thank-you-note col-md-6 col-xs-6 col-sm-6">
                                   <!-- <h3 class="pull-right"><?php echo $details[0]["name"];?></h3>  -->
								   <h3 class="pull-right"><?php echo $details[0]["first_name"].' '.$details[0]["last_name"];?></h3>
								   <p class="pull-right"><i class="fa fa-calendar"></i></span> <?php echo date("d-m-Y",strtotime($details[0]["date"]));?></p>
								   <p class="pull-right"><i class="fa fa-envelope"></i></span> <?php echo $details[0]["email"];?></p>
								   <p class="pull-right"><i class="fa fa-phone"></i></span><?php echo $details[0]["mobile"];?></p>
								   
                                </div><!-- end thank-you-note -->
                                                           
                               <div class="traveler-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                               		<h4 class="t-info-heading"><span><i class="fa fa-info-circle"></i></span><?php echo $details[0]["source"]." To ".$details[0]["destination"];?>
									<span>
									<?php
									if($details[0]["trip_type"]=="ONE") echo "ONE WAY"; 
									if($details[0]["trip_type"]=="ROUND") echo "ROUND TRIP"; 
									?>
									</span>
									
									<span style="text-align:right">
									  
									 
									</span>
									</h4>
                               		<div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
												  <tr>
                                                	<th style="width: 10%;"><h5>SI No.</h5></th>
                                                    <th style="width: 60%;"><h5>Passenger Name</h5></th>
													<!-- <th><h5>Age</h5></th>
													<th><h5>Mobile No.</h5></th>
													<th><h5>Email</h5></th> -->
													<th style="width: 15%;"><h5>PNR</h5></th>
													<th style="width: 15%;"><h5>Ticket #</h5></th>
                                                </tr>
												<?php
												$ctr=1;
												foreach($details as $key=>$value)
												{
													?>
													<tr>
                                                	<td><?php echo $ctr;?></td>
                                                    <td><?php echo $details[$key]["prefix"]." ".$details[$key]["first_name"]." ".$details[$key]["last_name"] ?></th>																										
													<!-- <td><?php echo $details[$key]["age"];?></td>
													<td><?php echo $details[$key]["mobile_no"];?></td>
													<td><?php echo $details[$key]["cemail"];?></td> -->
													<td><?php echo $details[0]["status"]==='APPROVED' ? $details[$key]["pnr"] : '';?></td>
													<td><?php echo $details[0]["status"]==='APPROVED' ? $details[$key]["airline_ticket_no"] : '';?></td>
                                                </tr>
													<?php
													$ctr++;
												}												
												?>												
                                        	</tbody>
                                        </table>
                               		</div>	
                                    <?php if($details[0]["trip_type"]=="ONE") 
									{
										$dateDiff = intval((strtotime($details[0]["arrival_date_time"])-strtotime($details[0]["departure_date_time"]))/60);?>
									<!-- <h4 class="t-info-heading"><span><i class="fa fa-plane" ></i>&nbsp;&nbsp;GOING</span></h4> -->
									<!-- <div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
                                        		
												  <tr>
                                                	<th><h5>Dep</h5></th>
													<th><h5>From</h5></th>
													<th><h5>To</h5></th>
													<th><h5>Duration</h5></th>													
													<th><h5>Airline</h5></th>
													<th><h5>Class</h5></th>
													<th><h5>Flight No.</h5></th>
													<th><h5>D. Term</h5></th>
													<th><h5>A. Term</h5></th>	
                                                </tr>
												
													<tr>
                                                	<td><?php echo date("jS M y",strtotime($details[0]["departure_date_time"]))." ( ".date("h:i a",strtotime($details[0]["departure_date_time"]))." )";?></td>                                                    
													<td><?php echo $details[0]["source"];?></td>
                                                    <td><?php echo $details[0]["destination"];?></td>													
													<td><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></th>													
													<td><?php echo $details[0]["airline"];?></td>
													<td><?php echo $details[0]["class"];?></td>
													<td><?php echo $details[0]["flight_no"];?></td>
													<td><?php echo $details[0]["terminal"];?></td>
													<td><?php echo $details[0]["terminal1"];?></td>
                                                </tr>
																							
                                        	</tbody>
                                        </table>
                               		</div>	 -->

									<h4 class="t-info-heading"><span><i class="fa fa-plane" ></i>&nbsp;&nbsp;Itinerary</span></h4>
									<div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
                                        		
												<tr>
                                                	<th><h5>Flight</h5></th>
													<th><h5>Departure</h5></th>
													<th><h5>Arrival</h5></th>
													<th><h5>Status / Flight Duration</h5></th>
                                                </tr>
												<tr>
													<td style="padding: 10px;">
														<div style="width: 10%;display: inline-block;">
															<img style="width: 35px; height: 35px;" src="<?php echo base_url(); ?>upload/thumb/<?php echo $details[0]["image"]; ?>" alt="<?php echo $details[0]["airline"];?>">
														</div>
														<div style="width: 80%; display: inline-block; float: right;">
															<div style="">
																<?php echo $details[0]["airline"];?>
																<?php echo $details[0]["flight_no"];?>
															</div>
															<div style="">
																<?php echo $details[0]["class"];?>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo $details[0]["source"];?></div>
																<div style="margin: 0px 15px;"><?php echo $details[0]["terminal"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($details[0]["departure_date_time"]))." (".date("H:i",strtotime($details[0]["departure_date_time"])).")";?>
																</div>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo $details[0]["destination"];?></div>
																<div style="margin: 0px 15px;"><?php echo $details[0]["terminal1"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($details[0]["arrival_date_time"]))." (".date("H:i",strtotime($details[0]["arrival_date_time"])).")";?>
																</div>
															</div>
														</div>
													</td>


                                                	<td><?php echo $details[0]["status"];?><br/><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></td>

													<!-- <td><?php echo $details[0]["source"];?></td>
                                                    <td><?php echo $details[0]["destination"];?></td>													 -->
													<!-- <td><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></th>
													<td><?php echo $details[0]["airline"];?></td>
													<td><?php echo $details[0]["class"];?></td>
													<td><?php echo $details[0]["flight_no"];?></td>
													<td><?php echo $details[0]["terminal"];?></td>
													<td><?php echo $details[0]["terminal1"];?></td> -->
                                                </tr>
																							
                                        	</tbody>
                                        </table>
                               		</div>	

				                    <?php 
									}
									?>
									
									<?php if($details[0]["trip_type"]=="ROUND")
									{
										$dateDiff = intval((strtotime($details[0]["arrival_date_time"])-strtotime($details[0]["departure_date_time"]))/60);
										$dateDiff1 = intval((strtotime($details[0]["arrival_date_time1"])-strtotime($details[0]["departure_date_time1"]))/60);
										?>									
									<h4 class="t-info-heading"><span><i class="fa fa-plane" ></i>&nbsp;&nbsp;Itinerary</span></h4>
									<div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
                                        		
												<tr>
                                                	<th><h5>Flight</h5></th>
													<th><h5>Departure</h5></th>
													<th><h5>Arrival</h5></th>
													<th><h5>Status / Flight Duration</h5></th>
                                                </tr>
												<tr>
													<td style="padding: 10px;">
														<div style="width: 10%;display: inline-block;">
															<img style="width: 35px; height: 35px;" src="<?php echo base_url(); ?>upload/thumb/<?php echo $details[0]["image"]; ?>" alt="<?php echo $details[0]["airline"];?>">
														</div>
														<div style="width: 80%; display: inline-block; float: right;">
															<div style="">
																<?php echo $details[0]["airline"];?>
																<?php echo $details[0]["flight_no"];?>
															</div>
															<div style="">
																<?php echo $details[0]["class"];?>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo $details[0]["source"];?></div>
																<div style="margin: 0px 15px;"><?php echo $details[0]["terminal"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($details[0]["departure_date_time"]))." (".date("H:i",strtotime($details[0]["departure_date_time"])).")";?>
																</div>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo $details[0]["destination"];?></div>
																<div style="margin: 0px 15px;"><?php echo $details[0]["terminal1"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($details[0]["arrival_date_time"]))." (".date("H:i",strtotime($details[0]["arrival_date_time"])).")";?>
																</div>
															</div>
														</div>
													</td>


                                                	<td><?php echo $details[0]["status"];?><br/><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></td>

													<!-- <td><?php echo $details[0]["source"];?></td>
                                                    <td><?php echo $details[0]["destination"];?></td>													 -->
													<!-- <td><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></th>
													<td><?php echo $details[0]["airline"];?></td>
													<td><?php echo $details[0]["class"];?></td>
													<td><?php echo $details[0]["flight_no"];?></td>
													<td><?php echo $details[0]["terminal"];?></td>
													<td><?php echo $details[0]["terminal1"];?></td> -->
                                                </tr>
												<tr>
													<td style="padding: 10px;">
														<div style="width: 10%;display: inline-block;">
															<img style="width: 35px; height: 35px;" src="<?php echo base_url(); ?>upload/thumb/<?php echo $details[0]["image1"]; ?>" alt="<?php echo $details[0]["airline1"];?>">
														</div>
														<div style="width: 80%; display: inline-block; float: right;">
															<div style="">
																<?php echo $details[0]["airline1"];?>
																<?php echo $details[0]["flight_no1"];?>
															</div>
															<div style="">
																<?php echo $details[0]["class1"];?>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo $details[0]["source1"];?></div>
																<div style="margin: 0px 15px;"><?php echo $details[0]["terminal1"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($details[0]["departure_date_time1"]))." (".date("H:i",strtotime($details[0]["departure_date_time1"])).")";?>
																</div>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo $details[0]["destination1"];?></div>
																<div style="margin: 0px 15px;"><?php echo $details[0]["terminal1"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($details[0]["arrival_date_time1"]))." (".date("H:i",strtotime($details[0]["arrival_date_time1"])).")";?>
																</div>
															</div>
														</div>
													</td>


                                                	<td><?php echo $details[0]["status"];?><br/><?php echo intval($dateDiff1/60)." Hours ".($dateDiff1%60)." Minutes"; ?></td>

													<!-- <td><?php echo $details[0]["source"];?></td>
                                                    <td><?php echo $details[0]["destination"];?></td>													 -->
													<!-- <td><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></th>
													<td><?php echo $details[0]["airline"];?></td>
													<td><?php echo $details[0]["class"];?></td>
													<td><?php echo $details[0]["flight_no"];?></td>
													<td><?php echo $details[0]["terminal"];?></td>
													<td><?php echo $details[0]["terminal1"];?></td> -->
                                                </tr>
                                        	</tbody>
                                        </table>
                               		</div>	

									<!-- <div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
                                        		
												  <tr>
                                                	<th><h5>Dep</h5></th>                                                   
													<th><h5>From</h5></th>
													<th><h5>To</h5></th>
													<th><h5>Duration</h5></th>													
													<th><h5>Airline</h5></th>
													<th><h5>Class</h5></th>
													<th><h5>Flight No.</h5></th>
													<th><h5>D. Term</h5></th>
													<th><h5>A. Term</h5></th>
													
                                                </tr>
												
													<tr>
                                                	<td><?php echo date("jS M y",strtotime($details[0]["departure_date_time"]))." ( ".date("h:i a",strtotime($details[0]["departure_date_time"]))." )";?></td>
                                                    
													<td><?php echo $details[0]["source"];?></td>													
													<td><?php echo $details[0]["destination"];?></td>
													<td><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></th>
													<td><?php echo $details[0]["airline"];?></td>
													<td><?php echo $details[0]["class"];?></td>
													<td><?php echo $details[0]["flight_no"];?></td>																										
													<td><?php echo $details[0]["terminal"];?></td>
													<td><?php echo $details[0]["terminal1"];?></td>
                                                </tr>
																							
                                        	</tbody>
                                        </table>
                               		</div> -->
                                   	<!-- <h4 class="t-info-heading"><span><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;RETURN</span></h4>
									<div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
                                        		
												  <tr>
                                                	<th><h5>Dep</h5></th>                                                   
													<th><h5>From</h5></th>
													<th><h5>To</h5></th>
													<th><h5>Duration</h5></th>													
													<th><h5>Airline</h5></th>
													<th><h5>Class</h5></th>
													<th><h5>Flight No.</h5></th>
													<th><h5>D. Term</h5></th>
													<th><h5>A. Term</h5></th>	
                                                </tr>
												
													<tr>
                                                	<td><?php echo date("jS M y",strtotime($details[0]["departure_date_time1"]))." ( ".date("h:i a",strtotime($details[0]["departure_date_time1"]))." )";?></td>                                                    
													<td><?php echo $details[0]["source1"];?></td>													
													<td><?php echo $details[0]["destination1"];?></td>
													<td><?php echo intval($dateDiff1/60)." Hours ".($dateDiff1%60)." Minutes"; ?></th>
													<td><?php echo $details[0]["airline1"];?></td>
													<td><?php echo $details[0]["class1"];?></td>
													<td><?php echo $details[0]["flight_no1"];?></td>
													<td><?php echo $details[0]["terminal2"];?></td>
													<td><?php echo $details[0]["terminal3"];?></td>
                                                </tr>
																							
                                        	</tbody>
                                        </table>
                               		</div>	 -->
				                    <?php 
									}
									?>
									<h4 class="t-info-heading"><span><i class="fa fa-info-circle"></i></span>BOOKING DETAILS</h4>
									<div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
												<tr>
                                                	<th><h5>Booking No.</h5></th>
                                                    <th><h5>Booking Date</h5></th>													
													<th><h5>Price Summary</h5></th>
													<th><h5>Status</h5></th>
                                                </tr>
												
												<tr>
													<td>BK-<?php echo $details[0]["id"];?></td>
													<td><?php echo date("d/m/Y H:i:s",strtotime($details[0]["date"]));?></td>
													<?php
														$qty = $details[0]["qty"];
													?>
													
													<td><label>Ticket Fare (each) : </label><?php echo number_format($details[0]["rate"],2,".",","); ?><br/>
													<label>Qty : </label><?php echo $details[0]["qty"]; ?><br/>													
													<label>Sub Total : </label><?php echo number_format($details[0]["amount"],2,".",","); ?><br/>
													<label>Service Charge : </label><?php echo number_format($details[0]["service_charge"] * $qty,2,".",","); ?><br/>
													<!--<label>SGST : </label><?php echo number_format($details[0]["sgst"],2,".",","); ?><br/>
													<label>CGST : </label><?php echo number_format($details[0]["cgst"],2,".",","); ?><br/>-->
													<!-- <label>GST : </label><?php echo number_format(($details[0]["igst"]+$details[0]["sgst"]+$details[0]["cgst"]),2,".",","); ?><br/> -->
													<label>GST : </label><?php echo number_format(($details[0]["sgst"]+$details[0]["cgst"]) * $qty,2,".",","); ?><br/>
													<label>Grand Total : </label><?php echo number_format($details[0]["total"],2,".",","); ?></td>
													
													<td>            
													   <?php echo $details[0]["status"]."<br>";?>
												  	</td>
												</tr>
                                        	</tbody>
                                        </table>
                               		</div>	
                                    
                                    
                               </div><!-- end traveler-info -->
							   
							    
								
                        	</div><!-- end space-right -->
                        </div><!-- end columns -->
						<?php 
						if(!$options['pdf']) {
						?>
                        <div id="editor">
							<div class="row">
								<form method="POST" action="<?php echo base_url(); ?>search/pdf/<?php echo $details[0]["id"];?>">
									<?php 
								 		if($details[0]["type"]!="B2C") {
								   	?>
									<div class="row" style="margin: 2px 2px 10px 2px;">
										<div class="col-sm-2">
										</div>
										<div class="col-sm-4">
											<label>Disable pricing summary in PDF</label>&nbsp;
											<input type="checkbox" id="showprice" name="showprice" title="Disable pricing summary while generating PDF by clicking DOWNLOAD button"/>
										</div>
										<div class="col-sm-4">
											<label>Extra markup</label>&nbsp;
											<input type="number" id="markup" name="markup" title="Specify extra markup before generating E-TICKET" value="0.00"/>
										</div>
										<div class="col-sm-2">
										</div>
									</div>
									<?php } ?>
									<div class="row">
										<div class="col-sm-4"></div>
										<div class="col-sm-2">
											<button type="button" class="btn btn-orange col-sm-12" onclick="window.print()">PRINT</button>
										</div>
										<div class="col-sm-2">
											<!-- <a href="<?php echo base_url(); ?>search/pdf/<?php echo $details[0]["id"];?>" class="btn btn-orange col-sm-12" id="pdfview">DOWNLOAD</a> -->
											<button type="submit" class="btn btn-orange col-sm-12" title="Download ticket in PDF format">DOWNLOAD</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<?php 
						}
						?>
                    </div><!-- end row -->
        		</div><!-- end container -->
        	</div><!-- end thank-you --> 
        </section><!-- end innerpage-wrapper -->
        
		<?php 
		if(!$options['pdf']) {
		?>
        <!--========================= NEWSLETTER-1 ==========================-->
        <section id="newsletter-1" class="section-padding back-size newsletter"> 
            <div class="container">
                <div class="row">
                	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                        <h2>Subscribe Our Newsletter</h2>
                        <p>Subscibe to receive our interesting updates</p>	
                        <form>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="email" class="form-control input-lg" placeholder="Enter your email address" required/>
                                    <span class="input-group-btn"><button class="btn btn-lg"><i class="fa fa-envelope"></i></button></span>
                                </div>
                            </div>
                        </form>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end newsletter-1 -->
		<?php }?>