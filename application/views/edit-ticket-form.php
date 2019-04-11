        <style>
		.lg-booking-form .form-group .fa 
		{
			position: absolute;
			top: 9px;
			right: 4px;
			pointer-events: none;
			color: #FAA61A;
			font-size: 20px;
		}
		.content-side
		{
			border:1px solid #F4E8E8;
			padding: 38px;
			background:#fff;
		}
		.lg-booking-form .lg-booking-form-heading h3
		{
		padding-top: 10px;
		border-bottom: 1px solid #FAA61A;
		padding-bottom: 20px;
		}
		.innerpage-wrapper
		{
			background:#f2f2f2;
		}
		</style>
        <!--=============== PAGE-COVER =============-->
        <section class="page-cover" id="cover-flight-booking">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Edit Ticket</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>">Home</a></li>
                            <li class="active">Edit Ticket</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="flight-booking" class="innerpage-section-padding">
                <div class="container">
                    <div class="row">
                    	
                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 content-side">
                        	<form id="frm_ticket" class="lg-booking-form" action="<?php echo base_url();?>user/update-ticket" method="POST"  autocomplete="off" onsubmit="return addoneway()"> 							    							    
									<input type="hidden" name="trip_type" id="trip_type" value="ONE">
									<input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $flight[0]["id"];?>">
									<div class="lg-booking-form-heading">
										<span><i class="fa fa-info-circle"></i></span>
										<h3>Edit Ticket</h3>
										
									</div>                            
									<div class="personal-info"> 									
										<div class="row">
										
										   
											<div class="col-xs-6 col-sm-6">
												<div class="form-group">												    
													<input type="text" class="form-control" name="pnr" placeholder="PNR" value="<?php echo $flight[0]["pnr"];?>"/>
												</div>
											</div>
											
										    <div class="col-xs-6 col-sm-6">
												<div class="form-group">												    
													<input type="text" class="form-control dpd3" name="dt" id="dt" placeholder="Date" value="<?php echo date("d-m-Y",strtotime($flight[0]["departure_date_time"]));?>" required/>
												</div>
											</div>
											<?php
											
											 $hh=date("H",strtotime($flight[0]["departure_date_time"]));
											 $mm=date("i",strtotime($flight[0]["departure_date_time"]));
											 $hh1=date("H",strtotime($flight[0]["arrival_date_time"]));
											 $mm1=date("i",strtotime($flight[0]["arrival_date_time"]));
											?>
											
										</div>	
										
										<div class="row">
											<div class="lg-booking-form-heading">
												<span><i class="fa fa-info-circle"></i></span>
												<h3>Enter One way Details</h3>
											</div>
                                           																																
											<div class="col-xs-6 col-sm-3">
											   <label>Source</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">												    
													 <select class="form-control" name="source" id="source">													 
													  <?php
													  foreach($city as $key=>$value)
													  {
													  ?>
													  <option value="<?php echo $city[$key]["id"];?>" <?php if($flight[0]["source"]==$city[$key]["id"]) echo "selected"; ?>><?php echo $city[$key]["city"];?></option>
													  <?php
													  }
													   ?>										  
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
																																	
											<div class="col-xs-6 col-sm-3">
											   <label>Destination</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">												    
													 <select class="form-control" name="destination" id="destination">													
													  <?php
													  foreach($city as $key=>$value)
													  {
													  ?>
													  <option value="<?php echo $city[$key]["id"];?>" <?php if($flight[0]["destination"]==$city[$key]["id"]) echo "selected"; ?>><?php echo $city[$key]["city"];?></option>
													  <?php
													  }
													   ?>										  
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											   <label>Airline</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<select class="form-control right-icon" name="airline" id="airline">
													 <option value="">Airline</option>
													  <?php
													  foreach($airline as $key=>$value)
													  {
													  ?>
													  <option color="<?php echo $airline[$key]["aircode"];?>" value="<?php echo $airline[$key]["id"];?>"  <?php if($flight[0]["airline"]==$airline[$key]["id"]) echo "selected"; ?>><?php echo $airline[$key]["airline"];?></option>
													  <?php
													  }
													   ?>										 
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>	
											
											<div class="col-xs-6 col-sm-3">
											   <label>AirCode</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">
												  <input type="text" class="form-control" name="aircode" id="aircode" placeholder="AirCode" value="<?php echo $flight[0]["aircode"] ?>" readonly/>
												</div>
											</div>

											<div class="col-xs-6 col-sm-3">
											   <label>Departure Time (Hours:Minutes)</label>
											</div>
											<div class="col-xs-2 col-sm-1">
												<div class="form-group">																							
													<select class="form-control right-icon" name="hh" id="hh">
												
													 <?php
													 for($i=1;$i<=24;$i++)
													 {
													 ?>
															<option value="<?php echo sprintf("%02d", $i); ?>" <?php if($hh==$i) echo "selected";?>><?php echo sprintf("%02d", $i); ?></option>									 
													 <?php
													 }
                                                     ?>													 
													</select>													
													<i class="fa fa-angle-down" ></i>
												</div>
										    </div>
											
											<div class="col-xs-2 col-sm-1">
												<div class="form-group">
																								
													<select class="form-control right-icon" name="mm" id="mm" >													
													 <?php
													 for($i=0;$i<=59;$i++)
													 {
													 ?>
															<option value="<?php echo sprintf("%02d", $i); ?>" <?php if($mm==$i) echo "selected";?>><?php echo sprintf("%02d", $i); ?></option>									 
													 <?php
													 }
                                                     ?>		
													</select>															
													<i class="fa fa-angle-down" ></i>
												</div>
										    </div>
											
											<div class="col-xs-2 col-sm-1">
											</div>
											
											<div class="col-xs-6 col-sm-3">
											   <label>Arrival Time (Hours:Minutes)</label>
											</div>
											<div class="col-xs-2 col-sm-1">
												<div class="form-group">																							
													<select class="form-control right-icon" name="hh1" id="hh1" >													
													 <?php
													 for($i=1;$i<=24;$i++)
													 {
													 ?>
															<option value="<?php echo sprintf("%02d", $i); ?>" <?php if($hh1==$i) echo "selected";?>><?php echo sprintf("%02d", $i); ?></option>									 
													 <?php
													 }
                                                     ?>													 
													</select>													
													<i class="fa fa-angle-down"></i>
												</div>
										    </div>
										
											
											
											<div class="col-xs-2 col-sm-1">
												<div class="form-group">
																								
													<select class="form-control right-icon" name="mm1" id="mm1" >													
													 <?php
													 for($i=0;$i<=59;$i++)
													 {
													 ?>
															<option value="<?php echo sprintf("%02d", $i); ?>" <?php if($mm1==$i) echo "selected";?>><?php echo sprintf("%02d", $i); ?></option>									 
													 <?php
													 }
                                                     ?>		
													</select>															
													<i class="fa fa-angle-down" ></i>
												</div>
										    </div>
											<div class="col-xs-2 col-sm-1">
											</div>
										
											
											
											
											
											
											
											<div class="col-xs-6 col-sm-3">
											   <label>Flight No.</label>
											</div>
										   <div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="flight_no" id="flight_no" placeholder="Flight No." value="<?php echo $flight[0]["flight_no"] ?>" />
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											  <label>No. of Stops</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">													
													<select class="form-control" name="no_of_stops" id="no_of_stops">	
													  <option value="0" <?php if($flight[0]["no_of_stops"]==0) echo "selected"; ?>>0</option>
													  <option value="1" <?php if($flight[0]["no_of_stops"]==1) echo "selected"; ?>>1</option>
													  <option value="2" <?php if($flight[0]["no_of_stops"]==2) echo "selected"; ?>>2</option>
													  <option value="3" <?php if($flight[0]["no_of_stops"]==3) echo "selected"; ?>>3</option>
													  <option value="4" <?php if($flight[0]["no_of_stops"]==4) echo "selected"; ?>>4</option>													  
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>
											
											<div id="div_stop" <?php if($flight[0]["no_of_stops"]>0) { echo "style='display:block'";}else {echo "style='display:none'";}?>>
												
												<?php 
													$label=array("0"=>"First","1"=>"Second","2"=>"Third","3"=>"Fourth");
													$arr=explode(",",$flight[0]["stops_name"]);
													foreach($arr as $key=>$value)
													{
													?>
													    <div id="stoprow">
														<div class="col-xs-6 col-sm-6">
														<label>Enter <?php echo $label[$key];?> Stop Name</label>
														</div>
														<div class="col-xs-6 col-sm-6">
															<div class="form-group">												
																<input type="text" class="form-control" name="stop_name[]"  placeholder="Stop Name" value="<?php echo $value;?>" />
															</div>
														</div>
                                                        </div>														
												<?php 
													}
												 ?>
											</div>
											
								          
											<div class="col-xs-6 col-sm-3">
											  <label>Departure Terminal No.</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">
													
													<select class="form-control" name="terminal" id="terminal">										   
													  <option value="1" <?php if($flight[0]["terminal"]==1) echo "selected"; ?>>1</option>
													  <option value="2" <?php if($flight[0]["terminal"]==2) echo "selected"; ?>>2</option>
													  <option value="3" <?php if($flight[0]["terminal"]==3) echo "selected"; ?>>3</option>
													  <option value="4" <?php if($flight[0]["terminal"]==4) echo "selected"; ?>>4</option>												  
													  <option value="NA" <?php if($flight[0]["terminal"]=="NA") echo "selected"; ?>>NA</option>
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											  <label>Arrival Terminal No.</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">
													
													<select class="form-control" name="terminal1" id="terminal1">										   
													  <option value="1" <?php if($flight[0]["terminal1"]==1) echo "selected"; ?>>1</option>
													  <option value="2" <?php if($flight[0]["terminal1"]==2) echo "selected"; ?>>2</option>
													  <option value="3" <?php if($flight[0]["terminal1"]==3) echo "selected"; ?>>3</option>
													  <option value="4" <?php if($flight[0]["terminal1"]==4) echo "selected"; ?>>4</option>	
													  <option value="NA" <?php if($flight[0]["terminal"]=="NA") echo "selected"; ?>>NA</option>
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											   <label>Class</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">													
													<select class="form-control" name="class" id="class">													  
													  <option value="FIRST" <?php if($flight[0]["class"]=="FIRST") echo "selected"; ?>>First Class</option>
													  <option value="BUSINESS" <?php if($flight[0]["class"]=="BUSINESS") echo "selected"; ?>>Business Class</option>
													  <option value="PREMIUM" <?php if($flight[0]["class"]=="PREMIUM") echo "selected"; ?>>Premium Economy Class</option>
													  <option value="ECONOMY" <?php if($flight[0]["class"]=="ECONOMY") echo "selected"; ?>>Economy Class</option>
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
									    </div>
										
											
										<div class="row">
											<div class="lg-booking-form-heading">
												<span><i class="fa fa-info-circle"></i></span>
												<h3>Enter Price and Other Details</h3>
											</div>
											<div class="col-xs-6 col-sm-3">
											   <label>Seats</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="number" min="1" class="form-control" name="no_of_person" id="no_of_person" placeholder="Seats" value="<?php echo $flight[0]["no_of_person"] ?>" />
												</div>
											</div>
											<!--
											<div class="col-xs-6 col-sm-3">
											   <label>No. of Availibility</label>
											</div>
										    <div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="number" min="1" class="form-control" name="availibility" id="availibility" placeholder="1" value="<?php echo $flight[0]["availibility"] ?>"/>
												</div>
											</div>-->
											
											
											<div class="col-xs-6 col-sm-3">
											   <label>Price</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="price" id="price" placeholder="5000"  autocomplete="off" onkeyup="return calculate(event)" onblur="return calculate(event)" onchange="return calculate(event)"  value="<?php echo $flight[0]["price"] ?>"/>
												</div>
											</div>
											<!--
											<div class="col-xs-6 col-sm-3">
											   <label>Markup</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="markup" id="markup" placeholder="1000"  autocomplete="off" onkeyup="return calculate(event)" onblur="return calculate(event)" onchange="return calculate(event)" value="<?php echo $flight[0]["markup"] ?>"/>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											   <label>Discount</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="discount" id="discount" placeholder="0.00"  autocomplete="off" onkeyup="return calculate(event)" onblur="return calculate(event)" onchange="return calculate(event)" value="<?php echo $flight[0]["discount"]; ?>"/>
												</div>
											</div>-->
											<div class="col-xs-6 col-sm-3">
											   <label>Total</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="total" id="total" placeholder="6000"  value="<?php echo $flight[0]["total"] ?>" readonly/>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											  <label>Remarks</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">
													
													<textarea  class="form-control" name="remarks" id="remarks"><?php echo $flight[0]["remarks"]; ?></textarea>
												</div>
											</div>
											<div class="col-xs-6 col-sm-3">
											   <label>Available</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">													
													<select class="form-control" name="available" id="available">													  
													  <option value="YES" <?php if($flight[0]["available"]=="YES") echo "selected"; ?>>YES</option>
													  <option value="NO" <?php if($flight[0]["available"]=="NO") echo "selected"; ?>>NO</option>
													  
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
											<div class="col-xs-6 col-sm-6">
													<div class="custom-check">
													<input type="checkbox" id="check01" name="refundable" <?php if($flight[0]["refundable"]=="Y") echo "checked";?>/>
													<label for="check01"><span><i class="fa fa-check"></i></span>Refundable</label>
													</div>
											</div>
																					
										</div>	                                        									
									</div> 								
                                <button type="submit" class="btn btn-orange">UPDATE</button>
                            </form>                            
                        </div><!-- end columns -->

                    </div><!-- end row -->
                </div><!-- end container -->         
            </div><!-- end flight-booking -->
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
        
        
      
