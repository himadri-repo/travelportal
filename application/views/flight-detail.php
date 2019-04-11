        <style>
		.f-text .fa
		{
			color:#faa61a;
			margin-right:10px;
		}
        </style> 		
        <!--================== PAGE-COVER ================-->
        <section class="page-cover" id="cover-flight-detail">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Flight Detail </h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>">Home</a></li>
                            <li class="active">Flight Detail</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="flight-details" class="innerpage-section-padding">
                <div class="container">
                    <div class="row">        	
                        
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 content-side">
                            
                            <div class="detail-slider">
                                <div class="feature-slider">
                                    <div><img src="<?php echo base_url(); ?>images/f-feature-1.jpg" class="img-responsive" alt="feature-img"/></div>
                                   
                                </div><!-- end feature-slider -->
                            	
                              
                                <?php											
								$dateDiff = intval((strtotime($flight[0]["arrival_date_time"])-strtotime($flight[0]["departure_date_time"]))/60);								
								?>
								<?php if($flight[0]["trip_type"]=="ONE") 
								{?>
									<ul class="list-unstyled features flight-features">
										<li><div class="f-icon"><i class="fa fa-plane"></i></div><div class="f-text"><p class="f-heading">From</p><p class="f-data"><?php echo $flight[0]["source_city"]; ?><br><?php echo date("jS M y",strtotime($flight[0]["departure_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["departure_date_time"])); ?>)</p></div></li>
										<li><div class="f-icon"><i class="fa fa-plane"></i></div><div class="f-text"><p class="f-heading">To</p><p class="f-data"><?php echo $flight[0]["destination_city"]; ?><br><?php echo date("jS M y",strtotime($flight[0]["arrival_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time"])); ?>)</p></div></li>
										<li><div class="f-icon"><i class="fa fa-clock-o"></i></div><div class="f-text"><p class="f-heading">Total Time</p><p class="f-data"><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></p></div></li>
										<li><div class="f-icon"><i class="fa fa-hotel"></i></div><div class="f-text"><p class="f-heading">Facility</p><p class="f-data"><?php echo $flight[0]["class"]." Class"; ?></p></div></li>
									</ul>
								<?php 
								} 
								else 
								{
									$dateDiff = intval((strtotime($flight[0]["arrival_date_time"])-strtotime($flight[0]["departure_date_time"]))/60);		
									$dateDiff1 = intval((strtotime($flight[0]["arrival_date_time1"])-strtotime($flight[0]["departure_date_time1"]))/60);
									?>
								        <ul class="list-unstyled features flight-features">
                                          <li><div class="f-text"><p class="f-heading">From</p><p class="f-data"><i class="fa fa-plane"></i><?php echo $flight[0]["source_city"]; ?> <?php echo date("jS M y",strtotime($flight[0]["departure_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["departure_date_time"])); ?>)<p class="f-heading">To</p><p class="f-data"><i class="fa fa-plane" style="transform:rotate(83deg)"></i><?php echo $flight[0]["destination_city"]; ?> <?php echo date("jS M y",strtotime($flight[0]["arrival_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time"])); ?>)<p class="f-heading">Duration</p><p class="f-data"><i class="fa fa-clock-o"></i><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></p></div></li>
										  <li><div class="f-text"><p class="f-heading">From</p><p class="f-data"><i class="fa fa-plane" style="transform:rotate(0deg)"></i><?php echo $flight[0]["source_city1"]; ?> <?php echo date("jS M y",strtotime($flight[0]["departure_date_time1"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["departure_date_time1"])); ?>)<p class="f-heading">To</p><p class="f-data"><i class="fa fa-plane" style="transform:rotate(83deg)"></i><?php echo $flight[0]["destination_city1"]; ?> <?php echo date("jS M y",strtotime($flight[0]["arrival_date_time1"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time1"])); ?>)<p class="f-heading">Duration</p><p class="f-data"><i class="fa fa-clock-o"></i><?php echo intval($dateDiff1/60)." Hours ".($dateDiff1%60)." Minutes"; ?></p></div></li>
								          <li><div class="f-icon"><i class="fa fa-hotel"></i></div><div class="f-text"><p class="f-heading">Facility</p><p class="f-data"><?php echo $flight[0]["class"]." Class"; ?></p></div></li>
										</ul>  
								<?php } ?>
                            </div><!-- end detail-slider -->  

                            <div class="detail-tabs">
                            	<ul class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#flight-info" data-toggle="tab">Flight Information</a></li>
                                    <li><a href="#entertainment" data-toggle="tab"><?php echo $flight[0]["no_of_person"]." ";?> Available Seats</a></li>
                                    <li><a href="#connectivity" data-toggle="tab"> <?php echo $flight[0]["no_of_stops"]." ";?> &nbsp; &nbsp;Going Stops</a></li>
                                    <?php if($flight[0]["trip_type"]=="ROUND") {?>
									<li><a href="#fare" data-toggle="tab"><?php echo $flight[0]["no_of_stops1"]." ";?> &nbsp;&nbsp;Returning Stops</a></li>
                                    <?php } ?>
                                </ul>
                            	
                                <div class="tab-content">

                                    <div id="flight-info" class="tab-pane in active">
                                    	<div class="row">
                                    		<div class="col-sm-4 col-md-4 tab-img">
                                        		<img src="<?php echo base_url(); ?>images/flight-detail-tab-1.jpg" class="img-responsive" alt="flight-detail-img" />
                                            </div><!-- end columns -->
                                        	
                                            <div class="col-sm-8 col-md-8 tab-text">
                                        		<h3>Flight Information</h3>
                                                <p><?php echo $flight[0]["remarks"]; ?></p>
                                            </div><!-- end columns -->
                                        </div><!-- end row -->
                                    </div><!-- end flight-info -->
                                	
                                    <div id="entertainment" class="tab-pane">
                                    	<div class="row">
                                    		<div class="col-sm-4 col-md-4 tab-img">
                                        		<img src="<?php echo base_url();?>images/flight-detail-tab-2.jpg" class="img-responsive" alt="flight-detail-img" />
                                            </div><!-- end columns -->
                                        	
                                            <div class="col-sm-8 col-md-8 tab-text">
                                        		<h3>Available Seats</h3>
                                                <p><?php echo $flight[0]["no_of_person"]." Seats Available"; ?></p>
                                            </div><!-- end columns -->
                                        </div><!-- end row -->
                                    </div><!-- end entertainment -->
                                    
                                    <div id="connectivity" class="tab-pane">
                                    	<div class="row">
                                    		<div class="col-sm-4 col-md-4 tab-img">
                                        		<img src="<?php echo base_url();?>images/flight-detail-tab-3.jpg" class="img-responsive" alt="flight-detail-img" />
                                            </div><!-- end columns -->
                                        	
                                            <div class="col-sm-8 col-md-8 tab-text">
											    <?php if($flight[0]["trip_type"]=="ONE" && $flight[0]["no_of_stops"]>0) {?> 
                                        		<h3>Going Details</h3>
												<?php 
												$stops=explode(",",$flight[0]["stops_name"]);
												$stops_ctr=1;
												foreach($stops as $key=>$value)
												{
													if($stops_ctr==1)
														echo " <p>From <strong>".$flight[0]["source_city"]."</strong> to  <strong>".$value."</strong></p>";
													else
														echo " <p>From <strong>".$stops[($key-1)]." to ".$stops[$key]."</strong></p>";
													$stops_ctr++;
												}
												echo " <p>From <strong>".$stops[sizeof($stops)-1]." to ".$flight[0]["destination_city"]."</strong></p>";
												?>
                                               
												<?php } ?>
												
												
                                            </div><!-- end columns -->
                                        </div><!-- end row -->
                                    </div><!-- end connectivity -->
                                       <?php if($flight[0]["trip_type"]=="ROUND") {?>
                                    <div id="fare" class="tab-pane">
                                    	<div class="row">
                                    		<div class="col-sm-4 col-md-4 tab-img">
                                        		<img src="<?php echo base_url();?>images/flight-detail-tab-4.jpg" class="img-responsive" alt="flight-detail-img" />
                                            </div><!-- end columns -->
                                        	
                                            <div class="col-sm-8 col-md-8 tab-text">
                                        		<?php if($flight[0]["trip_type"]=="ROUND" && $flight[0]["no_of_stops1"]>0) {?>
												<h3>Returning Details</h3>
                                                <?php 
												$stops=explode(",",$flight[0]["stops_name1"]);
												$stops_ctr=1;
												foreach($stops as $key=>$value)
												{
													if($stops_ctr==1)
														echo " <p>From <strong>".$flight[0]["destination_city"]." to ".$value."</strong></p>";
													else
														echo " <p>From <strong>".$stops[($key-1)]." to ".$stops[$key]."</strong></p>";
													$stops_ctr++;
												}
												echo " <p>From <strong>".$stops[sizeof($stops)-1]." to ".$flight[0]["source_city"]."</strong></p>";
												?>
												<?php } ?>
                                            </div><!-- end columns -->
                                        </div><!-- end row -->
                                    </div><!-- end fare -->
                                    <?php } ?>
                                    
                                    
                                </div><!-- end tab-content -->
                            </div><!-- end detail-tabs -->
                            
                            
                            
                            
                           
                        </div><!-- end columns -->
                                                
                        <div class="col-xs-12 col-sm-12 col-md-3 side-bar right-side-bar">
                            
                            <div class="side-bar-block booking-form-block">
                            	<h2 class="selected-price"><i class="fa fa-inr"></i><?php echo number_format($flight[0]["total"],2,".",","); ?> <span><?php //echo $flight[0]["ticket_no"];?></span></h2>
                            
                            	<div class="booking-form">
                                	<h3>Book Flight</h3>
                                    <p>Find your dream flight today</p>
                                    
                                    <form method="POST" action="<?php echo base_url(); ?>search/beforebook/<?php echo $flight[0]["id"];?>">
                                    	
									
										<input type="hidden" name="refundable" value="<?php echo $flight[0]["refundable"]; ?>">
										<!--<div class="form-group right-icon" >
										   
										    <select class="form-control" name="ticket_type" id="ticket_type" required>
												<option value="" selected>Select Booking Type</option>
												<option value="B2B">B2B</option>
												<option value="B2C">B2C</option>                                                       
                                             </select>  
											 <i class="fa fa-angle-down"></i>
										</div>-->	 
                                        <div class="form-group">
										    <label>Booking Date</label>
                                    		<input type="text" value="<?php echo date("d-m-Y");?>" class="form-control" placeholder="Booking Date" name="date" readonly required/>                                       
                                        </div>
										
                                        <div class="form-group">
										    <label>Qty</label>
                                    		<input type="text" name="qty" id="qty" value="<?php echo $this->session->userdata('no_of_person');?>" class="form-control" readonly/>                                       
                                        </div>
										
										<div class="form-group">
										
										    <label>Ticket Fare / Per Person</label>
											<?php $price=($this->session->userdata('no_of_person')*$flight[0]["total"]);?>
											<input type="text" id="calc_price" name="calc_price" value="<?php echo $flight[0]["total"]; ?>" class="form-control" readonly/>
                                    		<input type="hidden" name="price" id="price" value="<?php echo $flight[0]["total"]; ?>" class="form-control"/>                                       
                                        </div>
										<?php if($this->session->userdata('user_id')==$flight[0]["uid"])
										{
											?>
											<div class="form-group">
										    <label>Service Charge</label>
                                    		<input type="text" name="service_charge" id="service_charge" value="0" class="form-control" readonly/>                                       
                                        </div>
											<?php
										} else 
										{?>
									     <div class="form-group">
										    <label>Service Charge</label>
                                    		<input type="text" name="service_charge" id="service_charge" value="<?php echo $setting[0]["service_charge"]; ?>" class="form-control" readonly/>                                       
                                        </div>
										<?php 
										} ?>
										
										
										<!--<div class="form-group">
										    <label>SGST <?php echo $setting[0]["sgst"];?> % </label>
                                    		<input type="text" name="sgst" id="sgst" value="<?php echo number_format(($setting[0]["service_charge"]*$setting[0]["sgst"]/100),2,".",""); ?>" class="form-control" readonly/>                                       
                                        </div>
										
										<div class="form-group">
										    <label>CGST <?php echo $setting[0]["cgst"];?> % </label>
                                    		<input type="text" name="cgst" id="cgst" value="<?php echo number_format(($setting[0]["service_charge"]*$setting[0]["cgst"]/100),2,".",""); ?>" class="form-control" readonly/>                                       
                                        </div>
										
										<div class="form-group">
										    <label>IGST <?php echo $setting[0]["igst"];?> %</label>
                                    		<input type="text" name="igst" id="igst" value="<?php echo number_format(($setting[0]["service_charge"]*$setting[0]["igst"]/100),2,".",""); ?>" class="form-control" readonly/>                                       
                                        </div>-->
										<?php if($this->session->userdata('user_id')==$flight[0]["uid"])
										{
											?>
											<div class="form-group">
										    <label>GST <?php $gst=0;$service_charge=0; echo $gst;?> %</label>
                                    		<input type="text" name="igst" id="igst" value="0" class="form-control" readonly/>                                       
                                        </div>
											<?php
										} else 
										{?>
										<div class="form-group">
										    <label>GST <?php $gst=$setting[0]["igst"]+$setting[0]["cgst"]+$setting[0]["sgst"]; $service_charge=$setting[0]["service_charge"];echo $gst;?> %</label>
                                    		<input type="text" name="igst" id="igst" value="<?php echo number_format(($setting[0]["service_charge"]*$gst/100),2,".",""); ?>" class="form-control" readonly/>                                       
                                        </div>	
										<?php 
										} ?>
										<?php if($user_details[0]["is_supplier"]==1){?>
										 <!--<div class="form-group" id="div_markup" >
										    <label>Markup (Each Ticket)</label>
                                    		<input type="text" name="markup" id="markup" value="0" class="form-control" min="0" onkeyup="calculate()" onchange="calculate()" onblur="calculate()"/>                                       
                                        </div>-->
										<?php } ?>
										<div class="form-group">
										    <label>Total</label>
											<?php
											$total=$price+$service_charge+($service_charge*$gst/100);
											
											?>
											<input type="text" name="calc_total" id="calc_total" value="<?php echo $total; ?>" class="form-control" min="0"/>    
                                    		<input type="hidden" name="total" id="total" value="<?php echo $flight[0]["price"]; ?>" class="form-control" min="0"/>                                       
                                        </div>
                                        <!--<div class="row">
                                        	<div class="col-sm-12 col-md-12 col-lg-12 no-sp-r">
                                                <div class="form-group right-icon">
                                                    <select class="form-control" name="qty" required>
                                                        <option value="" selected>No. of Passanger</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                    </select>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div>
                                            
                                           
                                        </div>-->
                                        
                                       
                                        
                                        <div class="checkbox custom-check">
                                        	<input type="checkbox" id="check01" name="checkbox"/>
                                            <label for="check01"><span><i class="fa fa-check"></i></span>By continuing, you are agree to the <a href="#">Terms & Conditions.</a></label>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-block btn-orange">Continue</button>
                                    </form>

                                </div><!-- end booking-form -->
                            </div><!-- end side-bar-block -->
                            
                            
                        </div><!-- end columns -->  
                        
                    </div><!-- end row -->
            	</div><!-- end container -->
            </div><!-- end flight-details -->
        </section><!-- end innerpage-wrapper -->
        
        
       