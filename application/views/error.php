<!doctype html>
<html lang="en">
<head>
        <title>404 ERROR</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="icon" href="images/favicon.png" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900,900i%7CMerriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
        <link rel="stylesheet" id="cpswitch" href="<?php echo base_url(); ?>css/orange.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/responsive.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.colorpanel.css">
    </head>    
    <body>
    
        <!--====== LOADER =====-->
        <div class="loader"></div>
        
        
       
        <!--======================== ERROR-PAGE-2 =====================-->
        <section id="error-page-2"  class="full-page-body full-page-back">
            <div class="full-page-wrapper">
                <div class="full-page-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="full-page-title visible-xs visible-sm">
                                    <h3 class="company-name"><span><i class="fa fa-plane"></i> <?php echo $setting[0]["site_title"]; ?></span></h3>
                                </div><!-- end full-page-title -->
                                        
                                <div class="row">
                                    
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 error-page-2-circle text-center">
                                        <h2>404</h2>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 error-page-2-text">
                                        <div class="full-page-title visible-md visible-lg">
                                            <h3 class="company-name text-left"><span><i class="fa fa-plane"></i> <?php echo $setting[0]["site_title"]; ?></span></h3>
                                        </div><!-- end full-page-title -->
                                        
                                        <h2>Something went wrong !</h2>
                                        <p>We are sorry but it appears that the page you are looking for could not be found. We are working on it and we will get it fixed as soon as possible.</p>
                                        <p> You can go back to the Main Page by clicking the button.</p>
                                        <a href="<?php echo base_url(); ?>" class="btn btn-orange">Go Back</a>
                                    </div>
                                </div>
                            </div><!-- end columns -->
                        </div><!-- end row -->
                    </div><!-- end container -->
            	</div><!-- end full-page-content -->
            </div><!-- end full-page-wrapper -->
		</section><!-- end error-page-2 -->


       
        <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.colorpanel.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-navigation.js"></script>
    </body>
</html>
