
		<section class="page-cover" id="cover-travel-guide">
		  <div class="container">
			<div class="row">
			  <div class="col-sm-12">
				<h1 class="page-title">Term & Conditions</h1>
				<ul class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Term & Conditions</li>
				</ul>
			  </div><!-- end columns -->
			</div><!-- end row -->
		  </div><!-- end container -->
		</section>
				
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="travel-guide" class="innerpage-section-padding">
                <div class="container">
				    <?php						 
					 if(!empty($term))
					 {
					 ?>
                    <div class="row">
                         
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 content-side">
                        	<div class="detail-tabs"> 
							    <?php
							  $ctr=1; 
							  foreach($term as $key=>$value)
							  {
							  ?>
                                <div id="tv-guide-overview" class="tab-pane active">							
                                <div class="tab-content">
                                  <div class="tab-text">
								     <h3><?php echo $ctr.". ".$term[$key]["title"]; ?></h3>
									 <?php echo $term[$key]["description"]; ?>
                                  </div>                                  	                                                                                                                                                                                                                                                                                       
                                </div><!-- end tab-content -->
								</div>
								<?php
								$ctr++;
							  }
								?>																													
                            </div><!-- end detail-tabs -->
                        </div><!-- end columns -->
						
                        
                        <div class="col-xs-12 col-sm-12 col-md-3 side-bar right-side-bar">
                        	
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-12">
                                    <div class="side-bar-block main-block ad-block">
                                        <div class="main-img ad-img">
                                            <a href="#">
                                                <img src="images/car-ad.jpg" class="img-responsive" alt="car-ad" />
                                                <div class="ad-mask">
                                                    <div class="ad-text">
                                                        <span>Luxury</span>
                                                        <h2>Car</h2>
                                                        <span>Offer</span>
                                                    </div><!-- end ad-text -->
                                                </div><!-- end columns -->
                                            </a>
                                        </div><!-- end ad-img -->
                                    </div><!-- end side-bar-block -->
                                </div><!-- end columns -->
                                
                                 <div class="col-xs-12 col-sm-6 col-md-12">    
                                    <div class="side-bar-block support-block">
                                        <h3><?php echo $need_help[0]["title"]; ?></h3>
                                         <p><?php echo $need_help[0]["description"]; ?>.</p>
                                        <div class="support-contact">
                                            <span><i class="fa fa-phone"></i></span>
                                            <p><?php echo $setting[0]["fax"];?></p>
                                        </div><!-- end support-contact -->
                                    </div><!-- end side-bar-block -->
                                </div><!-- end columns -->
                                
                            </div><!-- end row -->
                        </div><!-- end columns -->
                        
                    </div><!-- end row -->
					<?php  } ?>
                </div><!-- end container -->   
            </div><!-- end travel-guide -->
        </section><!-- end innerpage-wrapper -->
        
        
       