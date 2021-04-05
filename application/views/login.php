        
        
        <!--============= PAGE-COVER =============-->
        <section class="page-cover" id="cover-login" style="display:none">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Login</h1>
                        <ul class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li class="active">Login</li>
                        </ul>
                    </div>
                </div>
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="login" class="innerpage-section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                        
                        	<div class="flex-content">
                                <div class="custom-form custom-form-fields">
                                    <h3>Login</h3>
                                    
                                    <form id="form_login" autocomplete="off">
                                            
                                        <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Mobile No."  name="mobile" id="mobile" autocomplete="off" maxlength="10"/>
                                             <span><i class="fa fa-user"></i></span>
                                        </div>
                                        
                                        <div class="form-group">
                                             <input type="password" class="form-control" placeholder="Password"  id="password" name="password" autocomplete="off"/>
                                             <span><i class="fa fa-lock"></i></span>
                                        </div>
                                        
                                        <div class="checkbox" style="display: none;">
                                            <a style="cursor:pointer" class="simple-link" data-toggle="modal" data-target="#login_otp">Login With OTP</a>
                                        </div>
                                        
                                        <button  id="btn_login" class="btn btn-orange btn-block">Login</button>
										<div class="form-group" id="status">
                                             
                                        </div>
                                    </form>
                                    
                                    <div class="other-links">
                                    	<p class="link-line">New Here ? <a href="<?php echo base_url(); ?>register">Signup</a></p>
                                        <a style="cursor:pointer" class="simple-link" data-toggle="modal" data-target="#forgot">Forgot Password ?</a>
                                    </div><!-- end other-links -->
                                </div><!-- end custom-form -->
                                
                                <div class="flex-content-img custom-form-img">
                                    <img src="<?php echo base_url(); ?>images/login.jpg" class="img-responsive" alt="registration-img" />
                                </div><!-- end custom-form-img -->
                            </div><!-- end form-content -->
                            
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->         
            </div><!-- end login -->
        </section><!-- end innerpage-wrapper -->
		
		
		<div id="forgot" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Enter Your Registered Mobile No.</h3>
                    </div><!-- end modal-header -->
                    
                    <div class="modal-body">
                       
                        	<div class="form-group">                        		
                            	<input type="text" class="form-control" placeholder="Mobile No." name="mobile" id="forgot_mobile" maxlength="10" />
                            </div><!-- end form-group -->
                            
                        	
                            						                            
                            
                            <button id="btn_forgot_password" class="btn btn-orange">Done</button>
							 <div class="form-group" id="forgot_status">
							 </div>
                        
                    </div><!-- end modal-bpdy -->
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-profile -->
		
		
		<div id="login_otp" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Enter Your Registered Mobile No.</h3>
                    </div><!-- end modal-header -->
                    
                    <div class="modal-body">
                       
                        	<div class="form-group">                        		
                            	<input type="text" class="form-control" placeholder="Mobile No." name="mobile" id="otp_mobile"  maxlength="10" />
                            </div><!-- end form-group -->
                            
                        	
                            						                            
                            
                            <button id="btn_login_otp" class="btn btn-orange">Done</button>
							 <div class="form-group" id="login_otp_status">
							 </div>
                        
                    </div><!-- end modal-bpdy -->
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-profile -->
        
        
        
        
        
       