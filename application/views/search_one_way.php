         
        <!--
        <section class="page-cover back-size" id="cover-flight-search">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Flight Search Result</h1>
                        <ul class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li class="active">Flight Search Result</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section> -->
		
		<section class="innerpage-wrapper" style="top:0;margin-top:20px">
        	<div id="flight-listings" class="innerpage-section-padding" style="padding-top:0;">
                <div class="container">
                    <div class="row">        	
                         <div class="col-xs-12 col-sm-12 col-md-4 side-bar right-side-bar">
                                        
                            <div class="page-search-form" style="padding: 30px 10px 45px 10px;">
                            	<h2 style="font-size:14px">Search the <span>Flight <i class="fa fa-plane"></i></span></h2>
                                
                                <ul class="nav nav-tabs">
								    <li class="active"><a href="#tab-one-way" data-toggle="tab">One Way</a></li>
                                	<li ><a href="#tab-round-trip" data-toggle="tab">Round Trip</a></li>
                                	
                                </ul>
                                
                                <div class="tab-content">
                                	<div id="tab-round-trip" class="tab-pane fade in ">
                                        <form class="pg-search-form" id="frm_one_way" action="<?php echo base_url(); ?>search/search_round_trip" method="post" onsubmit="return validation1()">
										   <input type="hidden" name="trip_type" value="ROUND"> 
                                            <div class="row">
                                                 <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>From</label>
                                                        <select class="form-control" name="source1" id="source1">
													      <option value="">Source</option>
														  <?php
														  if($city2 && count($city2)>0) {
															foreach($city2 as $key=>$value)
															{
															?>
																<option value="<?php echo $city2[$key]["id"];?>"><?php echo $city2[$key]["city"];?></option>
															<?php
															}
														  }
														   ?>										  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                 <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>To</label>
                                                         <select class="form-control" name="destination1" id="destination1">
													      <!--<option value="">Destination</option>
														  <?php
														  foreach($city as $key=>$value)
														  {
														  ?>
														  <option value="<?php echo $city[$key]["id"];?>"><?php echo $city[$key]["city"];?></option>
														  <?php
														  }
														   ?>	-->									  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                 <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-calendar"></i></span>Available Date</label>
														<!--<input class="form-control dpd3" placeholder="Date" name="departure_date_time1" id="departure_date_time1"/>-->
														<input class="form-control datepicker" placeholder="dd/mm/yyyy" name="departure_date1" id="departure_date1" readonly/>
														<select class="form-control" style="display:none" name="departure_date_time1" id="departure_date_time1">
                                                        </select> 
                                                    </div>

                                                </div><!-- end columns -->
                                                
                                                 <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-users"></i></span>Passengers</label>                                                        
														<select class="form-control" name="no_of_person1" id="no_of_person1">														 
														  <option value="1">1</option>
														  <option value="2">2</option>
														  <option value="3">3</option>
														  <option value="4">4</option>
														  <option value="5">5</option>
														  <option value="6">6</option>
														  <option value="7">7</option>
														  <option value="8">8</option>
														  <option value="9">9</option>
														  
														</select>
													</div>
                                                </div>
                                            </div><!-- end row -->
                                            
                                            <button type="submit" class="btn btn-orange" id="btn_one_way">Search</button>
                                        </form>
                                    </div>
                                    
                                    <div id="tab-one-way" class="tab-pane fade in active">
                                        <form class="pg-search-form" id="frm_one_way" action="<?php echo base_url(); ?>search/search_one_way" method="post" onsubmit="return validation()">
										   <input type="hidden" name="trip_type" value="ONE"> 
                                            <div class="row">
                                                 <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>From</label>
                                                        <select class="form-control" name="source" id="source">
													      <option value="">Source</option>
														  <?php
														  foreach($city as $key=>$value)
														  {
														  ?>
															<option value="<?php echo $city[$key]["id"];?>" <?php if($post[0]["source"]==$city[$key]["id"]) echo "selected"; ?>><?php echo $city[$key]["city"];?></option>
														  <?php
														  }
														   ?>										  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                 <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>To</label>
                                                         <select class="form-control" name="destination" id="destination">
													      <option value="">Destination</option>
														  <?php
														  foreach($city1 as $key=>$value)
														  {
														  ?>
														  <option value="<?php echo $city1[$key]["id"];?>" <?php if($post[0]["destination"]==$city1[$key]["id"]) echo "selected"; ?>><?php echo $city1[$key]["city"];?></option>
														  <?php
														  }
														   ?>										  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-calendar"></i></span>Available Date</label>
														<!--<input class="form-control dpd3" placeholder="Date" name="departure_date_time" id="departure_date_time" value="<?php $dt=date("d-m-Y",strtotime($post[0]["departure_date_time"])); echo $dt; ?>"/>-->
														<!-- value= "$dt=date("d-M-Y",strtotime($post[0]["departure_date_time"])); echo $dt;" -->
														<?php 
															$dt = $post[0]["departure_date_time"];
															//echo "posted departure_date_time" . $post[0]["departure_date_time"];
															if(isset($post[0]["departure_date"])) {
																//echo "posted departure_date" . $post[0]["departure_date"];
																$dt = $post[0]["departure_date"];
															}
														?>
														<input class="datepicker" placeholder="dd/mm/yyyy" name="departure_date" id="departure_date" readonly value="<?php echo $dt; ?>"/>
														<select class="form-control" style="display:none" name="departure_date_time" id="departure_date_time">
                                                        <?php
														  foreach($availalble as $key=>$value)
														  {
														  ?>
														  <option value="<?php echo $availalble[$key]["departure_date_time"];?>" <?php if($post[0]["departure_date_time"]==$availalble[$key]["departure_date_time"]) echo "selected"; ?>><?php echo $availalble[$key]["departure_date_time"];?></option>
														  <?php
														  }
														   ?>		
														</select> 
                                                    </div>

                                                </div><!-- end columns -->
                                                
                                                  <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-users"></i></span>Passengers</label>                                                        
														<select class="form-control" name="no_of_person" id="no_of_person">
														
														  <option value="1"  <?php if($post[0]["no_of_person"]==1) echo "selected"; ?>>1</option>
														  <option value="2"  <?php if($post[0]["no_of_person"]==2) echo "selected"; ?>>2</option>
														  <option value="3"  <?php if($post[0]["no_of_person"]==3) echo "selected"; ?>>3</option>
														  <option value="4"  <?php if($post[0]["no_of_person"]==4) echo "selected"; ?>>4</option>
														  <option value="5"  <?php if($post[0]["no_of_person"]==5) echo "selected"; ?>>5</option>
														  <option value="6"  <?php if($post[0]["no_of_person"]==6) echo "selected"; ?>>6</option>
														  <option value="7"  <?php if($post[0]["no_of_person"]==7) echo "selected"; ?>>7</option>
														  <option value="8"  <?php if($post[0]["no_of_person"]==8) echo "selected"; ?>>8</option>
														  <option value="9"  <?php if($post[0]["no_of_person"]==9) echo "selected"; ?>>9</option>
														  
														</select>
													</div>
                                                </div>
                                            </div><!-- end row -->
                                            
                                            <button type="submit" class="btn btn-orange" id="btn_one_way">Search</button>
                                        </form>
                                    </div><!-- end tab-one-way -->
                                </div><!-- end tab-content -->
                            </div><!-- end page-search-form -->
                            
                            
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-8 content-side" id="top_div">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="z-index: 999; background-color: #faa61a; color: #ffffff; font-size: 15pt; text-align: right; display: <?php echo (($currentuser['is_admin']=='1' || $currentuser['type']=='B2B') ? 'block': 'none'); ?>">
								<input type="checkbox" id="showcostprice" name="showcostprice" checked style="display: inline-block; width: 12pt; height: 12pt; cursor: pointer;"/>
								<span>Show cost price</span>
							</div>
                             <?php
								
								if(!empty($flight))
								{
									foreach($flight as $key=>$value)
									{
									    
									    
								?>
								
								<?php 
								$class = "";
								//echo $flight[$key]["user_id"] . " - " . $this->session->userdata('user_id');
								if($flight[$key]["user_id"]==$this->session->userdata('user_id')) {
									//echo "supplier_ticket";
									$class = "supplier_ticket";
								} 
								else {
									$class = "";
									//echo "<br/>NOT supplier_ticket";
								}
								?>
								<div class="list-block main-block f-list-block <?php echo $class?>">
									<div class="list-content">
									
										<div class="main-img list-img f-list-img">
											<div class="row">
												<div class="col-xs-3 col-sm-3 col-md-3">
													<a href="#">
														<div class="f-img" style="padding:2px 0 0px 2px">
															<?php if($flight[$key]["sale_type"]=="quote"){ ?>
															<img src="<?php echo base_url(); ?>upload/thumb/flight.png" class="img-responsive" style="max-width:70px;" alt="flight-img" />
															<?php } else { ?>
															<img src="<?php echo base_url(); ?>upload/thumb/<?php echo $flight[$key]["image"];?>" class="img-responsive" style="max-width:70px;" alt="flight-img" />
															<?php } ?>
														</div><!-- end f-list-img -->
													</a>
												</div> <!-- end of left column -->
												<div class="col-xs-9 col-sm-9 col-md-9">
													<ul class="list-unstyled flight-timing <?php echo $class?>">
														<?php if($flight[$key]["sale_type"]!="quote"){ ?>
														<?php if($flight[$key]["adult_total"]>0) {?>
															<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($flight[$key]["dept_date_time"])); ?> </span>(<?php echo date("H:i",strtotime($flight[$key]["dept_date_time"])); ?>)</li>
															<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($flight[$key]["arrv_date_time"])); ?> </span>(<?php echo date("H:i",strtotime($flight[$key]["arrv_date_time"])); ?>)</li>
														<?php }
														else {?>
															<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($flight[$key]["departure_date_time"])); ?> </span>(<?php echo date("H:i",strtotime($flight[$key]["departure_date_time"])); ?>)</li>
															<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($flight[$key]["arrival_date_time"])); ?> </span>(<?php echo date("H:i",strtotime($flight[$key]["arrival_date_time"])); ?>)</li>
														<?php }
														 } ?>
													</ul>
												</div> <!-- end of column -->
											</div> <!-- end of row -->
											<div class="row">
												<div class="col-xs-12 col-sm-12 col-md-12">
													<div class="f-fl-title">
														<?php
															$splcode = 'special fare';
															if((intval($flight[$key]["companyid"])===intval($currentuser['companyid'])) && intval($currentuser['is_admin'])===1) {
																if($flight[$key]["data_collected_from"]=='airiq') {
																	$splcode = 'oxytra-spl-1';
																}
																else if($flight[$key]["data_collected_from"]=='e2f') {
																	$splcode = 'oxytra-spl-2';
																}
																else if($flight[$key]["data_collected_from"]=='moh') {
																	$splcode = 'oxytra-spl-3';
																}
																else if($flight[$key]["data_collected_from"]=='mair') {
																	$splcode = 'oxytra-spl-4';
																}
																else if($flight[$key]["data_collected_from"]=='doshi') {
																	$splcode = 'oxytra-spl-5';
																}
																else if($flight[$key]["data_collected_from"]=='mpt') {
																	$splcode = 'oxytra-spl-6';
																}
																else if($flight[$key]["data_collected_from"]=='tmz') {
																	$splcode = 'oxytra-spl-7';
																}
																else if($flight[$key]["data_collected_from"]=='indr') {
																	$splcode = 'oxytra-spl-8';
																}
															}
														?>
														<span><?php echo $flight[$key]["aircode"] . '-' . $flight[$key]["flight_no"]?></span>
														<span style="display:block; float: right; font-size:9px; padding: 0px 5px">(<?php echo $splcode?>)</span>
													</div>
												</div> <!-- end of column -->
											</div> <!-- end of row -->

											<?php
											
											//$dateDiff = intval((strtotime($flight[$key]["arrival_date_time"])-strtotime($flight[$key]["departure_date_time"]))/60);
											if($flight[$key]["adult_total"]>0) {
												$dateDiff = intval((strtotime($flight[$key]["arrv_date_time"])-strtotime($flight[$key]["dept_date_time"]))/60);
											}
											else {
												$dateDiff = intval((strtotime($flight[$key]["arrival_date_time"])-strtotime($flight[$key]["departure_date_time"]))/60);
											}
											
											?>
											<ul class="list-unstyled list-inline offer-price-1">
											    <?php if($flight[$key]["sale_type"]!="quote"){ ?>
													<li class="duration"><i class="fa fa-clock-o"></i><span><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></span></li>
												<?php } else {?>
												<!-- <li class="duration"><i class="fa fa-clock-o"></i><span><?php echo date("jS M y",strtotime($flight[$key]["departure_date_time"])); ?></span></li> -->
													<li class="duration"><i class="fa fa-clock-o"></i><span><?php echo date("jS M y",strtotime($flight[$key]["dept_date_time"])); ?></span></li>
												<?php } ?>
												
												<?php  if($flight[$key]["user_id"]==$this->session->userdata('user_id')){?>
													<?php if($flight[$key]["adult_total"]>0) {?>
														<li class="live-price"><?php if($flight[$key]["sale_type"]!="quote") echo "<i class='fa fa-inr'></i> ".number_format($flight[$key]["adult_total"],2,".",",").' (live)'; ?></li>
													<?php }?>
														<?php
														// $final_total = $flight[$key]["total"] + $flight[$key]["splr_markup"] + $flight[$key]["splr_srvchg"] + $flight[$key]["wsl_markup"] + $flight[$key]["wsl_srvchg"] + $flight[$key]["cgst"] + $flight[$key]["sgst"];
														$final_total = $flight[$key]["price"];
														?>
														<li class="price"><?php if($flight[$key]["sale_type"]!="quote") echo "<i class='fa fa-inr'></i> ".number_format($final_total,2,".",","); ?></li>
												<?php } else {?>
													<?php if($flight[$key]["adult_total"]>0) {?>
														<li class="live-price"><?php if($flight[$key]["sale_type"]!="quote") echo "<i class='fa fa-inr'></i> ".number_format($flight[$key]["adult_total"],2,".",",").' (live)'; ?></li>
													<?php }?>
														<?php
														// $final_total = $flight[$key]["total"] + $flight[$key]["splr_markup"] + $flight[$key]["splr_srvchg"] + $flight[$key]["wsl_markup"] + $flight[$key]["wsl_srvchg"] + $flight[$key]["cgst"] + $flight[$key]["sgst"];
														$final_total = $flight[$key]["price"];
														if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
															$final_total += $flight[$key]["admin_markup"];
														}
														?>
														<li class="price"><?php if($flight[$key]["sale_type"]!="quote") echo "<i class='fa fa-inr'></i> ".number_format($final_total,2,".",","); ?></li>
												<?php } ?>
												
												<?php if($currentuser['is_admin']=='1') { ?>
													<?php 
													//$costprice = $flight[$key]["total"] + $flight[$key]["whl_markup"] + $flight[$key]["whl_srvchg"] + ($flight[$key]['whl_srvchg'] * $flight[$key]['whl_cgst'] / 100) + ($flight[$key]['whl_srvchg'] * $flight[$key]['whl_sgst'] / 100);
													$costprice = $flight[$key]["total"] + $flight[$key]["spl_markup"] + $flight[$key]["spl_srvchg"] + ($flight[$key]['spl_srvchg'] * $flight[$key]['spl_cgst'] / 100) + ($flight[$key]['spl_srvchg'] * $flight[$key]['spl_sgst'] / 100);
													?>
													<li class="costprice"><?php if($flight[$key]["sale_type"]!="quote") echo "<i class='fa fa-info'></i><span> Your cost price</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr'></i> ".number_format($costprice,2,".",","); ?></li>
												<?php } else {
													if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
														$costprice = $flight[$key]["price"]; ?>
														<li class="costprice"><?php if($flight[$key]["sale_type"]!="quote") echo "<i class='fa fa-info'></i><span> Your cost price</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr'></i> ".number_format($costprice,2,".",","); ?></li>
													<?php }?>
												<?php } ?>
											</ul>
											<!--<ul class="list-unstyled flight-timing <?php echo $class?>">
											    <?php if($flight[$key]["sale_type"]!="quote"){ ?>
												<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($flight[$key]["departure_date_time"])); ?> </span>(<?php echo date("H:i",strtotime($flight[$key]["departure_date_time"])); ?>)</li>
												<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($flight[$key]["arrival_date_time"])); ?> </span>(<?php echo date("H:i",strtotime($flight[$key]["arrival_date_time"])); ?>)</li>
											    <?php } ?>
											</ul>-->
										</div><!-- end f-list-img -->
										
										<div class="list-info f-list-info <?php echo $class?>" style="padding:5px 5px 0 5px;">
											<!--<h3 class="block-title" style="font-size:12px !important"><a href="#"  style="font-size:12px"><?php echo $flight[$key]["source_city"]; ?> To <?php echo $flight[$key]["destination_city"]; ?></a>&nbsp;&nbsp; ( Oneway Flight )</h3> -->
											<ul class="list-unstyled flight-timing <?php echo $class?>">
												<li><span><i class="fa fa-plane"></i></span><span class="circle_city"><?php echo $flight[$key]["source_city"]; ?></li>
												<li><span><i class="fa fa-plane"></i></span><span class="circle_city"><?php echo $flight[$key]["destination_city"]; ?></li>
											</ul>
											<div class="row">
												<div class="col-xs-6 col-sm-6 col-md-6" style="border-right: 1px solid #dedfe0;">
													<?php if($flight[$key]["sale_type"]!="quote"){ ?>
														<div class="block-minor" style="font-size:12px">
															<span>
																<i class="fa fa-users"></i>
																<div class="seats-title"><?php echo $flight[$key]["no_of_person"];?>&nbsp;&nbsp; seat(s) left,</div>
																<div class="stop-title"><i class="fa fa-plane"></i>&nbsp;<?php echo ($flight[$key]["no_of_stops"]>0?$flight[$key]["no_of_stops"].'Stop':'Direct')?></div>
															</span>
														</div>
													<?php } else {?>
														<p class="block-minor">&nbsp;</p>
													<?php } ?>
												</div> <!-- end of column -->
												<div class="col-xs-6 col-sm-6 col-md-6">
													<?php if($flight[$key]["sale_type"]!="quote"){ ?>
														<p class="block-minor" style="float:right"><span><i class="fa fa-hotel"></i>&nbsp;&nbsp;<?php echo $flight[$key]["class"]." Class";?></span></p>
													<?php } else {?>
														<p class="block-minor" style="float:right">&nbsp;</p>
													
													<?php } ?>
												</div> <!-- end of column -->
											</div> <!-- end of row -->
											<p class="block-minor" style="margin: 25px 0px 0px 0px;">
												<?php if($flight[$key]["sale_type"]!="quote"){ ?>
												<span><?php if($flight[$key]["refundable"]=="Y") echo "Refundable";else echo "Non Refundable";?></span> 
												<?php } ?>
												<?php if($flight[$key]["sale_type"]=="request"){ ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange">REQUEST NOW</a>
												<?php } ?>
											
												<?php if($flight[$key]["sale_type"]=="live"){ ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange">BOOK NOW</a>
												<?php } ?>
												
												<?php if($flight[$key]["sale_type"]=="quote"){ ?>
												<!--<a style="margin-top:0" href="<?php echo base_url(); ?>search/sendquote/<?php echo $flight[$key]["id"];?>" class="btn btn-orange">GET QUOTE</a>-->
												<a style="cursor:pointer" class="btn btn-orange btn_send_quote_request" data-toggle="modal" data-target="#getquote" color="<?php echo $flight[$key]["id"];?>">GET QUOTE</a>
												
												<?php } ?>
											</p>
											
										</div><!-- end f-list-info -->
									</div><!-- end list-content -->
								</div><!-- end f-list-block -->
								<?php
									    
								    }
								}
                                else
								{
									?>
									   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<div class="page-heading mg-bot-55">
											<h2>No Result Found !!!</h2>
											<hr class="heading-line">
										</div>
									  </div>	
									<?php
								}									
								  
								?>
                            <!--<div class="pages">
                                <ol class="pagination">
                                    <li><a href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-angle-left"></i></span></a></li>
                                    <li class="active"><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#" aria-label="Next"><span aria-hidden="true"><i class="fa fa-angle-right"></i></span></a></li>
                                </ol>
                            </div>-->
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end flight-listings -->
        </section><!-- end innerpage-wrapper -->
        <div id="getquote" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Enter No. of Passengers</h3>
                    </div><!-- end modal-header -->
                    
                    <div class="modal-body">
                            <form id="frm_sendquote" action="" method="POST">
                        	<div class="form-group">                        		
                            	<input type="number" class="form-control" placeholder="Enter No. of Passengers" name="no_of_person" id="no_of_person" maxlength="10" />
                            </div><!-- end form-group -->
                             <button type="submit" class="btn btn-orange">Submit</button>
								<div class="form-group" id="forgot_status">
							 </div>
							</form>
                        
                    </div><!-- end modal-bpdy -->
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-profile -->

		<script language="javascript">
			$( document ).ready(function() {
				$("#showcostprice").change(function() {
					if(this.checked) {
						$('.costprice').show();
					} else {
						$('.costprice').hide();
					}
				});
			});
		</script>
