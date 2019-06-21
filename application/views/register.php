       
        <!--================ PAGE-COVER =================-->
        <section class="page-cover" id="cover-registration">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Registration</h1>
                        <ul class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li class="active">Registration</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="registration" class="innerpage-section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                        
                        	<div class="flex-content">
                                <div class="custom-form custom-form-fields">
                                    <h3>Registration</h3>
                                   
                                    <form id="form_registration" autocomplete="off">
                                            
                                        <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Name"  id="name" name="name" autocomplete="off" value=""/>
                                             <span><i class="fa fa-user"></i></span>
                                        </div>

                                        <div class="form-group">
                                             <input type="email" class="form-control" placeholder="Email" id="email"  name="email" autocomplete="off" value=""/>
                                             <span><i class="fa fa-envelope"></i></span>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="form-input-section">
                                                <span for="type" class="form-title">Registering as *</span>
                                                <div class="form-field"><input type="radio" id="type" name="type" value="traveller"> Me a Traveller</div>
                                                <div class="form-field"><input type="radio" id="type" name="type" value="agent"> Me a Travel Agent</div>
                                            </div>
                                        </div>

                                        <div class="form-group hide-agent-fields" alias="fortraveller">
                                             <input type="text" class="form-control" placeholder="PAN" id="pan"  name="pan" autocomplete="off" value=""/>
                                             <span><i class="fa fa-address-card-o"></i></span>
                                        </div>

                                        <div class="form-group hide-agent-fields" alias="fortraveller">
                                             <input type="text" class="form-control" placeholder="GST #" id="gst"  name="gst" autocomplete="off" value=""/>
                                             <span><i class="fa fa-id-card-o"></i></span>
                                        </div>
										
										<div class="form-group">
                                             <input type="email" class="form-control" placeholder="Mobile No." id="mobile"  name="mobile" autocomplete="off" value="" maxlength="10"/>
                                             <span><i class="fa fa-mobile"></i></span>
                                        </div>
                                        
                                        <div class="form-group">
                                             <input type="password" class="form-control" placeholder="Password"  id="password" name="password" autocomplete="off" value=""/>
                                             <span><i class="fa fa-lock"></i></span>
                                        </div>
        
                                        <div class="form-group">
                                             <input type="password" class="form-control" placeholder="Confirm Password"  id="confirm_password" name="confirm_password" autocomplete="off" value=""/>
                                             <span><i class="fa fa-repeat"></i></span>
                                        </div>
                                        
                                        <button class="btn btn-orange btn-block" type="button" id="btn_register">Register</button>
										<div class="form-group" id="status">
                                             
                                        </div>
                                    </form>
                                    
                                    <div class="other-links">
                                    	<p class="link-line">Already Have An Account ? <a href="<?php echo base_url(); ?>login">Login Here</a></p>
                                    </div><!-- end other-links -->
                                </div><!-- end custom-form -->
                                
                                <div class="flex-content-img custom-form-img">
                                    <img src="<?php echo base_url(); ?>images/registration.jpg" class="img-responsive" alt="registration-img" />
                                </div><!-- end custom-form-img -->
                            </div><!-- end form-content -->
                            
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->         
            </div><!-- end registration -->
        </section><!-- end innerpage-wrapper -->

        <script language="javascript">
            $(document).ready(function()
            {
                try
                {
                    $('input[type=radio][name=type]').change(function() {
                        if (this.value == 'traveller') {
                            // alert("I am a traveller");
                            $('div[alias=fortraveller]').addClass("hide-agent-fields");
                        }
                        else if (this.value == 'agent') {
                            //alert("I am a travel agent");
                            $('div[alias=fortraveller]').removeClass("hide-agent-fields");
                        }
                    });
                }
                catch(e) {
                    console.log(e);
                }
            });

        </script>