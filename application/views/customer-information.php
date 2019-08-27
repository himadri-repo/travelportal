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
										<div class="circle"><i class="fa fa-plane" aria-hidden="true"></i><?php echo $flight[0]["source_city"]; ?></div>
										<div class="circle"><i class="fa fa-plane down" aria-hidden="true"></i><?php echo $flight[0]["destination_city"]; ?></div>
									  	<div class="tripway">
											<?php if($flight[0]["trip_type"]=="ONE") echo $flight[0]["trip_type"]." Way"; if($flight[0]["trip_type"]=="ROUND") echo $flight[0]["trip_type"]." Trip";?>
										</div>
									</div><!-- end detail-title -->
									<?php 
										$qty = intval($this->session->userdata('no_of_person'));
										$price = $flight[0]["price"];
										$total = $flight[0]["total"];
										$admin_markup = 0;

										if($currentuser["is_admin"]!=='1' && $currentuser["type"]=='B2B') {
											$admin_markup = $flight[0]["admin_markup"];

											$price += $admin_markup;
											$total += $admin_markup;
										}
									?>
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
											<td><?php echo number_format($price,2,".",""); ?></td>
										  </tr>
										  <tr>
											<td>Qty</td>
											<td><?php echo $qty; ?></td>
										  </tr>
										  <tr>
											<td>Sub Total</td>
											<td><?php echo number_format($price * $qty,2,".",""); ?></td>
										  </tr>
										  <?php if($this->session->userdata('user_id')==$flight[0]["uid"])
										  {
											$service_charge=$flight[0]["service_charge"];
											?>
										   <tr>
											<td>Service Charge</td>
											<td><?php echo number_format($flight[0]["service_charge"] * $qty,2,".",""); ?></td>
											<!-- <td>0</td> -->
										  </tr>
										  <?php
										  }
										  else
										  {
											$service_charge=$flight[0]["service_charge"];?>
											<tr>
												<td>Service Charge</td>
												
												<!-- <td><?php //echo number_format($setting[0]["service_charge"],2,".",""); ?></td> -->
												<td><?php echo number_format($flight[0]["service_charge"] * $qty,2,".",""); ?></td>
											</tr>
										  <?php
										  }
										  ?>
										  <!--
										  <tr>
											<td>SGST <?php echo $setting[0]["sgst"];?> % </td>
											<td><?php echo number_format(($setting[0]["service_charge"]*$setting[0]["sgst"]/100),2,".",""); ?></td>
										  </tr>
										  <tr>
											<td>CGST <?php echo $setting[0]["cgst"];?> % </td>
											<td><?php echo number_format(($setting[0]["service_charge"]*$setting[0]["cgst"]/100),2,".",""); ?></td>
										  </tr>
										  <tr>
											<td>IGST <?php echo $setting[0]["igst"];?> % </td>
											<td><?php echo number_format(($setting[0]["service_charge"]*$setting[0]["igst"]/100),2,".",""); ?></td>
										  </tr>-->
										  <?php if($this->session->userdata('user_id')==$flight[0]["uid"])
										  {
											?>
											<td>GST 
											<?php 
											$gst=$flight[0]["gst"]; 
											//$gst=0; 
											//$service_charge=0;
											//echo $gst;?> % </td>
											<!-- <td><?php //echo number_format(($service_charge*$gst/100),2,".",""); ?></td> -->
											<td><?php echo number_format($flight[0]["gst"] * $qty,2,".",""); ?></td>
										    </tr>
											<?php
										  }
										  else
										  {
											?>
											<tr>
											<td>GST 
											<?php 
											//$gst=($setting[0]["igst"]+$setting[0]["cgst"]+$setting[0]["sgst"]); 
											//$service_charge=$setting[0]["service_charge"];
											$gst=$flight[0]["gst"]; 
											//echo $gst;?> % </td>
											<!-- <td><?php //echo number_format(($service_charge*$gst/100),2,".",""); ?></td> -->
											<td><?php echo number_format($flight[0]["gst"] * $qty,2,".",""); ?></td>
										    </tr>
											<?php
										  }
											?>
										 
										  <tr>
											<td>Total</td>
											<?php //$grand_total=($flight[0]["price"]*$this->session->userdata('no_of_person'))+$service_charge+($service_charge*$gst/100);?>
											<?php $grand_total=($total * $qty);?>
											<td>
												<?php echo number_format($grand_total,2,".",""); 
												$allow_credit = $flight[0]["user"]["credit_ac"];
												$costprice = floatval($flight[0]["costprice"]);
												$total_costprice = ($costprice * $qty);
												$show_alarm = false;
												if($total_costprice>$flight[0]["wallet_balance"] && $allow_credit==0) { 
													$show_alarm = true; ?>
													<span class="warning">*</span>
												<?php }
												?>
											</td>
										  </tr>
										</tbody>
									  </table>
									  	<?php if($show_alarm) { ?>
											<span class="warning">* Insufficient wallet balance (<?php echo $flight[0]["wallet_balance"] ?>)</span>
										<?php } ?>
									</div><!-- end table-responsive -->
								  </div><!-- end side-bar-block -->
								</div>
                                
                               
                                
                            </div><!-- end row -->
                        
                        </div><!-- end columns -->
                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 content-side">
                        	<form class="lg-booking-form" action="<?php echo base_url(); ?>search/book/<?php echo $flight[0]["id"];?>" method="POST">
							    <input type="hidden" name="markup" value="<?php echo $flight[0]["markup"]; ?>">
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
								<!-- <input type="hidden" name="price" value="<?php echo number_format($flight[0]["price"],2,".",""); ?>"> -->
								<input type="hidden" name="price" value="<?php echo number_format($price,2,".",""); ?>">
								<input type="hidden" name="qty" value="<?php echo $this->session->userdata('no_of_person'); ?>">
								<input type="hidden" name="service_charge" value="<?php echo number_format($service_charge,2,".",""); ?>">
								<input type="hidden" name="costprice" value="<?php echo number_format($flight[0]["costprice"],2,".",""); ?>">
								<input type="hidden" name="rateplanid" value="<?php echo $flight[0]["rateplanid"]; ?>">
							
								<input type="hidden" name="igst" value="<?php echo number_format(($service_charge*$gst/100),2,".",""); ?>">
								<input type="hidden" name="total" value="<?php echo number_format($grand_total,2,".",""); ?>">
								<input type="hidden" name="refundable" value="<?php echo $flight[0]["refundable"]; ?>">
								<input type="hidden" name="date" value="<?php echo date("Y-m-d"); ?>">
							    <?php								
								if(isset($flight))
								{
								 $arr=array("1"=>"1st","2"=>"2nd","3"=>"3rd","4"=>"4th","5"=>"5th","6"=>"6th","7"=>"7th","8"=>"8th","9"=>"9th","10"=>"10th","11"=>"11th","12"=>"12th","13"=>"13th","14"=>"14th","15"=>"15th","16"=>"16th","17"=>"17th","18"=>"18th","19"=>"19th","20"=>"20th","21"=>"21st","22"=>"22nd","23"=>"23rd","24"=>"24th","25"=>"25th");	
								 $ctr=1;	
                                 $qty=$this->session->userdata('no_of_person');									
								 while($ctr<=$qty)
								 {
								?>
									<div class="lg-booking-form-heading">
										<span><?php echo $arr[$ctr];?></span>
										<h3>Passenger Information</h3>
									</div>                            
									<div class="personal-info">                                
										<div class="row">
											<div class="col-xs-4 col-sm-4">
												<div class="form-group right-icon">	
													<select name="prefix[]"  class="form-control">
														 <option value="Mr.">Mr.</option>
														 <option value="Miss">Miss</option>
														 <option value="Mrs.">Mrs.</option>
														 <option value="Master">Master</option>
													</select>
													<i class="fa fa-angle-down"></i>
												</div>
											</div>
											
											<div class="col-xs-4 col-sm-4">
												<div class="form-group">												
													<!-- <input type="text" class="form-control" name="first_name[]" placeholder="First Name" value="<?php echo $flight[0]["first_name"]; ?>"required/> -->
													<input type="text" class="form-control" name="first_name[]" placeholder="First Name" value="" required/>
												</div>
											</div>
											
											<div class="col-xs-4 col-sm-4">
												<div class="form-group">													
													<input type="text" class="form-control" name="last_name[]" placeholder="Last Name" value="" required/>
												</div>
											</div>
										</div>
										<div class="row" style="<?php echo $ctr>1?'display:none':''?>">
											<div class="col-xs-4 col-sm-4">
												<div class="form-group">													
													<input type="text" maxlength="10" name="mobile_no[]" class="form-control" placeholder="Mobile No." value="<?php echo $flight[0]["mobile_no"]; ?>" required/>
												</div>
											</div>
											<div class="col-xs-4 col-sm-4">
												<div class="form-group right-icon">													
													<input type="email" name="email[]" class="form-control" placeholder="Email" value="<?php echo $flight[0]["user_email"]; ?>" required/>
												</div>
											</div>
											<div class="col-xs-4 col-sm-4" style="display:none">
												<div class="form-group">													
													<input type="number" maxlength="10" min="1" name="age[]" class="form-control" placeholder="Age" value=""/>
												</div>
											</div>
											
										</div>                                                                                                                                                                          
									</div> 
								<?php
                                  $ctr++;								
								 }
								}
								?>
                                <button type="submit" class="btn btn-orange">Confirm Booking</button>
                            </form>
                            
                        </div><!-- end columns -->

                    </div><!-- end row -->
                </div><!-- end container -->         
            </div><!-- end flight-booking -->
        </section><!-- end innerpage-wrapper -->
        
        
       