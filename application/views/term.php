
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
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-side" style="height: 750px;">
                          <?php if($term) {?>
                            <iframe src="<?php echo base_url(); ?>/<?= $term[0]['filepath'] ?>" style="width: 100%; height: 100%;" scrolling="yes" frameborder="0" marginwidth="0" marginheight="0">
                            </iframe>
                          <?php } ?>
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->   
            </div><!-- end travel-guide -->
        </section><!-- end innerpage-wrapper -->
        
        
       