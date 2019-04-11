        <style>
		.lg-booking-form .form-group .fa 
		{
			position: absolute;
			top: 9px;
			right: 10px;
			pointer-events: none;
			color: #FAA61A;
			font-size: 20px;
		}
		</style>
        <!--=============== PAGE-COVER =============-->
        <section class="page-cover" id="cover-flight-booking">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Passenger Information</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>">Home</a></li>
                            <li class="active">Passenger Information</li>
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
                    	<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 side-bar left-side-bar">
                        	<div class="row">
                            
                                 <div class="col-xs-12 col-sm-6 col-md-12">
								  <div class="side-bar-block detail-block style1 text-center">
									<div class="detail-img text-center">
									  <a href="#"><img src="<?php echo base_url(); ?>upload/thumb/<?php echo $flight[0]["image"];?>" class="img-responsive"></a>
									</div><!-- end detail-img -->

									<div class="detail-title">
									  <h4><a href="#"> <?php echo $flight[0]["source"]; ?> To <?php echo $flight[0]["destination"]; ?></a></h4>
									  <p><?php if($flight[0]["trip_type"]=="ONE") echo $flight[0]["trip_type"]." Way"; if($flight[0]["trip_type"]=="ROUND") echo $flight[0]["trip_type"]." Trip";  ?></p>
									  
									</div><!-- end detail-title -->

									<div class="table-responsive">
									  <table class="table table-hover">
										<tbody>
										 <?php if($flight[0]["trip_type"]=="ONE") {?>
										  <tr>
											<td>Departure Date Time</td>
											<td><?php echo date("jS M y",strtotime($flight[0]["departure_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["departure_date_time"])); ?>)</td>
										  </tr>
										  
										  <tr>
											<td>Arrival Date Time</td>
											<td><?php echo date("jS M y",strtotime($flight[0]["arrival_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time"])); ?>)</td>
										  </tr>
										 <?php } ?>
										 <?php if($flight[0]["trip_type"]=="ROUND") {?>
										  <tr>
											<td>Departure Date Time</td>
											<td><?php echo date("jS M y",strtotime($flight[0]["departure_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["departure_date_time"])); ?>)</td>
										  </tr>
										  
										  <tr>
											<td>Arrival Date Time</td>
											<td><?php echo date("jS M y",strtotime($flight[0]["arrival_date_time"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time"])); ?>)</td>
										  </tr>
										  
										  <tr>
											<td>Return Departure Date Time</td>
											<td><?php echo date("jS M y",strtotime($flight[0]["departure_date_time1"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["departure_date_time1"])); ?>)</td>
										  </tr>
										  
										  <tr>
											<td>Return Arrival Date Time</td>
											<td><?php echo date("jS M y",strtotime($flight[0]["arrival_date_time1"])); ?> (<?php echo date("h:i a",strtotime($flight[0]["arrival_date_time1"])); ?>)</td>
										  </tr>
										 <?php } ?>
										  <tr>
											<td>Class</td>
											<td><?php echo $flight[0]["class"]; ?></td>
										  </tr>
										 
										  
										  <tr>
											<td>Price</td>
											<td><?php echo number_format($flight[0]["price"],2,".",""); ?></td>
										  </tr>
										  <tr>
											<td>Qty</td>
											<td><?php echo $flight[0]["qty"]; ?></td>
										  </tr>
										  <tr>
											<td>Sub Total</td>
											<td><?php echo number_format($flight[0]["price"]*$flight[0]["qty"],2,".",""); ?></td>
										  </tr>
										  
										  <tr>
											<td>Service Charge</td>
											<td><?php echo number_format($flight[0]["service_charge"],2,".",""); ?></td>
										  </tr>
										  
										 
										 
											<tr>
											<td>GST 
											<?php 
											$gst=$flight[0]["gst"]; 
											$service_charge=$flight[0]["service_charge"];
											echo $gst;?> % </td>
											<td><?php echo number_format(($service_charge*$gst/100),2,".",""); ?></td>
										    </tr>
											
										  <tr>
											<td>Total</td>
											<?php $grand_total=($flight[0]["price"]*$flight[0]["qty"])+$service_charge+($service_charge*$gst/100);?>
											<td><?php echo number_format($grand_total,2,".",""); ?></td>
										  </tr>
										</tbody>
									  </table>
									</div><!-- end table-responsive -->
								  </div><!-- end side-bar-block -->
								</div>
                                
                                
                                <div class="col-xs-12 col-sm-6 col-md-12">    
                                    <div class="side-bar-block support-block">
                                        <h3>Need Help</h3>
                                        <p>Lorem ipsum dolor sit amet, ad duo fugit aeque fabulas, in lucilius prodesset pri. Veniam delectus ei vis. Est atqui timeam mnesarchum.</p>
                                        <div class="support-contact">
                                            <span><i class="fa fa-phone"></i></span>
                                            <p>+1 123 1234567</p>
                                        </div><!-- end support-contact -->
                                    </div><!-- end side-bar-block -->
                                </div><!-- end columns -->
                                
                            </div><!-- end row -->
                        
                        </div><!-- end columns -->
                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 content-side">
                        	<form class="lg-booking-form" action="<?php echo base_url(); ?>search/update_booking" method="POST">
							    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $flight[0]["id"]; ?>">			
								<input type="hidden" name="price" value="<?php echo number_format($flight[0]["price"],2,".",""); ?>">
								<input type="hidden" name="qty" value="<?php echo $flight[0]["qty"]; ?>">								
								<input type="hidden" name="total" value="<?php echo number_format($grand_total,2,".",""); ?>">
								
								<?php
											if($this->session->flashdata('msg'))
											{
											?>
											<div class="alert alert-success"><?php echo $this->session->flashdata('msg'); ?></div>
											<?php
											}
											?>
							    <?php								
								if(isset($flight))
								{
								 $arr=array("1"=>"1st","2"=>"2nd","3"=>"3rd","4"=>"4th","5"=>"5th","6"=>"6th","7"=>"7th","8"=>"8th","9"=>"9th","10"=>"10th","11"=>"11th","12"=>"12th","13"=>"13th","14"=>"14th","15"=>"15th","16"=>"16th","17"=>"17th","18"=>"18th","19"=>"19th","20"=>"20th","21"=>"21st","22"=>"22nd","23"=>"23rd","24"=>"24th","25"=>"25th");	
								 $ctr=1;	
								 $key=0;
                                 $qty=$flight[0]["qty"];									
								 while($ctr<=$qty)
								 {
								?>
								    <input type="hidden" name="hid_booking_id[]" id="hid_booking_id" value="<?php echo $flight[$key]["hid_id"]; ?>">			
									<div class="lg-booking-form-heading">
									     
										<span><?php echo $arr[$ctr];?></span>
										<h3>Passenger Information</h3>
									</div> 
									
									<div class="personal-info"> 
                            									
										<div class="row">
											<div class="col-xs-4 col-sm-4">
												<div class="form-group right-icon">	
													<select name="prefix[]"  class="form-control">
														 <option value="Mr." <?php if($flight[$key]["prefix"]=="Mr.") echo "selected";?>>Mr.</option>
														 <option value="Miss" <?php if($flight[$key]["prefix"]=="Miss") echo "selected";?>>Miss</option>
														 <option value="Mrs." <?php if($flight[$key]["prefix"]=="Mrs.") echo "selected";?>>Mrs.</option>
														 <option value="Master" <?php if($flight[$key]["prefix"]=="Master") echo "selected";?>>Master</option>
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
											
											<div class="col-xs-4 col-sm-4">
												<div class="form-group">												
													<input type="text" class="form-control" name="first_name[]" placeholder="First Name" value="<?php echo $flight[$key]["first_name"]; ?>"required/>
												</div>
											</div>
											
											<div class="col-xs-4 col-sm-4">
												<div class="form-group">													
													<input type="text" class="form-control" name="last_name[]" placeholder="Last Name" value="<?php echo $flight[$key]["last_name"]; ?>" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-4 col-sm-4">
												<div class="form-group">													
													<input type="text" maxlength="10" name="mobile_no[]" class="form-control" placeholder="Mobile No." value="<?php echo $flight[$key]["mobile_no"]; ?>" required/>
												</div>
											</div>
											<div class="col-xs-4 col-sm-4">
												<div class="form-group right-icon">													
													<input type="email" name="email[]" class="form-control" placeholder="Email" value="<?php echo $flight[$key]["cemail"]; ?>" required/>
												</div>
											</div>
											<div class="col-xs-4 col-sm-4">
												<div class="form-group">													
													<input type="number" maxlength="10" min="1" name="age[]" class="form-control" placeholder="Age" value="<?php echo $flight[$key]["age"]; ?>" required/>
												</div>
											</div>
											
										</div> 
										<?php if($flight[$key]["seller_id"]==$flight[$key]["customer_id"]) {?>
										<div class="row">
										
										  <div class="col-xs-4 col-sm-4">
												<div class="form-group">													
													<input type="text" maxlength="50" name="pnr[]" class="form-control" placeholder="PNR" value="<?php echo $flight[$key]["pnr"]; ?>" />
												</div>
											</div>
										</div>
										<?php } ?>
									</div> 
								<?php
                                  $ctr++;	
                                   $key++;								  
								 }
								}
								?>
								    <?php if($this->session->userdata('user_id')==$flight[0]["seller_id"] && $flight[0]["seller_id"]==$flight[0]["customer_id"])
										{
									?>
										   <!--<div class="lg-booking-form-heading">
												 
												<span><i class="fa fa-info"></i></span>
												<h3>Update Status</h3>
											</div> 
											
											<div class="personal-info"> 
																		
												<div class="row">
													<div class="col-xs-4 col-sm-4">
														<div class="form-group right-icon">	
															<select name="status"  class="form-control">
																 <?php if($flight[0]["status"]=="PENDING") {?>
																 <option value="CONFIRM" <?php if($flight[0]["status"]=="CONFIRM") echo "selected";?>>CONFIRM</option>
																 <option value="PENDING" <?php if($flight[0]["status"]=="PENDING") echo "selected";?>>PENDING</option>
																 <?php } else {?>
																 <option value="CONFIRM" <?php if($flight[0]["status"]=="CONFIRM") echo "selected";?>>CONFIRM</option>
																 
																 <?php } ?>
																
															</select>
															<i class="fa fa-angle-down"></i>
														</div>
													</div>
													
													<div class="col-xs-4 col-sm-4">
														<div class="form-group">												
															<input type="text" class="form-control" name="pnr" placeholder="PNR" value="<?php echo $flight[0]["pnr"]; ?>"required/>
														</div>
													</div>
													
													
												</div>
																																																					   
											</div> -->
									<?php } ?>	
                                <button type="submit" class="btn btn-orange">Update</button>
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
        
        
      
       