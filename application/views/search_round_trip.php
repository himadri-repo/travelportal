        		
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
        
        
      
		
		
		<section class="innerpage-wrapper" style="top:0;margin-top:100px">
        	<div id="flight-listings" class="innerpage-section-padding" style="padding-top:0;">
                <div class="container">
                    <div class="row">        	
                        <div class="col-xs-12 col-sm-12 col-md-4 side-bar right-side-bar">
						   <div class="page-search-form" style="padding: 30px 10px 45px 10px;">
                            	<h2  style="font-size:14px">Search the <span>Flight <i class="fa fa-plane"></i></span></h2>
                                
                                <ul class="nav nav-tabs">
								    <li ><a href="#tab-one-way" data-toggle="tab">One Way</a></li>
                                	<li class="active"><a href="#tab-round-trip" data-toggle="tab">Round Trip</a></li>
                                	
                                </ul>
                                
                                <div class="tab-content">
                                	<div id="tab-round-trip" class="tab-pane fade in active">
                                       <form class="pg-search-form" id="frm_one_way" action="<?php echo base_url(); ?>search/search_round_trip" method="post" onsubmit="return validation1()" autocomplete="off">
										   <input type="hidden" name="trip_type" value="ONE"> 
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>From</label>
                                                        <select class="form-control" name="source1" id="source1">
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
                                                         <select class="form-control" name="destination1" id="destination1">
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
                                                        <!--<input class="form-control dpd3" placeholder="Date" name="departure_date_time1" id="departure_date_time1" value="<?php $dt=date("d-m-Y",strtotime($post[0]["departure_date_time"])); echo $dt; ?>" autocomplete="off"/>-->
														<select class="form-control" name="departure_date_time1" id="departure_date_time1">
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
														<select class="form-control" name="no_of_person1" id="no_of_person1">														 
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
                                    </div>
                                    
                                    <div id="tab-one-way" class="tab-pane fade in">
                                        <form class="pg-search-form" id="frm_one_way" action="<?php echo base_url(); ?>search/search_one_way" method="post" onsubmit="return validation()">
										   <input type="hidden" name="trip_type" value="ONE"> 
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>From</label>
                                                        <select class="form-control" name="source" id="source">
													      <option value="">Source</option>
														  <?php
														  foreach($city2 as $key=>$value)
														  {
														  ?>
															<option value="<?php echo $city2[$key]["id"];?>"><?php echo $city2[$key]["city"];?></option>
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
														  <!--<?php
														  foreach($city as $key=>$value)
														  {
														  ?>
														  <option value="<?php echo $city[$key]["id"];?>"><?php echo $city[$key]["city"];?></option>
														  <?php
														  }
														   ?>-->										  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-calendar"></i></span>Available Date</label>
                                                        <!--input class="form-control dpd3" placeholder="Date" name=" _date_time" id="departure_date_time" value="" autocomplete="off"/>-->
														 <select class="form-control" name="departure_date_time" id="departure_date_time">
                                                         </select> 
                                                    </div>

                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-users"></i></span>Passengers</label>                                                        
														<select class="form-control" name="no_of_person" id="no_of_person">														 
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
                                    </div><!-- end tab-one-way -->
                                </div><!-- end tab-content -->
                            </div><!-- end page-search-form -->
						</div>
                        <div class="col-xs-12 col-sm-12 col-md-8 content-side" id="top_div">
                             <?php
							  
							   if(!empty($flight))
								{
									foreach($flight as $key=>$value)
									{
									    
									    
								?>
								<div class="list-block main-block f-list-block">
									<div class="list-content">
									
										<div class="main-img list-img f-list-img">
											<a href="#">
												<div class="f-img" style="padding:0 0 15px 0">
													<?php if($flight[$key]["sale_type"]=="quote"){ ?>
													<img src="<?php echo base_url(); ?>upload/thumb/flight.png" class="img-responsive" style="max-width:100px;" alt="flight-img" />
													<?php } else { ?>
													<img src="<?php echo base_url(); ?>upload/thumb/<?php echo $flight[$key]["image"];?>" class="img-responsive" style="max-width:100px;" alt="flight-img" />
												    <?php } ?>
												</div><!-- end f-list-img -->
											</a>
											
											<ul class="list-unstyled list-inline offer-price-1">
												<li class="duration"><i class="fa fa-plane"></i><span>ROUND TRIP</span></li>
												<?php if($flight[$key]["sale_type"]!="quote"){ ?>
													<?php  if($flight[$key]["user_id"]==$this->session->userdata('user_id')){?>
													<li class="price"><i class="fa fa-inr"></i><?php echo number_format($flight[$key]["total"],2,".",","); ?></li>
													<?php } else {?>
													<li class="price"><i class="fa fa-inr"></i><?php echo number_format(($flight[$key]["total"]+$flight[$key]["admin_markup"]),2,".",","); ?></li>
													<?php } ?>
												<?php }?>
                                               											
											</ul>
											<ul class="list-unstyled flight-timing">
												
												<li><span><i class="fa fa-bed" style="transform:rotate(0deg)"></i></span><span class="date"><?php echo $flight[$key]["class"]." Class"; ?></li>
											</ul>
										</div><!-- end f-list-img -->
										
										<div class="list-info f-list-info"  style="padding:30px 30px 0 30px;">
										    
											<h3 class="block-title" style="font-size:12px !important"><a href="#" style="font-size:12px"><?php echo $flight[$key]["source_city"]; ?> To <?php echo $flight[$key]["destination_city"]; ?></a></h3>
											<?php if($flight[$key]["sale_type"]!="quote"){ ?>
											<p class="block-minor"><span><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo date("jS M y",strtotime($flight[$key]["departure_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[$key]["departure_date_time"])); ?>)&nbsp;&nbsp;,</span> <span><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo date("jS M y",strtotime($flight[$key]["arrival_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[$key]["arrival_date_time"])); ?>)</span></p>
											<h3 class="block-title"><a href="flight-detail-right-sidebar.html"><?php echo $flight[$key]["source_city1"]; ?> To <?php echo $flight[$key]["destination_city1"]; ?></a></h3>
											<p class="block-minor"><span><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo date("jS M y",strtotime($flight[$key]["departure_date_time1"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[$key]["departure_date_time1"])); ?>)&nbsp;&nbsp;,</span> <span><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo date("jS M y",strtotime($flight[$key]["arrival_date_time1"])); ?> </span>(<?php echo date("h:i a",strtotime($flight[$key]["arrival_date_time1"])); ?>)</span></p>
											<?php } else {?>
											<p class="block-minor"><span><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo date("jS M y",strtotime($flight[$key]["departure_date_time"])); ?> </span></p>
											<p class="block-minor">&nbsp;</p>
											<p class="block-minor">&nbsp;</p>
											<?php } ?>
											<?php if($flight[$key]["sale_type"]!="quote"){ ?>
											<p class="block-minor"><span><i class="fa fa-users"></i></span>&nbsp;&nbsp;<?php echo $flight[$key]["no_of_person"]; ?>&nbsp;&nbsp; SEATS,&nbsp;&nbsp;<i class="fa fa-plane"></i>&nbsp;<?php echo $flight[$key]["no_of_stops"];?>&nbsp;STOP,&nbsp;&nbsp;<i class="fa fa-plane" style="transform: rotate(83deg);"></i>&nbsp;<?php echo $flight[$key]["no_of_stops"];?>&nbsp;STOP</span></p>
											<?php } ?>
											<p class="block-minor">
											<?php if($flight[$key]["sale_type"]!="quote"){ ?>
											<span><?php if($flight[$key]["refundable"]=="Y") echo "Refundable";else echo "Not Refundable";?></span> 
											<?php } ?>
											<?php if($flight[$key]["sale_type"]=="request"){ ?>
												<a style="margin-top:0" href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange">REQUEST NOW</a>
											<?php } ?>
											
											<?php if($flight[$key]["sale_type"]=="live"){ ?>
												<a style="margin-top:0" href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange">BOOK NOW</a>
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