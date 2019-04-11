               		
        <section class="flexslider-container" id="flexslider-container-2">            
            <div class="flexslider slider" id="slider-2">
                <ul class="slides">
                    <?php
					foreach($slider as $key=>$value)
					{
					?>
                    <li class="item-1 back-size" 
					style="background:linear-gradient(rgba(0,0,0,0.3),rgba(0,0,0,0.3)),url(<?php echo base_url(); ?>/upload/thumb/<?php echo $slider[$key]["slider_image_name"]; ?>) 50% 15%;
	background-size:cover;
	height:100%;">
                    	<div class="meta">         
                            <div class="container">
                                <h2><?php echo $slider[$key]["slider_title"]; ?></h2>
                                <?php echo $slider[$key]["slider_description"]; ?>
                            </div>
                        </div>
                    </li>
                    <?php
					}
					?>                   										                                        
                </ul>
            </div>                                  
        </section><!-- end flexslider-container -->


        <!--================= FLIGHT OFFERS =============-->
        <section id="flight-offers" class="section-padding">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-12">
                    	<div class="page-heading">
                        	<h2>Flight Offers</h2>
                            <hr class="heading-line" />
                        </div><!-- end page-heading -->
                        
                        <div class="row">
                            <?php
							if($number>0)
							{
								foreach($best_offer as $key=>$value)
								{
									if($best_offer[$key]["trip_type"]=="ONE")
									{
										$trip_type="ONE WAY";
									}
									else
									{
										$trip_type="RETURN FLIGHT";
									}
								?>
								<div class="col-sm-6 col-md-4">
									<div class="main-block flight-block">
										<a href="#">
											<div class="flight-img">
												<img src="<?php echo base_url(); ?>upload/thumb/<?php echo $best_offer[$key]["image"];?>" class="img-responsive" alt="flight-img" />
											</div>
											
											<div class="flight-info">
												<div class="flight-title">
													<h3 ><span class="flight-destination"><span class="flight-type" style="font-size:12px;"><?php echo $best_offer[$key]["source_city"]; ?> To <?php echo $best_offer[$key]["destination_city"]; ?>&nbsp;&nbsp; <br/>( <?PHP echo $trip_type;?> )</span></h3>
												</div>
												
												<div class=" flight-timing">
													<ul class="list-unstyled">
														 <?php 
														 if($best_offer[$key]["trip_type"]=="ONE")
														 {
														 ?>
															<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($best_offer[$key]["departure_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($best_offer[$key]["departure_date_time"])); ?>)</li>
															<li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($best_offer[$key]["arrival_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($best_offer[$key]["arrival_date_time"])); ?>)</li>
														<?php
														 }
														 else
														 {														 													 
														?>
															 <li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($best_offer[$key]["departure_date_time1"])); ?> </span>(<?php echo date("h:i a",strtotime($best_offer[$key]["departure_date_time1"])); ?>)</li>
															 <li><span><i class="fa fa-plane"></i></span><span class="date"><?php echo date("jS M y",strtotime($best_offer[$key]["arrival_date_time1"])); ?> </span>(<?php echo date("h:i a",strtotime($best_offer[$key]["arrival_date_time1"])); ?>)</li>
														<?php
														 }
														?>
													</ul>
												</div>
												
												<ul class="list-unstyled list-inline offer-price-1">                                                												
													<?php  if($best_offer[$key]["user_id"]==$this->session->userdata('user_id')){?>
													 <li class="price"><i class="fa fa-inr"></i><?php echo number_format(($best_offer[$key]["total"]),2,".",","); ?> <span class="pkg">&nbsp;&nbsp; | &nbsp;&nbsp; <i class="fa fa-users"></i>&nbsp;&nbsp; <?php echo $best_offer[$key]["no_of_person"];?>&nbsp;&nbsp; SEATS AVAILABLE</span></li>
													<?php } else {?>
													<li class="price"><i class="fa fa-inr"></i><?php echo number_format(($best_offer[$key]["total"]+$best_offer[$key]["admin_markup"]),2,".",","); ?> <span class="pkg">&nbsp;&nbsp; | &nbsp;&nbsp; <i class="fa fa-users"></i>&nbsp;&nbsp; <?php echo $best_offer[$key]["no_of_person"];?>&nbsp;&nbsp; SEATS AVAILABLE</span></li>
													<?php } ?>                                                
												</ul>
											</div>
											
										</a>
									</div>
								</div>
								<?php
								}
							}
                            ?>							
                            
                        </div><!-- end row -->
                        
                        <!--<div class="view-all text-center">
                        	<a href="flight-grid-right-sidebar.html" class="btn btn-orange">View All</a>
                        </div> -->
                    </div><!-- end columns -->
                </div><!-- end row -->
        	</div><!-- end container -->
        </section><!-- end flight-offers -->
        
        
        
                
                
        <!--========================= BEST FEATURES =======================-->
        <section id="best-features" class="banner-padding lightgrey-features" style="background:#fff">
        	<div class="container">
        		<div class="row">
				
        			<div class="col-sm-6 col-md-3">
                    	<div class="b-feature-block">
                    		<span><i class="fa fa-inr"></i></span>
                        	<h3><?php echo $first[0]["title"]; ?></h3>
                            <p><?php echo $first[0]["description"]; ?></p>
                        </div><!-- end b-feature-block -->
                   </div><!-- end columns -->
                   
                   <div class="col-sm-6 col-md-3">
                    	<div class="b-feature-block">
                    		<span><i class="fa fa-lock"></i></span>
                        	<h3><?php echo $second[0]["title"]; ?></h3>
                            <p><?php echo $second[0]["description"]; ?></p>
                        </div><!-- end b-feature-block -->
                   </div><!-- end columns -->
                   
                   <div class="col-sm-6 col-md-3">
                    	<div class="b-feature-block">
                    		<span><i class="fa fa-thumbs-up"></i></span>
                        	<h3><?php echo $third[0]["title"]; ?></h3>
                            <p><?php echo $third[0]["description"]; ?></p>
                        </div><!-- end b-feature-block -->
                   </div><!-- end columns -->
                   
                   <div class="col-sm-6 col-md-3">
                    	<div class="b-feature-block">
                    		<span><i class="fa fa-bars"></i></span>
                        	<h3><?php echo $fourth[0]["title"]; ?></h3>
                            <p><?php echo $fourth[0]["description"]; ?></p>
                        </div><!-- end b-feature-block -->
                   </div><!-- end columns -->
				   
                </div><!-- end row -->
        	</div><!-- end container -->
        </section><!-- end best-features -->
        
        
        <!--=============== TESTIMONIALS ===============-->
        <section id="testimonials" class="section-padding">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-12">
                    	<div class="page-heading white-heading">
                        	<h2>Testimonials</h2>
                            <hr class="heading-line" />
                        </div><!-- end page-heading -->
                         <?php
						 
						 if(!empty($testimonial))
						 {
						 ?>
                        <div class="carousel slide" data-ride="carousel" id="quote-carousel">
                            <div class="carousel-inner text-center">
                               <?php
							   $ctr=1;
							   foreach($testimonial as $key=>$value)
							  {
							  ?>
                                <div class="item <?php if($ctr==1) echo "active"; ?>">
                                    <blockquote><?php echo strip_tags($testimonial[$key]["description"]); ?></blockquote>
                                    <!--<div class="rating">
                                        <span><i class="fa fa-star orange"></i></span>
                                        <span><i class="fa fa-star orange"></i></span>
                                        <span><i class="fa fa-star orange"></i></span>
                                        <span><i class="fa fa-star orange"></i></span>
                                        <span><i class="fa fa-star lightgrey"></i></span>
                                    </div> -->
                                    
                                    <small><?php echo $testimonial[$key]["name"]; ?></small>
                                </div><!-- end item -->
                                <?php
								$ctr++;
							  }
								?>
                                
                                
                               
                                
                            </div><!-- end carousel-inner -->
                            
                            <ol class="carousel-indicators">
							    <?php
							   $ctr=0;
							   foreach($testimonial as $key=>$value)
							  {
							  ?>
                                <li data-target="#quote-carousel" data-slide-to="<?php echo $ctr; ?>" class="<?php if($ctr==0) echo "active"; ?>"><img src="<?php echo base_url(); ?>images/client-1.jpg" class="img-responsive"  alt="client-img">
                                </li>
								<?php
							   $ctr++;
							  }
							  ?>
                                
                            </ol>
        
                        </div><!-- end quote-carousel -->
						<?php
						 }
						?>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end testimonials --> 

        
       
        