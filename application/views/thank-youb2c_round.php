
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

			.overlay-document {
				top: 0%;
				width: 100%;
				height: 100%;
				position: absolute;
				left: 0%;
				background-color: #c1c1c1;
			}

			.overlay-rotated-text {
				position: relative;
				top: 25%;
				left: 10%;
				transform: rotate(-45deg);
				color: #ff00008c;
				z-index: 9;
				font-size: 15em;
				font-weight: 900;				
			}

			.price-section {
				list-style: none;
				padding-left: 0px;
			}

			.key {
				width: 40%;
				display: inline-block;
			}
			.key span {
				font-weight: 800;
			}
			.value {
				width: 30%;
				display: inline-block;
				text-align: right;
			}
			.grand-total {
				font-weight: 700;
				border-top: 1px solid #cdcdcd;
				border-bottom: 1px solid #cdcdcd;
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

		<?php
			$tkt_status = (($ob_details[0]["customer_status"] === $ob_details[0]["status"]) ? $ob_details[0]["status"] : "PENDING");
        ?>
        <!--==== INNERPAGE-WRAPPER =====-->
        <section class="innerpage-wrapper">
        	<div id="thank-you" class="innerpage-section-padding">
        		<div class="container">
        			<div class="row">

                    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-side">
							<?php if($tkt_status === 'PENDING') { ?>
							<div class="overlay-document">
								<div class="overlay-rotated-text">PENDING</div>
							</div>
							<?php } ?>
                        	<div class="space-right">
								<hr/>
							    <div class="thank-you-note col-md-12 col-xs-12 col-sm-12 center">
								   <!-- <h3><span><i class="fa fa-plane"></i>OXY</span> TRA</h3> -->
								   <h3><span>E-TICKET</span></h3>
								    <p>&nbsp;</p>
								</div>
                            	<div class="thank-you-note col-md-6 col-xs-6 col-sm-6" style="height: 160px;">
                                   <!-- <h3><?php echo $setting[0]["site_title"];?></h3> logo -->
								   <?php 
								 	if($ob_details[0]["type"]=="B2B") {
								   ?>
									<h3><?php echo $ob_details[0]["name"];?></h3> 
									<p><span><i class="fa fa-envelope"></i></span> <?php echo $ob_details[0]["email"];?></p>
									<p><span><i class="fa fa-phone"></i></span><?php echo $ob_details[0]["mobile"];?></p>
									<p><span><i class="fa fa-map-marker"></i></span><?php echo $ob_details[0]["address"];?></p>
								   <?php 
									} else { ?>
									<img src='<?php echo base_url(); ?>upload/<?php echo $setting[0]["logo"]; ?>' alt='<?php echo $setting[0]["acc_name"]; ?>' style="width: 85px; height: 30px;"/><h3><?php echo $setting[0]["acc_name"];?></h3> 
									<p><span><i class="fa fa-envelope"></i></span> <?php echo $setting[0]["email"];?></p>
									<p><span><i class="fa fa-phone"></i></span><?php echo $setting[0]["phone_no"];?></p>
									<p><span><i class="fa fa-map-marker"></i></span><?php echo $setting[0]["address"];?></p>
								   <?php }
								   ?>
                                </div><!-- end thank-you-note -->
								
								<div class="thank-you-note col-md-6 col-xs-6 col-sm-6" style="height: 160px;">
								<h3 class="pull-right"><?php echo $ob_details[0]["first_name"].' '.$ob_details[0]["last_name"];?></h3>
								<p class="pull-right"><span><i class="fa fa-calendar"></i></span> <?php echo date("d-m-Y",strtotime($ob_details[0]["date"]));?></p>
								<p class="pull-right"><span><i class="fa fa-envelope"></i></span> <?php echo $ob_details[0]["cemail"];?></p>
								<p class="pull-right"><span><i class="fa fa-phone"></i></span><?php echo $ob_details[0]["mobile_no"];?></p>
								<p class="pull-right"><span><i class="fa fa-map-marker"></i></span>&nbsp;</p>
								   
                                </div><!-- end thank-you-note -->
                                                           
                               <div class="traveler-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                               		<!-- Outbound direction -->
									<h4 class="t-info-heading"><span><i class="fa fa-info-circle"></i></span><?php echo $ob_details[0]["source"]." To ".$ob_details[0]["destination"];?>
									<span>
									<?php
									// $direction = "ONE WAY";
									// if(count($ob_details)>0 && $ob_details[0]["trip_type"]=="ONE") $direction = "ONE WAY"; 
									// if(count($ib_details)>0 && $ib_details[0]["trip_type"]=="ONE") $direction = "ROUND TRIP"; 
									?>
									</span>
									<span style="text-align:right"></span>
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
												foreach($ob_details as $key=>$value)
												{
													if($ob_details[$key]['customer_status']=='PENDING' || $ob_details[$key]['customer_status']=='APPROVED') {
														$passenger_type = intval($ob_details[$key]['passenger_type']);
														?>
														<tr>
															<td><?php echo $ctr;?></td>
															<?php 
															if($passenger_type === 3) { ?>
																<td><?php echo $ob_details[$key]["prefix"]." ".$ob_details[$key]["first_name"]." ".$ob_details[$key]["last_name"] . "(Infant)"?></th>
															<?php }
															else { ?>
																<td><?php echo $ob_details[$key]["prefix"]." ".$ob_details[$key]["first_name"]." ".$ob_details[$key]["last_name"] ?></th>
															<?php } ?>
															
															<!-- <td><?php echo $ob_details[$key]["age"];?></td>
															<td><?php echo $ob_details[$key]["mobile_no"];?></td>
															<td><?php echo $ob_details[$key]["cemail"];?></td> -->
															<td><?php echo ($ob_details[0]["status"]==='APPROVED' && $ob_details[0]['customer_status']=='APPROVED') ? $ob_details[$key]["pnr"] : '';?></td>
															<td><?php echo ($ob_details[0]["status"]==='APPROVED' && $ob_details[0]['customer_status']=='APPROVED') ? $ob_details[$key]["airline_ticket_no"] : '';?></td>
														</tr>
														<?php
														$ctr++;
													}
												}
												?>												
                                        	</tbody>
                                        </table>
                               		</div>

									<!-- Inbound direction -->
									<h4 class="t-info-heading"><span><i class="fa fa-info-circle"></i></span><?php echo $ib_details[0]["source"]." To ".$ib_details[0]["destination"];?>
									<span>
									<?php
									// $direction = "ONE WAY";
									// if(count($ob_details)>0 && $ob_details[0]["trip_type"]=="ONE") $direction = "ONE WAY"; 
									// if(count($ib_details)>0 && $ib_details[0]["trip_type"]=="ONE") $direction = "ROUND TRIP"; 
									?>
									</span>
									<span style="text-align:right"></span>
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
												foreach($ib_details as $key=>$value)
												{
													if($ib_details[$key]['customer_status']=='PENDING' || $ib_details[$key]['customer_status']=='APPROVED') {
														$passenger_type = intval($ib_details[$key]['passenger_type']);
														?>
														<tr>
															<td><?php echo $ctr;?></td>
															<?php 
															if($passenger_type === 3) { ?>
																<td><?php echo $ib_details[$key]["prefix"]." ".$ib_details[$key]["first_name"]." ".$ib_details[$key]["last_name"] . "(Infant)"?></th>
															<?php }
															else { ?>
																<td><?php echo $ib_details[$key]["prefix"]." ".$ib_details[$key]["first_name"]." ".$ib_details[$key]["last_name"] ?></th>
															<?php } ?>
															
															<!-- <td><?php echo $ib_details[$key]["age"];?></td>
															<td><?php echo $ib_details[$key]["mobile_no"];?></td>
															<td><?php echo $ib_details[$key]["cemail"];?></td> -->
															<td><?php echo ($ib_details[0]["status"]==='APPROVED' && $ib_details[0]['customer_status']=='APPROVED') ? $ib_details[$key]["pnr"] : '';?></td>
															<td><?php echo ($ib_details[0]["status"]==='APPROVED' && $ib_details[0]['customer_status']=='APPROVED') ? $ib_details[$key]["airline_ticket_no"] : '';?></td>
														</tr>
														<?php
														$ctr++;
													}
												}
												?>												
                                        	</tbody>
                                        </table>
                               		</div>
									

                                    <?php if($ob_details[0]["trip_type"]=="ONE") 
									{
										$dateDiff = intval((strtotime($ob_details[0]["arrival_date_time"])-strtotime($ob_details[0]["departure_date_time"]))/60);?>
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
															<img style="width: 35px; height: 35px;" src="<?php echo base_url(); ?>upload/thumb/<?php echo $ob_details[0]["image"]; ?>" alt="<?php echo $ob_details[0]["airline"];?>">
														</div>
														<div style="width: 80%; display: inline-block; float: right;">
															<div style="">
																<?php echo $ob_details[0]["airline"];?>
																<?php echo $ob_details[0]["flight_no"];?>
															</div>
															<div style="">
																<?php echo $ob_details[0]["class"];?>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo $ob_details[0]["source"];?></div>
																<div style="margin: 0px 15px;">Terminal - <?php echo $ob_details[0]["terminal"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($ob_details[0]["departure_date_time"]))." (".date("H:i",strtotime($ob_details[0]["departure_date_time"])).")";?>
																</div>
															</div>
														</div>
													</td>
													<td style="padding: 10px;">
														<div style="display: inline-block;">
															<div style="">
																<div><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo $ob_details[0]["destination"];?></div>
																<div style="margin: 0px 15px;">Terminal - <?php echo $ob_details[0]["terminal2"];?></div>
																<div style="margin: 0px 15px;">
																	<?php echo date("jS M y",strtotime($ob_details[0]["arrival_date_time"]))." (".date("H:i",strtotime($ob_details[0]["arrival_date_time"])).")";?>
																</div>
															</div>
														</div>
													</td>


                                                	<td><?php echo (($ob_details[0]["customer_status"] === $ob_details[0]["status"]) ? $ob_details[0]["status"] : "PENDING");?><br/><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></td>
                                                </tr>
												<?php if($ib_details && count($ib_details)>0 && isset($ib_details[0]['id']))
												{
													$dateDiff = intval((strtotime($ib_details[0]["arrival_date_time"])-strtotime($ib_details[0]["departure_date_time"]))/60);
													$dateDiff1 = intval((strtotime($ib_details[0]["arrival_date_time1"])-strtotime($ib_details[0]["departure_date_time1"]))/60); ?>

													<tr>
														<td style="padding: 10px;">
															<div style="width: 10%;display: inline-block;">
																<img style="width: 35px; height: 35px;" src="<?php echo base_url(); ?>upload/thumb/<?php echo $ib_details[0]["image"]; ?>" alt="<?php echo $ib_details[0]["airline"];?>">
															</div>
															<div style="width: 80%; display: inline-block; float: right;">
																<div style="">
																	<?php echo $ib_details[0]["airline"];?>
																	<?php echo $ib_details[0]["flight_no"];?>
																</div>
																<div style="">
																	<?php echo $ib_details[0]["class"];?>
																</div>
															</div>
														</td>
														<td style="padding: 10px;">
															<div style="display: inline-block;">
																<div style="">
																	<div><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo $ib_details[0]["source"];?></div>
																	<div style="margin: 0px 15px;">Terminal - <?php echo $ib_details[0]["terminal"];?></div>
																	<div style="margin: 0px 15px;">
																		<?php echo date("jS M y",strtotime($ib_details[0]["departure_date_time"]))." (".date("H:i",strtotime($ib_details[0]["departure_date_time"])).")";?>
																	</div>
																</div>
															</div>
														</td>
														<td style="padding: 10px;">
															<div style="display: inline-block;">
																<div style="">
																	<div><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo $ib_details[0]["destination"];?></div>
																	<div style="margin: 0px 15px;">Terminal - <?php echo $ib_details[0]["terminal2"];?></div>
																	<div style="margin: 0px 15px;">
																		<?php echo date("jS M y",strtotime($ib_details[0]["arrival_date_time"]))." (".date("H:i",strtotime($ib_details[0]["arrival_date_time"])).")";?>
																	</div>
																</div>
															</div>
														</td>


														<td><?php echo (($ib_details[0]["customer_status"] === $ib_details[0]["status"]) ? $ib_details[0]["status"] : "PENDING");?><br/><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></td>
													</tr>												
												<?php
												}
												?>
                                        	</tbody>
                                        </table>
                               		</div>	

				                    <?php 
									}
									?>

									<!-- Showing Booking Details -->
									<h4 class="t-info-heading"><span><i class="fa fa-info-circle"></i></span>BOOKING DETAILS</h4>
									<div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
												<tr>
                                                	<th style="width: 15%;"><h5>Booking No.</h5></th>
                                                    <th style="width: 20%;"><h5>Booking Date</h5></th>
													<th><h5>Price Summary</h5></th>
													<th style="width: 20%;"><h5>Status</h5></th>
                                                </tr>
												
												<tr>
													<td>Going&nbsp;->&nbsp;BK-<?php echo $ob_details[0]["id"];?></br>Return&nbsp;->&nbsp;BK-<?php echo $ib_details[0]["id"];?></td>
													<td>
														Going&nbsp;&nbsp;-> <?php echo date("d/m/Y",strtotime($ob_details[0]["date"]));?></br>
														Return&nbsp;-> <?php echo date("d/m/Y",strtotime($ib_details[0]["date"]));?>
													</td>
													<?php
														$qty = intval($ob_details[0]["qty"]);
														$rate = ($ob_details[0]["rate"] + $ib_details[0]["rate"]);
														$infant_rate = ($ob_details[0]["infant_price"] + $ib_details[0]["infant_price"]);
														$passengers = $state['passengers'];
														$adult = intval($passengers['adult']);
														$child = intval($passengers['child']);
														$infant = intval($passengers['infant']);

														$srvchg = ($ob_details[0]["service_charge"] + $ib_details[0]["service_charge"]) * $qty;
														$gst = (($ob_details[0]["sgst"]+$ob_details[0]["cgst"]) + ($ib_details[0]["sgst"]+$ib_details[0]["cgst"]));
														$admin_markup = (($ob_details[0]["admin_markup"] + $ib_details[0]["admin_markup"]) * $qty);

														$taxothers = round($srvchg + $gst + $admin_markup, 0);
														$amount = ($ob_details[0]["amount"] + $ib_details[0]["amount"]);

														$total = ($ob_details[0]["total"] + $ib_details[0]["total"]) + $admin_markup;
													?>
													
													<td>
														<ul style="list-style: none; padding-left: 0px;">
															<li class="key"><span>Ticket Fare (each) : </span></li>
															<li class="value"><?php echo number_format($rate,2,".",","); ?></li>
															<li class="key"><span>Passengers : </span></li>
															<?php if($infant > 0) { ?>
																<li class="value"><?= $qty.' + '.$infant.' (Infant)' ; ?></li>
															<?php } 
															else { ?>
																<li class="value"><?= $qty; ?></li>
															<?php } ?>
															<?php if($adult > 0) { ?>
																<li class="key space"><span>Adult : </span></li>
																<li class="value"><?= $adult.' x '.number_format($rate, 2,".",","); ?></li>
															<?php } ?>
															<?php if($child > 0) { ?>
																<li class="key space"><span>Child : </span></li>
																<li class="value"><?= $child.' x '.number_format($rate, 2,".",","); ?></li>
															<?php } ?>
															<?php if($infant > 0) { ?>
																<li class="key space"><span>Infant : </span></li>
																<li class="value"><?= $infant.' x '.number_format($infant_rate, 2,".",","); ?></li>
															<?php } ?>
															<li class="key"><span>Sub Total : </span></li>
															<li class="value grand-total"><?= number_format($amount,2,".",","); ?></li>
															<li class="key"><span>Tax & Others : </span></li>
															<li class="value"><?= number_format($taxothers,2,".",","); ?></li>
															<li class="key"><span>Grand Total : </span></li>
															<li class="value grand-total"><?= number_format($total,2,".",","); ?></li>
														</ul>
													</td>
													
													<td>            
													   <?php echo (($ob_details[0]["customer_status"] === $ob_details[0]["status"]) ? "Going -> ".$ob_details[0]["status"] : "PENDING")."<br>";?>
													   <?php echo (($ib_details[0]["customer_status"] === $ib_details[0]["status"]) ? "Return -> ".$ib_details[0]["status"] : "PENDING")."<br>";?>
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
								<form method="POST" action="<?php echo base_url(); ?>search/pdf_round/<?php echo $ob_details[0]["correlationid"];?>">
									<?php 
								 		if($ob_details[0]["type"]!="B2C") {
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
											<?php if($tkt_status !== 'PENDING') { ?>
												<button type="button" class="btn btn-orange col-sm-12" onclick="window.print()">PRINT</button>
											<?php } 
											else { ?>
												<button type="button" class="btn btn-orange col-sm-12" onclick="javascript:alert('Your ticket is in PENDING state. Can`t print this ticket until it`s approved.');">PRINT</button>
											<?php }?>
										</div>
										<div class="col-sm-2">
											<!-- <a href="<?php echo base_url(); ?>search/pdf/<?php echo $ob_details[0]["id"];?>" class="btn btn-orange col-sm-12" id="pdfview">DOWNLOAD</a> -->
											<?php if($tkt_status !== 'PENDING') { ?>
												<button type="submit" class="btn btn-orange col-sm-12" title="Download ticket in PDF format">DOWNLOAD</button>
											<?php } 
											else { ?>
												<button type="button" class="btn btn-orange col-sm-12" title="Download ticket in PDF format" onclick="javascript:alert('Your ticket is in PENDING state. Can`t download this ticket until it`s approved.');">DOWNLOAD</button>
											<?php }?>
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