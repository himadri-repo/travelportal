
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
	.table th
	{
		border-bottom:1px solid #ccc;
		border-top:none !important;
		padding:0;
		background:#666;
		color:#fff;
	}
	.table td
	{
		border-bottom:1px solid #ccc;
		border-top:none !important;
		padding:0;
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
		font-size:14px !important;
	}
	.table td
	{
		font-size:12px !important;
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
			font-size:14px !important;
		}
		.table td
		{
			font-size:12px !important;
		}

		#innerpage-wrapper
		{
			margin-left:-1000px !important;
		}		
	}
	@page section {
		size: 8.27in 11.69in; 
		margin: .5in .5in .5in .5in; 
		mso-header-margin: .5in; 
		mso-footer-margin: .5in; 
		mso-paper-source: 0;
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
							    <div class="thank-you-note col-md-12 col-xs-12 col-sm-12 center">
								   <h3><span><i class="fa fa-plane"></i>OXY</span> TRA</H3>
								    <p>&nbsp;</p>
								   
								</div>
                            	<!--OLD <div class="thank-you-note col-md-6 col-xs-6 col-sm-6">
                                   <h3><?php echo $setting[0]["site_title"];?></h3> 
								   <p><span><i class="fa fa-envelope"></i></span> <?php echo $setting[0]["email"];?></p>
								   <p><span><i class="fa fa-phone"></i></span><?php echo $setting[0]["phone_no"];?></p>
								   <p><span><i class="fa fa-map-marker"></i></span><?php echo $setting[0]["address"];?></p>
                                </div>
								
								<div class="thank-you-note col-md-6 col-xs-6 col-sm-6">
                                   <h3 class="pull-right"><?php echo $details[0]["name"];?></h3> 
								   <p class="pull-right"><span><i class="fa fa-calendar"></i></span><?php echo date("d-m-Y",strtotime($details[0]["date"]));?></p>
								   <p class="pull-right"><span><i class="fa fa-envelope"></i></span> <?php echo $details[0]["email"];?></p>
								   <p class="pull-right"><span><i class="fa fa-phone"></i></span><?php echo $details[0]["mobile"];?></p>
								   
                                </div> -->
								
								
								<div class="thank-you-note col-md-12 col-xs-12 col-sm-12">
                                   <h3><?php echo $details[0]["name"];?></h3> 
								   <p><span><i class="fa fa-calendar"></i></span> <?php echo date("d-m-Y",strtotime($details[0]["date"]));?></p>
								   <p><span><i class="fa fa-phone"></i></span><?php echo $details[0]["email"];?></p>
								   <p><span><i class="fa fa-map-marker"></i></span><?php echo $details[0]["mobile"];?></p>
                                </div>
								
								<!--<div class="thank-you-note col-md-6 col-xs-6 col-sm-6">
                                   <h3 class="pull-right"></h3> 
								   <p class="pull-right"><span><i class="fa fa-calendar"></i></span></p>
								   <p class="pull-right"><span><i class="fa fa-envelope"></i></span> </p>
								   <p class="pull-right"><span><i class="fa fa-phone"></i></span></p>
								   
                                </div>-->
                                                           
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
									<span>
									</span>
									</h4>
                               		<div class="table-responsive">
                               			<table class="table">
                                        	<tbody>
                                        		
												  <tr>
                                                	<th><h5>SI No.</h5></th>
                                                    <th><h5>Passenger Name</h5></th>													
													<th><h5>Age</h5></th>
													<th><h5>Mobile No.</h5></th>
													<th><h5>Email</h5></th>
													<th><h5>PNR</h5></th>
                                                </tr>
												<?php
												$ctr=1;
												foreach($details as $key=>$value)
												{
													?>
													<tr>
                                                	<td><?php echo $ctr;?></td>
                                                    <td><?php echo $details[$key]["prefix"]." ".$details[$key]["first_name"]." ".$details[$key]["last_name"] ?></th>																									
													<td><?php echo $details[$key]["age"];?></td>
													<td><?php echo $details[$key]["mobile_no"];?></td>
													<td><?php echo $details[$key]["cemail"];?></td>
													<td><?php echo $details[$key]["pnr"];?></td>
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
										$dateDiff = intval((strtotime($details[0]["arrival_date_time"])-strtotime($details[0]["departure_date_time"]))/60);
										?>									
									<h4 class="t-info-heading"><span><i class="fa fa-plane" ></i>&nbsp;&nbsp;GOING</span></h4>
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
                               		</div>	
				                    <?php 
									}
									?>
									
									<?php if($details[0]["trip_type"]=="ROUND") 
									{
										$dateDiff = intval((strtotime($details[0]["arrival_date_time"])-strtotime($details[0]["departure_date_time"]))/60);
										$dateDiff1 = intval((strtotime($details[0]["arrival_date_time1"])-strtotime($details[0]["departure_date_time1"]))/60);
										?>									
									<h4 class="t-info-heading"><span><i class="fa fa-plane"></i>&nbsp;&nbsp;GOING</span></h4>
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
                               		</div>
                                   <h4 class="t-info-heading"><span><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;RETURN</span></h4>
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
                               		</div>	
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
													<td><?php echo $details[0]["id"];?></td>
													<td><?php echo date("d/m/Y h:i:s",strtotime($details[0]["date"]));?></td>													
													
													
													<td><label>Ticket Fare (each) : </label><?php echo number_format($details[0]["rate"],2,".",","); ?><br/>
													<label>Qty : </label><?php echo $details[0]["qty"]; ?><br/>													
													<label>Sub Total : </label><?php echo number_format($details[0]["amount"],2,".",","); ?><br/>
													<label>Service Charge : </label><?php echo number_format($details[0]["service_charge"],2,".",","); ?><br/>
													<!--<label>SGST : </label><?php echo number_format($details[0]["sgst"],2,".",","); ?><br/>
													<label>CGST : </label><?php echo number_format($details[0]["cgst"],2,".",","); ?><br/>-->
													<!-- <label>GST : </label><?php echo number_format(($details[0]["igst"]+$details[0]["sgst"]+$details[0]["cgst"]),2,".",","); ?><br/> -->
													<label>GST : </label><?php echo number_format(($details[0]["sgst"]+$details[0]["cgst"]),2,".",","); ?><br/>
													<label>Grand Total : </label><?php echo number_format($details[0]["total"],2,".",","); ?></td>
													
									                <td>            
													   <?php 
														
															echo $details[$key]["status"]."<br>";
														
															
														
														?>
												  </td>
												</tr>
																							
                                        	</tbody>
                                        </table>
                               		</div>	
                                    
                                    
                               </div><!-- end traveler-info -->
							   
							    
								
                        	</div><!-- end space-right -->
                        </div><!-- end columns -->
						<div id="editor">
						
						<div class="col-sm-4"></div>
						<div class="col-sm-2">
						  <button type="button" class="btn btn-orange col-sm-12" onclick="window.print()">PRINT</button>
						</div>
						<div class="col-sm-2">
						  <a href="<?php echo base_url(); ?>search/pdf/<?php echo $details[0]["id"];?>" class="btn btn-orange col-sm-12" id="pdfview">DOWNLOAD</a>
						</div>
						</div>
						
                        
                        
                    </div><!-- end row -->
        		</div><!-- end container -->
        	</div><!-- end thank-you --> 
        </section><!-- end innerpage-wrapper -->
        
        
        
        
        
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
        
        
       