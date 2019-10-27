<!Doctype html>
<html lang="en">
<head>
        <title><?php echo $setting[0]['site_title']?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0">
        <link rel="icon" href="images/favicon.png" type="image/x-icon">                
        <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900,900i%7CMerriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">               
        
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">                
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
        <link rel="stylesheet" id="cpswitch" href="<?php echo base_url(); ?>css/orange.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/responsive.css">               
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/flexslider.css" type="text/css" />
        <!--<link rel="stylesheet" href="<?php echo base_url(); ?>css/datepicker.css">-->		
        <link href="<?php echo base_url(); ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/magnific-popup.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.colorpanel.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/owl.carousel.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/owl.theme.css"> 
		 <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css">
         <script>
            var baseurl="<?php echo base_url(); ?>";
            var admin=parseInt("0<?php echo $this->session->userdata('current_user')['is_admin'] ?>");
            var permission = parseInt("0<?php echo $this->session->userdata('current_user')['permission'] ?>");
        </script>
        <style>
            .dropdown-item {
                font-size: 15px;
                padding: 15px 10px;
                display: block;
                background-color: #ffffff;
                color: #000000;
                text-decoration: none;
            }

            .dropdown-item:hover {
                background-color: #0000ff;
                color: #ffffff;
                text-decoration: none;
            }
        </style>
    </head>
    
    <body id="flight-homepage">
    
       
        <div class="loader"></div>            
    	      
        <!--<div class="overlay">
            <a href="javascript:void(0)" id="close-button" class="closebtn">&times;</a>
            <div class="overlay-content">
                <div class="form-center">
                    <form>
                    	<div class="form-group">
                        	<div class="input-group">
                        		<input type="text" class="form-control" placeholder="Search..." required />
                            	<span class="input-group-btn"><button type="submit" class="btn"><span><i class="fa fa-search"></i></span></button></span>
                            </div><
                        </div>
                    </form>
                </div>
            </div>
        </div>-->
        
        <?php 
            if($cname!=null && !empty($cname)) {
                $current_user = $this->session->userdata('current_user');
                $phone = $company_setting[0]["phone_no"];
                $logo = $company_setting[0]["logo"];
                $admin = $current_user['is_admin'];
                $uuid = $current_user['uid'];
            }
            else {
                $cname = $setting[0]["site_title"];
                $phone = $setting[0]["phone_no"];
                $logo = $setting[0]["logo"];
                $admin = 0;
                $uuid = 0;
            }
        ?>
                
        <!--============= TOP-BAR ===========-->
        <div id="top-bar" class="tb-text-white" style="display:none">
            <div class="container">
                <div class="row">          
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div id="info">
                            <ul class="list-unstyled list-inline">
                                <!-- <li><span><i class="fa fa-map-marker"></i></span><?php echo $setting[0]["address"]; ?></li>
                                <li><span><i class="fa fa-phone"></i></span><?php echo $setting[0]["phone_no"]; ?></li> -->
                                <li><span><i class="fa fa-map-marker"></i></span><?php echo $cname; ?></li>
                                <li><span><i class="fa fa-phone"></i></span><?php echo $phone; ?></li>
                            </ul>
                        </div><!-- end info -->
                    </div><!-- end columns -->
                    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div id="links">
                            <ul class="list-unstyled list-inline">
							    <?php if(!$this->session->userdata('user_id')){ ?>
									<li><a href="<?php echo base_url(); ?>login"><span><i class="fa fa-lock"></i></span>Login</a></li>
									<li><a href="<?php echo base_url(); ?>register"><span><i class="fa fa-plus"></i></span>Sign Up</a></li>
								<?php } else {?>
								    <li><a href="<?php echo base_url(); ?>user"><span><i class="fa fa-user"></i></span>My Account</a></li>
									<li><a href="<?php echo base_url(); ?>user/logout"><span><i class="fa fa-power-off"></i></span>Log Out</a></li>

								<?php } ?>
                                
                            </ul>
                        </div><!-- end links -->
                    </div><!-- end columns -->				
                </div><!-- end row -->
            </div><!-- end container -->
        </div>
        <!-- end top-bar -->
        <!--<nav class="navbar navbar-default main-navbar navbar-custom navbar-white" id="mynavbar">-->
        <nav class="navbar navbar-default navbar-custom new-navbar" id="mynavbar1">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" id="menu-button">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>                        
                    </button>
                    <!-- <div class="header-search hidden-lg">
                    	<a href="javascript:void(0)" class="search-button"><span><i class="fa fa-search"></i></span></a>
                    </div> -->
                    <a href="<?php echo base_url(); ?>" class="navbar-brand"><img src="<?php echo base_url(); ?>upload/<?php echo $logo;?>" class="img-responsive" style="width: 125px; height: 50px;"></a>
                </div><!-- end navbar-header -->
                
                <div class="collapse navbar-collapse" id="myNavbar1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo base_url(); ?>"><span><i class="fa fa-home"></i></span>Home</a></li>
						<li><a href="<?php echo base_url(); ?>search"><span><i class="fa fa-search"></i></span>Search Flight</a></li>
                        <?php if(!$this->session->userdata('user_id')){ ?>
                            <li><a href="<?php echo base_url(); ?>login"><span><i class="fa fa-lock"></i></span>Login</a></li>
                            <li><a href="<?php echo base_url(); ?>register"><span><i class="fa fa-plus"></i></span>Sign Up</a></li>
                        <?php } else {?>
                            <li><a href="<?php echo base_url(); ?>user"><span><i class="fa fa-user"></i></span><?php echo 'Hi! '.explode(" ",$this->session->userdata('name'))[0];?>&nbsp;<?php echo $admin?'(Admin)':'' ?></a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">My Wallet (₹ <?php echo $mywallet['balance'] ?>)</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>wallet/add">Add Money</a>
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>user/transaction">Ledger</a>
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>wallet/withdraw">Withdraw Money</a>
                                </div>
                            </li>
                            <?php if($this->session->userdata('user_id') && $admin) { ?>
                                <li><a href="<?php echo base_url(); ?>admin?uuid=<?php echo $uuid; ?>"><span><i class="fa user-crown"></i></span>Administration</a></li>
                            <?php } ?>
                            <li><a href="<?php echo base_url(); ?>user/logout"><span><i class="fa fa-power-off"></i></span>Log Out</a></li>
                        <?php } ?>

					    <!--<li><a href="<?php echo base_url(); ?>terms-and-conditions">Term & Conditions</a></li>
					    <li><a href="<?php echo base_url(); ?>faq">FAQ</a></li>
                        <li><a href="<?php echo base_url(); ?>contact"><span><i class="fa fa-phone">Contact Us</a></li>-->
                        <!--<li><a href="javascript:void(0)" class="search-button"><span><i class="fa fa-search"></i></span></a></li>-->
                    </ul>
                </div><!-- end navbar collapse -->
            </div><!-- end container -->
        </nav><!-- end navbar -->

		<div class="sidenav-content">
            <div id="mySidenav" class="sidenav" >
                <h2 id="web-name"><span><i class="fa fa-plane"></i></span><?php echo $setting[0]['site_title']?></h2>

                <div id="main-menu">
                	<div class="closebtn">
                        <button class="btn btn-default" id="closebtn">&times;</button>
                    </div><!-- end close-btn -->
                    
                    <div class="list-group panel">
                    
                        <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>"><span><i class="fa fa-home link-icon"></i></span>Home</a>
                        <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>search"><i class="fa fa-plane link-icon"></i><span></span>Search Flight</a>
						<!--<a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>terms-and-conditions"><span><i class="fa fa-book link-icon"></i></span>Term & Conditions</a>-->
						<!--<a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>faq"><span><i class="fa fa-question-circle link-icon"></i></span>FAQ</a>-->
                        <?php if(!$this->session->userdata('user_id')){ ?>
                            <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>login"><span><i class="fa fa-lock link-icon"></i></span>Login</a></li>
                            <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>register"><span><i class="fa fa-plus link-icon"></i></span>Sign Up</a></li>
                        <?php } else {?>
                            <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>user"><span><i class="fa fa-user link-icon"></i></span><?php echo 'Hi! '.explode(" ",$this->session->userdata('name'))[0];?></a></li>

                            <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>wallet/add"><span><i class="fa fa-money link-icon"></i></span> Add Money</a>
                            <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>user/transaction"><span><i class="fa fa-shopping-bag link-icon"></i></span> Ledger</a>
                            <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>wallet/withdraw"><span><i class="fa fa-suitcase link-icon"></i></span> Withdraw Money</a>

                            <?php if($this->session->userdata('user_id') && $admin) { ?>
                                <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>admin?uuid=<?php echo $uuid; ?>"><span><i class="fa user-crown link-icon"></i></span>Administration</a></li>
                            <?php } ?>
                            <a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>user/logout"><span><i class="fa fa-power-off link-icon"></i></span>Log Out</a></li>
                        <?php } ?>
						<a class="list-group-item" data-parent="#main-menu" href="<?php echo base_url(); ?>contact"><span><i class="fa fa-phone link-icon"></i></span>Contact Us</a>
                    </div><!-- end list-group -->
                </div><!-- end main-menu -->
            </div><!-- end mySidenav -->
        </div><!-- end sidenav-content -->