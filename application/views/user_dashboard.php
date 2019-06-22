        <style>
			@media (min-width: 768px)
			{
				.custom-modal .modal-dialog 
				{
				width: 900px;
				}
			}
			.card-block .primary-tag1
			{
				background: #faa61a;
				color: white;
				border-radius: 0px 25px 0 0;
			}
			.card-block .primary-tag1
			{
				position: absolute;
				left: -1px;
				bottom: -1px;
				padding: 2px 36px 2px;
			}
			.card-block h4
			{
				font-size:16px;
			}
			.card-name span
			{
				margin-right: 10px;
                color: #faa61a;
			}
			.dashboard-listing table td.dash-list-btn .btn
			{
				opacity:1 !important;
				background: #ccc;
			}
			.card-block ul li.card-links .btn 
			{
					margin-left: 25px;
					font-size: 15px;
					padding: 0px;
					color: #303030 !important;
					text-transform: uppercase;
			}
			table th.dash-list-icon
			{
				text-align:center !important;
			}
			.dashboard-listing .dash-listing-heading
			{
				font-size:15px;
			}
			.table-responsive tr th
			{
				background:#E8E6E4;
				text-align:center;
			}
			.table-responsive tr:nth-child(even) {background: #f5f5f5}
            .table-responsive tr:nth-child(odd) {background: #FFF}
			.dashboard-listing table td.dash-list-icon 
			{
				width: auto; 
				padding-left:0;
				font-size:12px;
			}
			h3 span
			{
				font-size:15px;
				font-weight:normal;
			}
		</style>
        <section class="page-cover dashboard">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">My Account</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>">Home</a></li>
                            <li class="active">My Account</li>
						</ul>
                    </div><!-- end columns -->
				</div><!-- end row -->
				<div class="row">
					<div class="col-sm-12">
						<div class="welcome_title">Hi <?php echo $user_details[0]["name"];?>, Welcome to OXYTRA</div>
					</div><!-- end columns -->
				</div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="dashboard" class="innerpage-section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                        	<div class="dashboard-heading" style="display:none">
							    <?php
								if($this->session->flashdata('msg'))
								{
								?>
								<div class="alert alert-success"><?php echo $this->session->flashdata('msg'); ?></div>
								<?php
								}
								?>
                                <h2>OXY <span>TRA</span></h2>								
								<div class="welcome_title">Hi <?php echo $user_details[0]["name"];?>, Welcome to OXY TRA</div>
                            </div><!-- end dashboard-heading -->
                        	
                            
                            <div id="dashboard-tabs">
                            	<ul class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#dsh-dashboard" data-toggle="tab"><span><i class="fa fa-cogs"></i></span>Dashboard</a></li>
                                    <li><a href="#dsh-profile" data-toggle="tab"><span><i class="fa fa-user"></i></span>Profile</a></li>
								
								</ul>
                            	
                                <div class="tab-content">
                                	<div id="dsh-dashboard" class="tab-pane in active fade">
                                		<div class="dashboard-content">
                                           
                                            <div class="row info-stat">
                                            
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="stat-block">
													    <a style="color:#303030;text-decoration:none" href="<?php echo base_url(); ?>user/transaction">
                                                        <span><i class="fa fa-inr"></i></span>
                                                        <h3><?php echo number_format($wallet[0]["wallet"],2,".",","); ?></h3>
                                                        <p>Wallet</p>
														</a>
                                                    </div>
                                                </div>
                                                <?php if($user_details[0]["is_supplier"]==1){;?>
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="stat-block">
													    <a style="color:#303030;text-decoration:none" href="<?php echo base_url(); ?>user/tickets">
                                                        <span><i class="fa fa-ticket"></i></span>
                                                        <h3><?php echo $ticket_added[0]["no"];?></h3>
                                                        <p>Total Ticket</p>
														</a>
                                                    </div>
                                                </div>
												<?php } ?>
                                                
												<div class="col-sm-6 col-md-4">
                                                    <div class="stat-block">
													     <a style="color:#303030;text-decoration:none" href="<?php echo base_url(); ?>user/my-bookings">
                                                        <span><i class="fa fa-shopping-cart"></i></span>
                                                        <h3><?php echo $my_booking[0]["no"];?></h3>
                                                        <p>My Bookings</p>
														</a>
                                                    </div>
                                                </div>
                                                <?php if($user_details[0]["is_supplier"]==1){?>
													<div class="col-sm-6 col-md-4">
														<div class="stat-block">
															<a style="color:#303030;text-decoration:none" href="<?php echo base_url(); ?>user/booking-orders">
															<span><i class="fa fa-users"></i></span>
															<h3><?php if(isset($ticket_sold[1]["no"])) echo $ticket_sold[0]["no"]+$ticket_sold[1]["no"]; else echo $ticket_sold[0]["no"];?></h3>
															<p>Booking Orders</p>
															</a>
														</div>
													</div>
												<?php } ?>
												<div class="col-sm-6 col-md-4">
												    <a style="color:#303030;text-decoration:none;cursor:pointer" data-toggle="modal" data-target="#make-payment">
                                                    <div class="stat-block">
                                                        <span><i class="fa makepayment"></i></span>
                                                        <h3>&nbsp;</h3>
                                                        <p>Make Payment</p>
                                                    </div>
													</a>
                                                </div><!-- end columns -->
												<?php if($user_details[0]["is_supplier"]==1){?>
												<!--<div class="col-sm-6 col-md-4">
												    <a style="color:#303030;text-decoration:none" href="<?php echo base_url(); ?>user/cancel-request">
                                                    <div class="stat-block">
                                                        <span><i class="fa fa fa-refresh"></i></span>
                                                        <h3><?php echo $cancels[0]["no"];?></h3>
                                                        <p>Cancel Request</p>
                                                    </div>
													</a>
                                                </div> -->
												<div class="col-sm-6 col-md-4">
												    <a style="color:#303030;text-decoration:none;cursor:pointer" href="<?php echo base_url(); ?>user/testimonials">
                                                    <div class="stat-block">
                                                        <span><i class="fa fa-users"></i></span>
                                                        <h3><?php echo $count_testi[0]["no"];?></h3>
                                                        <p>Testimonials</p>
                                                    </div>
													</a>
                                                </div>
												<?php } ?>
												
												<div class="col-sm-12 col-md-12">												    
                                                    <div class="stat-block" style="float:left;width:100%">
													    <div class="col-sm-4">
                                                        <span><i class="fa fa-bank"></i></span>
                                                        <h3><?php echo strtoupper($cname);?> - BANK DETAILS</h3>
                                                        </div>

													<div class="col-sm-4">
														<?php 
															$bank = isset($company_setting[0]["bank_name"])?$company_setting[0]["bank_name"]:$setting[0]["bank_name"];
															$branch = isset($company_setting[0]["bank_branch"])?$company_setting[0]["bank_branch"]:$setting[0]["bank_branch"];
															//$acc_name = $setting[0]["acc_name"]; //$company_setting["acc_name"]?'true':'false';
															$acc_name = isset($company_setting[0]["acc_name"])?$company_setting[0]["acc_name"]:$setting[0]["acc_name"];
															$acc_no = isset($company_setting[0]["acc_no"])?$company_setting[0]["acc_no"]:$setting[0]["acc_no"];
															$ifsc = isset($company_setting[0]["ifsc"])?$company_setting[0]["ifsc"]:$setting[0]["ifsc"];
														?>
                                                        <p><b>BANK NAME : </b> <?php echo $bank;?></p>
														<p><b>BRANCH : </b> <?php echo $branch;?></p>
                                                    </div>												
													<div class="col-sm-4">
                                                      
                                                        <p><b>A/C NAME : </b> <?php echo $acc_name;?></p>
														<p><b>A/C NO. : </b> <?php echo $acc_no;?></p>
														<p><b>IFSC : </b> <?php echo $ifsc;?></p>
                                                    </div>
													</div>	
                                                </div><!-- end columns -->
                                                
                                            </div>
                                            
                                        </div><!-- end dashboard-content -->
                                    </div><!-- end dsh-dashboard -->
                                    
                                    <div id="dsh-profile" class="tab-pane fade">
                                    	<div class="dashboard-content user-profile">
                                            <h2 class="dash-content-title">My Profile</h2>
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><h4>Profile Details</h4></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-sm-5 col-md-3 user-img col-md-offset-3">
														    <center>
														    <?php if(empty($user_details[0]["profile_image"])) {?> 
																<img src="<?php echo base_url(); ?>images/user.png" class="img-responsive" alt="user-img" id="user-img"/>
															<?php } else {?>
															     <img src="<?php echo base_url(); ?>upload/thumb/<?php echo $user_details[0]["profile_image"]; ?>" class="img-responsive" alt="user-img" id="user-img"/>
															<?php } ?>
															</center>
															 <form enctype="multipart/form-data" id="frm_profile_image">
															 <input type="file" id="profile_image" name="profile_image">
															 </form>
															 <center>
															 <label for="profile_image"> 
															 <i class="fa fa-upload"></i>
															 <span>Choose Profile Image...</span>
															 </label>
															 </center>
                                                        </div><!-- end columns -->
                                                        
                                                        <div class="col-sm-7 col-md-6  user-detail">
                                                            <ul class="list-unstyled">
                                                                <li><span>Name:</span> <?php echo $user_details[0]["name"]; ?></li>
                                                                <li><span>Date of Joining:</span> <?php echo date("jS M Y",strtotime($user_details[0]["doj"])); ?></li>
                                                                <li><span>Email:</span> <?php echo $user_details[0]["email"]; ?></li>
                                                                <li><span>Mobile No. :</span> <?php echo $user_details[0]["mobile"]; ?></li>
                                                                
                                                            </ul>
                                                            <button class="btn" data-toggle="modal" data-target="#edit-profile"><i class="fa fa-pencil"></i> Edit Profile</button>
                                                        </div><!-- end columns -->
                                                        
                                                       
                                                    </div><!-- end row -->
                                                    
                                                </div><!-- end panel-body -->
                                            </div><!-- end panel-detault -->
                                        </div><!-- end dashboard-content -->
                                    </div><!-- end dsh-profile -->
                                    
                                    
									
									<div id="dsh-booking1" class="tab-pane fade">
                                    	<div class="dashboard-content booking-trips">
                                            <h2 class="dash-content-title">Sale Orders</h2>
                                            <div class="dashboard-listing booking-listing">
                                                <div class="dash-listing-heading">
                                                   
                                                </div>
                                                
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <?php
                                                             if(is_array($sale_order))
														     {
															  foreach($sale_order as $key=>$value)
															  {
															  ?>
																<tr>
																	<td class="dash-list-icon booking-list-date"><div class="b-date"><h3><?php echo date("jS",strtotime($sale_order[$key]["date"])); ?></h3><p><?php echo date("M",strtotime($sale_order[$key]["date"])); ?></p></div></td>
																	<td class="dash-list-text booking-list-detail">
																		<h3><?php  echo $sale_order[$key]["source_city"]." to ".$sale_order[$key]["destination_city"];?> <span><?php  echo $purchase_order[$key]["status"];?></span></h3>
																		<ul class="list-unstyled booking-info">
																			<li><span>Booking No:</span> <?php  echo $sale_order[$key]["id"];?></li>
																			<li><span>Booking Date:</span><?php  echo date("d/m/Y",strtotime($sale_order[$key]["date"]));?></li>
																			<li><span>Price:</span><?php echo number_format($sale_order[$key]["rate"],2,".",","); ?></li>
																			<li><span>No. of Person:</span><?php  echo $sale_order[$key]["qty"];?></li>
																			<!--<li><span>Customer : </span><?php  echo $sale_order[$key]["name"]." ( ".$sale_order[$key]["user_id"]." ) ";?></li>-->
																		</ul>
																		<button class="btn btn-orange"><i class="fa fa-inr"></i><?php echo number_format($sale_order[$key]["total"],2,".",","); ?></button>
																	</td>
																	<!--<td class="dash-list-btn"><button class="btn btn-orange">Cancel</button><button class="btn">Approve</button></td>-->
																	<td class="dash-list-btn"><a class="btn" href="<?php echo base_url();?>search/thankyou/<?php echo $sale_order[$key]["id"]; ?>">Print</a></td>
																</tr>
															<?php 
															  }
														     }
															?>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div><!-- end table-responsive -->
                                            </div><!-- end booking-listings -->
                                        </div><!-- end dashboard-content -->
                                    </div><!-- end dsh-booking -->
                                    
                                    
                                    
                                    
                                </div><!-- end tab-content -->
                            </div><!-- end dashboard-tabs -->
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->   
            </div><!-- end contact-us -->
        </section><!-- end innerpage-wrapper -->
        
        
       
        
        
        
        
        
        
        
        <div id="edit-profile" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Edit Profile</h3>
                    </div><!-- end modal-header -->
                    
                    <div class="modal-body">
                       
                        	<div class="form-group">
                        		<label>Your Name</label>
                            	<input type="text" class="form-control" placeholder="Name" name="name" id="name" value="<?php echo $user_details[0]["name"]; ?>"/>
                            </div><!-- end form-group -->
                            
                        	
                            <div class="form-group">
                        		<label>Your Mobile No.</label>
                            	<input type="text" class="form-control" placeholder="Mobile No."  name="mobile" id="mobile" value="<?php echo $user_details[0]["mobile"]; ?>"/>
                            </div><!-- end form-group -->							                            
                            
                            <button id="btn_update" class="btn btn-orange">Save Changes</button>
							 <div class="form-group" id="status">
							 </div>
                        
                    </div><!-- end modal-bpdy -->
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-profile -->
        
        
        
        
		
		
		 
		
		 <div id="make-payment" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Make Payment</h3>
                    </div><!-- end modal-header -->
                    
                    <div class="modal-body">
                        <form  id="frm_make_payment">												    
								<div class="form-group">
									<label>Payment Type</label>
									 <select class="form-control" name="payment_type" id="payment_type">											   
											   <option value="NEFT">NEFT</option>
											   <option value="CHEQUE">Cheque Payment</option>
											   <option value="BANK">Bank</option>												 
									</select>
								</div>
								
								<div class="form-group" id="div_refrence_id">
									<label>Refrence ID</label>
									<input  type="number" class="form-control" name="refrence_id" id="refrence_id" placeholder="XXXX" />
								</div>
								
								<div class="form-group" style="display:none" id="div_bank">
									<label>Bank</label>
									<input  type="text" class="form-control" name="bank" id="bank" placeholder="SBI/BOI" />
								</div>
						   
								<div class="form-group" style="display:none" id="div_cheque_no">
									<label>Cheque No.</label>
									<input  type="number" class="form-control" name="cheque_no" id="cheque_no" placeholder="XXXX" />
								</div>	
								
								<div class="form-group" style="display:none" id="div_account_no">
									<label>Account No.</label>
									<input  type="number" class="form-control" name="account_no" id="account_no" placeholder="XXXX" />
								</div>
							
								<div class="form-group" id="div_amount">
									<label>Amount</label>
									<input  type="number" class="form-control" name="amount" id="amount" placeholder="5000" />
								</div>
								
								
                                <div class="form-group" id="div_amount">							
                                  <a class="btn btn-orange" id="btn_make_payment">DONE</a>
									<div class="form-group" id="make_payment_status">
									</div>
								</div>	
                        </form>
                    </div><!-- end modal-bpdy -->
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-card -->
        
        
       
	   