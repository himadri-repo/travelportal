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
                    	<h1 class="page-title">Add New Testimonial</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>">Home</a></li>
                            <li class="active">New Testimonial</li>
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
                        	<form id="frm_ticket" class="lg-booking-form" action="<?php echo base_url();?>user/submit-testimonial" method="POST"  autocomplete="off" > 							    							    
									<input type="hidden" name="trip_type" id="trip_type" value="ONE">
									<div class="lg-booking-form-heading">
										<span><i class="fa fa-info-circle"></i></span>
										<h3>Add Testimonial</h3>
										
									</div>                            
									<div class="personal-info"> 									
										<div class="row">
										
										    <div class="col-xs-12 col-sm-12">
											  <label>Give Your Feedback</label>
											</div>
											<div class="col-xs-12 col-sm-12">
												<div class="form-group right-icon">
													
													<textarea  class="form-control" name="description" id="description" required></textarea>
												</div>
											</div>																																																																					
																					
										</div>	

                                        										
									</div> 								
                                <button type="submit" class="btn btn-orange">SUBMIT</button>
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
        
        
      
