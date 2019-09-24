		<!-- end flexslider-container -->
		<!--================= New Home - Header section ===================-->
		<section id="head-container" class="head-container">
			<div class="container-fluid full-background">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
						<div class="bg">
							<?php 
							$name = '';
							if($this->session->userdata('name')!==null) {
								$name = explode(" ", $this->session->userdata('name'))[0];
							}
							?>
							<h1 class="new-typewriter">
								<a href="" class="typewrite" data-period="2000" data-type='[ "Hi! <?php echo $name?>, welcome to OxyTra.", "Nothing is important to us, than seeing smile on your face.", "We value our <i><u>Travel Partners</u></i>, to give best support to our customer", "There is only one <b>Boss!</b>, <strong><u>The Customer</u></strong> &#9786;"]'>
									<span class="wrap"></span>
								</a>
							</h1>							
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--================= END:: New Home - Header section ===================-->
		<!--================= KPI section =======================================-->
		<section id="kpislides" class="container-fluid section-padding">
			<div class="row">
				<div class="col-sm-12">
					<div class="page-heading">
						<h2>Our Offerings</h2>
						<hr class="heading-line" />
					</div><!-- end page-heading -->					
				</div>
			</div>
			<div class="row">
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
					<div class="flexslider carousel">
						<ul class="slides">
							<li>
								<img src="/images/flight_ads.png"/>
							</li>
							<li>
								<img src="/images/tickets_ad.png"/>
							</li>
							<li>
								<img src="/images/travelagent.png"/>
							</li>
							<li>
								<img src="/images/trip-africa-2.jpg"/>
							</li>
							<li>
								<img src="/images/trip-africa-2.jpg"/>
							</li>
							<li>
								<img src="/images/trip-africa-2.jpg"/>
							</li>
							<li>
								<img src="/images/trip-africa-2.jpg"/>
							</li>
							<li>
								<img src="/images/trip-africa-2.jpg"/>
							</li>
							<li>
								<img src="/images/trip-africa-2.jpg"/>
							</li>
							<!-- items mirrored twice, total of 12 -->
						</ul>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
				</div>
			</div>
		</section>
		<!--================= End of KPI section ================================-->
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

<script language="javascript">
	var TxtType = function(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 2000;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    };

    TxtType.prototype.tick = function() {
        var i = this.loopNum % this.toRotate.length;
        var fullTxt = this.toRotate[i];

        if (this.isDeleting) {
        	this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
        	this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

        var that = this;
        var delta = 200 - Math.random() * 100;

        if (this.isDeleting) { delta /= 2; }

        if (!this.isDeleting && this.txt === fullTxt) {
			delta = this.period;
			this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
			this.isDeleting = false;
			this.loopNum++;
			delta = 500;
        }

        setTimeout(function() {
	        that.tick();
        }, delta);
    };

    window.onload = function() {
        var elements = document.getElementsByClassName('typewrite');
        for (var i=0; i<elements.length; i++) {
            var toRotate = elements[i].getAttribute('data-type');
            var period = elements[i].getAttribute('data-period');
            if (toRotate) {
              new TxtType(elements[i], JSON.parse(toRotate), period);
            }
        }
        // INJECT CSS
        var css = document.createElement("style");
        css.type = "text/css";
        css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff}";
        document.body.appendChild(css);
	};
	
	// $(window).load(function() {
	// 	$('.flexslider1').flexslider({
	// 		animation: "slide",
	// 		animationLoop: false,
	// 		itemWidth: 210,
	// 		itemMargin: 5
	// 	});
	// });	
</script>
       
        