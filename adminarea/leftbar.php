<?php 

?>
<body class="no-skin">
		<div id="navbar" class="navbar navbar-default">			
			<div class="navbar-container" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="navbar-header pull-left">
					<a href="<?php echo $baseurl; ?>/index.php" target="_blank" class="navbar-brand">
						<small>
							<i class="fa fa-eye"></i>
							View Site
						</small>
					</a>
				</div>



				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">												
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">						
								<span class="user-info" id="user_display_name">
									<small>Welcome,</small>
									<?php echo $_SESSION['admin_name'];?>
								</span>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>
							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
							   <?php if(in_array(13,$auth)){?>
								<li>
									<a href="setting.php">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>
							   <?php } ?>

								<li class="divider"></li>
								<li>
									<a id="btn_logout" style="cursor:pointer">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>



		<div class="main-container" id="main-container">
			<div id="sidebar" class="sidebar responsive">				
				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>						
					</div>
					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>
						<span class="btn btn-info"></span>
						<span class="btn btn-warning"></span>
						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->
				<ul class="nav nav-list">
					<li class="" id="dashboard_menu">
						<a href="dashboard.php">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php if(in_array(15,$auth)){?>
					<li class="" id="post_menu">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-quote-right"></i>
							<span class="menu-text">
								Content
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
						<ul class="submenu">														
							<li class="" id="all_posts_menu">
								<a href="slider.php">
									<i class="menu-icon fa fa-caret-right"></i>
									 Slider
								</a>
								<b class="arrow"></b>
							</li>

							<li class="" id="content"> 
								<a href="post.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Content
								</a>
								<b class="arrow"></b>
							</li>
							
								<li class="" id="faq"> 
								<a href="faq.php">
								<i class="menu-icon fa fa-caret-right"></i>
									FAQ
								</a>
								<b class="arrow"></b>
							</li>
							
								<li class="" id="term"> 
								<a href="term.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Term & Conditions
								</a>
								<b class="arrow"></b>
							</li>

							<li class="" id="testimonial">
								<a href="testimonial.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Testimonial
								</a>
								<b class="arrow"></b>
							</li>														
						</ul>
					</li>
					<?php } ?>
					<?php if(in_array(1,$auth)){?>
					<li class="" id="menu_airline">
						<a href="airline.php">
							<i class="menu-icon fa fa-plane"></i>
							<span class="menu-text"> Airlines </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(2,$auth)){?>
					<li class="" id="menu_city">
						<a href="city.php">
							<i class="menu-icon fa fa-map-marker"></i>
							<span class="menu-text"> City </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(3,$auth)){?>
					<li class="" id="menu_celebrity">
						<a href="celebrity.php">
							<i class="menu-icon fa fa-users"></i>
							<span class="menu-text"> Agents </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(4,$auth)){?>
					<li class="" id="menu_payment_request">
						<a href="payment_request.php">
							<i class="menu-icon fa fa-inr"></i>
							<span class="menu-text"> Payment Request </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(5,$auth)){?>
					<li class="" id="menu_tickets">
						<a href="tickets.php">
							<i class="menu-icon fa fa-ticket"></i>
							<span class="menu-text"> Tickets </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(6,$auth)){?>
					<li class="" id="menu_update_tickets">
						<a href="update_tickets.php">
							<i class="menu-icon fa fa-ticket"></i>
							<span class="menu-text"> Update PNR </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(7,$auth)){?>
					<li class="" id="menu_book_tickets">
						<a href="book_tickets.php">
							<i class="menu-icon fa fa-inr"></i>
							<span class="menu-text">Book Ticket </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					
					<?php if(in_array(8,$auth)){?>
					<li class="" id="menu_bookings">
						<a href="bookings.php">
							<i class="menu-icon fa fa-plane"></i>
							<span class="menu-text"> Booking Request</span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(16,$auth)){?>
					<li class="" id="menu_quotation">
						<a href="quotation.php">
							<i class="menu-icon fa fa-quote-right"></i>
							<span class="menu-text"> Quotation Request</span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(14,$auth)){?>
					<li class="" id="menu_booking">
						<a href="booking.php">
							<i class="menu-icon fa fa-plane"></i>
							<span class="menu-text"> Bookings </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					
					<?php if(in_array(9,$auth)){?>
					<li class="" id="menu_cbookings">
						<a href="cbookings.php">
							<i class="menu-icon fa fa-times"></i>
							<span class="menu-text">Cancel Request</span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(10,$auth)){?>
					<li class="" id="menu_ctransaction">
						<a href="ctransaction.php">
							<i class="menu-icon fa fa-inr"></i>
							<span class="menu-text"> Agents  Account</span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(11,$auth)){?>
					<li class="" id="menu_mtransaction">
						<a href="mtransaction.php">
							<i class="menu-icon fa fa-inr"></i>
							<span class="menu-text"> Manage  Agents Account</span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<?php if(in_array(12,$auth)){?>
					<li class="" id="menu_users">
						<a href="users.php">
							<i class="menu-icon fa fa-users"></i>
							<span class="menu-text">Users</span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<!--<li class="" id="menu_stransaction">
						<a href="stransaction.php">
							<i class="menu-icon fa fa-inr"></i>
							<span class="menu-text"> Supplier Transaction </span>
						</a>
						<b class="arrow"></b>
					</li>
                     -->

                   <?php if(in_array(13,$auth)){?>
                     <li class="" id="setting">
						<a href="setting.php">
							<i class="menu-icon fa fa-cog"></i>
							<span class="menu-text"> Settings </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					
					<li class="" id="change_password">
						<a href="change_password.php">
							<i class="menu-icon fa fa-lock"></i>
							<span class="menu-text"> Change Password </span>
						</a>
						<b class="arrow"></b>
					</li> 
					
					<li class="">
						<a href="backup.php">
							<i class="menu-icon fa fa-database"></i>
							<span class="menu-text"> Backup </span>
						</a>
						<b class="arrow"></b>
					</li> 
					
				</ul><!-- /.nav-list -->
				
				
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>


				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>