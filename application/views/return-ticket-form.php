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
		#div_request_import
		{
			display:none;
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
                    	<h1 class="page-title">Add New Ticket</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>">Home</a></li>
                            <li class="active">New Ticket</li>
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
                        	      <div  class="lg-booking-form"> 							    							    
									
									<div class="lg-booking-form-heading">
										<span><i class="fa fa-info-circle"></i></span>
										<h3>Add Ticket</h3>
									</div>	
									</div>
									
									<div class="personal-info"> 
									     <div class="row" id="div_request_import">
										     <form action="<?php echo base_url() ?>user/import-request-ticket" method="POST" enctype="multipart/form-data"> 
										      <input type="hidden" name="request_trip_type" id="request_trip_type" value="ROUND">
											  <div class="col-xs-5 col-sm-5">
											   <label>Select Excel file to import Tickets</label>
											 </div>
										    <div class="col-xs-3 col-sm-3">
												<div class="form-group">												    
													<input style="background:#fff" type="file" class="form-control" name="request_file" id="request_file" accept=".xls,.xlsx" required/>
												</div>
											</div>
											 <div class="col-xs-4 col-sm-4">
											   <div class="form-group">
											   <button style="margin:0" type="submit" class="btn btn-orange" >Import</button>
											   <a style="margin:0"  href="<?php echo base_url() ?>download.php?file=request_return_ticket_demo.xlsx" type="submit" class="btn btn-orange" >Download demo</a>
											   </div>
											</div>
										    </form>
										</div>
										<form id="frm_ticket" class="lg-booking-form" action="<?php echo base_url();?>user/submit-ticket" method="POST"  autocomplete="off" onsubmit="return addreturn()"> 							    							    
									<input type="hidden" name="trip_type" id="trip_type" value="ROUND">
                                        <div class="row">
										    <div class="col-xs-4 col-sm-4">
													<div class="custom-check">
													<input type="radio" id="check02" name="sale_type" value="live"/>
													<label for="check02">Live Ticket</label>
													</div>
											</div>
											
											<div class="col-xs-4 col-sm-4">
													<div class="custom-check">
													<input type="radio" id="check03" name="sale_type" value="request" checked/>
													<label for="check03">Request Ticket</label>
													</div>
											</div>
											
											<div class="col-xs-4 col-sm-4">
													<div class="custom-check">
													<input type="radio" id="check04" name="sale_type" value="quote"/>
													<label for="check04">Quotation Ticket</label>
													</div>
											</div>
                                        </div>										
										<div class="row">
										
											
											<div class="col-xs-6 col-sm-4">
												<div class="form-group">												    
													<input type="text" class="form-control" name="pnr" placeholder="PNR"/>
												</div>
											</div>
											
										  <div class="col-xs-6 col-sm-4">
												<div class="form-group right-icon">												    
													 <select class="form-control" name="source" id="source">	
													  <option value="">From</option>
													  <?php
													  foreach($city as $key=>$value)
													  {
													  ?>
													  <option value="<?php echo $city[$key]["id"];?>"><?php echo $city[$key]["city"];?></option>
													  <?php
													  }
													   ?>										  
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
											
											 <div class="col-xs-6 col-sm-4">
												<div class="form-group right-icon">												    
													 <select class="form-control" name="destination" id="destination">	
													  <option value="">To</option>
													  <?php
													  foreach($city as $key=>$value)
													  {
													  ?>
													  <option value="<?php echo $city[$key]["id"];?>"><?php echo $city[$key]["city"];?></option>
													  <?php
													  }
													   ?>										  
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
										</div>	
										
										<div class="row">
											<div class="lg-booking-form-heading">
												<span><i class="fa fa-info-circle"></i></span>
												<h3>Enter One way Details</h3>
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
													  <option color="<?php echo $airline[$key]["aircode"];?>" value="<?php echo $airline[$key]["id"];?>"><?php echo $airline[$key]["airline"];?></option>
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
												  <input type="text" class="form-control" name="aircode" id="aircode" placeholder="AirCode" readonly/>
												</div>
											</div>

											<div class="col-xs-6 col-sm-3">
											   	<label>Departure Date Time</label>
											</div> 
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">
												
													<div class="controls input-append date form_datetime" data-date="<?php echo date("Y-m-d h:i:s");?>" data-date-format="dd MM yyyy - HH:ii p" data-link-field="departure_date_time">
														<input  class="form-control" type="text" value=""  readonly Placeholder="20 Aug 2018 - 11:00 am">
														<span class="add-on"><i class="icon-remove"></i></span>
														<span class="add-on"><i class="icon-th"></i></span>
														<input type="hidden" id="departure_date_time" name="departure_date_time"  value="" />
													</div>
												</div><!-- end form-group -->
											</div><!-- end columns -->
											 <div class="col-xs-6 col-sm-3">
											   	<label>Arrival Date Time</label>
											</div> 
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">
													
													<div class="controls input-append date form_datetime" data-date="<?php echo date("Y-m-d h:i:s");?>" data-date-format="dd MM yyyy - HH:ii p" data-link-field="arrival_date_time">
														<input  class="form-control" type="text"   value="" readonly Placeholder="20 Aug 2018 - 11:00 am">
														<span class="add-on"><i class="icon-remove"></i></span>
														<span class="add-on"><i class="icon-th"></i></span>
														<input type="hidden" id="arrival_date_time" name="arrival_date_time"  value="" />
													</div>
												</div><!-- end form-group -->
											</div><!-- end columns -->
											<div class="col-xs-6 col-sm-3">
											   <label>Flight No.</label>
											</div>
										   <div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="flight_no" id="flight_no" placeholder="Flight No." />
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											  <label>No. of Stops</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">													
													<select class="form-control" name="no_of_stops" id="no_of_stops">	
													  <option value="0">0</option>
													  <option value="1">1</option>
													  <option value="2">2</option>
													  <option value="3">3</option>
													  <option value="4">4</option>													  
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>
											
											<div id="div_stop" style="display:none">
												
											</div>
								
											<div class="col-xs-6 col-sm-3">
											  <label>Departure Terminal No.</label>
											</div>
										    <div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">
													
													<select class="form-control" name="terminal" id="terminal">										   
													  <option value="1">1</option>
													  <option value="2">2</option>
													  <option value="3">3</option>
													  <option value="4">4</option>													  
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
													  <option value="1">1</option>
													  <option value="2">2</option>
													  <option value="3">3</option>
													  <option value="4">4</option>													  
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
													  <option value="FIRST">First Class</option>
													  <option value="BUSINESS">Business Class</option>
													  <option value="PREMIUM">Premium Economy Class</option>
													  <option value="ECONOMY">Economy Class</option>
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
									    </div>
										
										<div class="row" id="div_returns">
											<div class="lg-booking-form-heading">
												<span><i class="fa fa-info-circle"></i></span>
												<h3>Enter Return Details</h3>
											</div>
                                           																																																																																							
											
											<div class="col-xs-6 col-sm-3">
											   <label>Airline</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<select class="form-control right-icon" name="airline1" id="airline1">
													 <option value="">Airline</option>
													  <?php
													  foreach($airline as $key=>$value)
													  {
													  ?>
													  <option color="<?php echo $airline[$key]["aircode"];?>" value="<?php echo $airline[$key]["id"];?>"><?php echo $airline[$key]["airline"];?></option>
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
												  <input type="text" class="form-control" name="aircode1" id="aircode1" placeholder="AirCode" readonly />
												</div>
											</div>
                                            <div class="col-xs-6 col-sm-3">
											   	<label>Departure Date Time</label>
											</div> 
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">
												
													<div class="controls input-append date form_datetime" data-date="<?php echo date("Y-m-d h:i:s");?>" data-date-format="dd MM yyyy - HH:ii p" data-link-field="departure_date_time1">
														<input  class="form-control" type="text" value=""  readonly Placeholder="20 Aug 2018 - 11:00 am">
														<span class="add-on"><i class="icon-remove"></i></span>
														<span class="add-on"><i class="icon-th"></i></span>
														<input type="hidden" id="departure_date_time1" name="departure_date_time1"  value="" />
													</div>
												</div><!-- end form-group -->
											</div><!-- end columns -->
											 <div class="col-xs-6 col-sm-3">
											   	<label>Arrival Date Time</label>
											</div> 
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">
													
													<div class="controls input-append date form_datetime" data-date="<?php echo date("Y-m-d h:i:s");?>" data-date-format="dd MM yyyy - HH:ii p" data-link-field="arrival_date_time1">
														<input  class="form-control" type="text"   value="" readonly Placeholder="20 Aug 2018 - 11:00 am">
														<span class="add-on"><i class="icon-remove"></i></span>
														<span class="add-on"><i class="icon-th"></i></span>
														<input type="hidden" id="arrival_date_time1" name="arrival_date_time1"  value="" />
													</div>
												</div><!-- end form-group -->
											</div><!-- end columns -->
											
											
											<div class="col-xs-6 col-sm-3">
											   <label>Flight No.</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="flight_no1" id="flight_no1" placeholder="Flight No." />
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											  <label>No. of Stops</label>
											</div>
										    <div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">													
													<select class="form-control" name="no_of_stops1" id="no_of_stops1">
													 <option value="0">0</option>
													  <option value="1">1</option>
													  <option value="2">2</option>
													  <option value="3">3</option>
													  <option value="4">4</option>													  
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>
											<div id="div_stop1" style="display:none">
												
											</div>
								
											<div class="col-xs-6 col-sm-3">
											  <label>Departure Terminal No.</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">
													
													<select class="form-control" name="terminal2" id="terminal2">										   
													  <option value="1">1</option>
													  <option value="2">2</option>
													  <option value="3">3</option>
													  <option value="4">4</option>													  
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											  <label>Arrival Terminal No.</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">
													
													<select class="form-control" name="terminal3" id="terminal3">										   
													  <option value="1">1</option>
													  <option value="2">2</option>
													  <option value="3">3</option>
													  <option value="4">4</option>													  
													</select>
													<i class="fa fa-angle-down" ></i>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											   <label>Class</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">													
													<select class="form-control" name="class1" id="class1">													  
													  <option value="FIRST">First Class</option>
													  <option value="BUSINESS">Business Class</option>
													  <option value="PREMIUM">Premium Economy Class</option>
													  <option value="ECONOMY">Economy Class</option>
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
													<input type="number" min="1" class="form-control" name="no_of_person" id="no_of_person" placeholder="Seats"  value="1" />
												</div>
											</div>
											<div class="col-xs-6 col-sm-3">
											   <label>No. of Availibility</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="number" min="1" class="form-control" name="availibility" id="availibility" placeholder="1" value="1"/>
												</div>
											</div>
											
											
											<div class="col-xs-6 col-sm-3">
											   <label>Price</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="price" id="price" placeholder="5000"  autocomplete="off" onkeyup="return calculate(event)" onblur="return calculate(event)" onchange="return calculate(event)" />
												</div>
											</div>
											
											<!--
											<div class="col-xs-6 col-sm-3">
											   <label>Markup</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="markup" id="markup" placeholder="1000"  autocomplete="off" onkeyup="return calculate(event)" onblur="return calculate(event)" onchange="return calculate(event)" />
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											   <label>Discount</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="discount" id="discount" placeholder="0.00"  autocomplete="off" onkeyup="return calculate(event)" onblur="return calculate(event)" onchange="return calculate(event)" />
												</div>
											</div>-->
											
											<div class="col-xs-6 col-sm-3">
											   <label>Total</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group">													
													<input type="text" class="form-control" name="total" id="total" placeholder="6000"  readonly/>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-3">
											  <label>Remarks</label>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="form-group right-icon">
													
													<textarea  class="form-control" name="remarks" id="remarks"></textarea>
												</div>
											</div>
											
											<div class="col-xs-6 col-sm-6">
													<div class="custom-check">
													<input type="checkbox" id="check01" name="refundable"/>
													<label for="check01"><span><i class="fa fa-check"></i></span>Refundable</label>
													</div>
											</div>
																					
										</div>	

                                        										
									</div> 								
                                <button type="submit" class="btn btn-orange">ADD</button>
                            </form>                            
                        </div><!-- end columns -->

                    </div><!-- end row -->
                </div><!-- end container -->         
            </div><!-- end flight-booking -->
        </section><!-- end innerpage-wrapper -->
        
        
       
