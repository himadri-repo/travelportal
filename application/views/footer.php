<!--============ NEWSLETTER-2 =============-->
        <section id="newsletter-2" class="newsletter"> 
            <div class="container">
                <div class="row">
                	<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <h2>Subscribe Our Newsletter</h2>	
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
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
        </section><!-- end newsletter-2 -->
        
        
        <!--======================= FOOTER =======================-->
        <section id="footer" class="ftr-heading-w ftr-heading-mgn-2">
        
            <div id="footer-top" class="banner-padding ftr-top-grey">
                <div class="container">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 footer-widget ftr-about ftr-our-company">
                            <h3 class="footer-heading"><?php echo $footer[0]["title"]; ?></h3>
                            <p><?php echo $footer[0]["description"]; ?>.</p>
                            <ul class="social-links list-inline list-unstyled">
                            	<li><a href="<?php echo $setting[0]["facebook_link"]; ?>" target="_blank"><span><i class="fa fa-facebook"></i></span></a></li>
                            	<li><a href="<?php echo $setting[0]["twitter_link"]; ?>" target="_blank"><span><i class="fa fa-twitter"></i></span></a></li>
                                <li><a href="<?php echo $setting[0]["google_link"]; ?>" target="_blank"><span><i class="fa fa-google-plus"></i></span></a></li>
                               <!-- <li><a href="#"><span><i class="fa fa-pinterest-p"></i></span></a></li>
                                <li><a href="#"><span><i class="fa fa-instagram"></i></span></a></li>
                                <li><a href="#"><span><i class="fa fa-linkedin"></i></span></a></li>
                                <li><a href="#"><span><i class="fa fa-youtube-play"></i></span></a></li>-->
                            </ul>
                        </div><!-- end columns -->
                        
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 footer-widget ftr-in-touch">
                            <h3 class="footer-heading">Get in Touch</h3>
                            
                                <div class="row">
                                    <div class="col-sm-6 col-md-5 col-lg-5 slide-right-vis">
                                    
                                        <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Name"  id="txt_name" name="txt_name" />
                                        </div>
                                      
                                        <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Email"  id="txt_email" name="txt_email" />
                                        </div>
                                          <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Phone No."  id="txt_phone" name="txt_phone"/>
                                        </div>
                                        
                                        
                                        
                                    </div><!-- end columns -->
        
                                    <div class="col-sm-6 col-md-7 col-lg-7 slide-left-vis">
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="Your Message" id="txt_msg" name="txt_msg"></textarea>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-12" id="mail-msg-box"></div>
                                    </div><!-- end columns -->
                                    
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-orange" id="btn_send_mail">Send</button>
                                    </div><!-- end butn -->
                                </div><!-- end row -->
                            
                        </div><!-- end columns -->
                        
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end footer-top -->

            <div id="footer-bottom" class="ftr-bot-black">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="copyright">
                            <p>© 2018 <a href="#">OxyTra</a>. All rights reserved.</p>
                        </div><!-- end columns -->
                        
                        <!--<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="terms">
                            <ul class="list-unstyled list-inline">
                            	<li><a href="#">Terms & Condition</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                            </ul>
                        </div> -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end footer-bottom -->            
        </section><!-- end footer -->
        
        
       
        <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.colorpanel.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.flexslider.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-navigation.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-flex.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-date-picker.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
        <script src="<?php echo base_url(); ?>js/jquery.colorpanel.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.magnific-popup.min.js"></script>        
        <script src="<?php echo base_url(); ?>js/owl.carousel.min.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-owl.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-video.js"></script>
		<script src="<?php echo base_url(); ?>script/user_script.js"></script>
        <script src="<?php echo base_url(); ?>script/contact.js"></script>

        <script language="javascript">
            window.onscroll = function() {myFunction()};

            var header = document.getElementById("mynavbar1");
            var sticky = 0;

            if(header!==null) {
                sticky = header.offsetTop;
                function myFunction() {
                    if (window.pageYOffset > sticky) {
                        header.classList.add("sticky");
                    } else {
                        header.classList.remove("sticky");
                    }
                }
            }
        </script>
    </body>
</html>