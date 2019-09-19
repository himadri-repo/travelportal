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
			.list-unstyled li span.title {
				display: inline-block;
				width: 150px;
				margin: 0px 6px;
				color: #5a5454bd;
			}
			.list-unstyled li span {
				display: inline-block;
				font-weight: normal !important;
			}
			.error {
				color: #ff0000;
			}
			.success {
				color: #24a00f;
			}
			/* .error:after {
				font-family: "Font Awesome 5 Free";
				font-weight: 400;
				content: "\f1ea";
				
				content:url("http://www.free-icons-download.net/images/tick-mark-icon-35779.png");
				display: inline-block;
				margin-left:10px;
				width: 15px;
				height: 15px;
			} */
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
						<!-- <div class="welcome_title">Hi <?php echo $user_details[0]["name"];?>, Welcome to <?php echo $setting[0]["site_title"]?></div> -->
						<div class="welcome_title">Hi <?php echo $user_details[0]["name"];?>, Welcome to <?php echo $cname?></div>
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
                                                        <div>
															<?php if(isset($wallet_summary[0]['wallet_id'])) { ?>
																<div style="font-size: 18px; font-weight: 700; color: #ff0000;">Unapproved    : ₹ <?php echo $wallet_summary[0]['unapproved_amount']?></div>
																<div style="font-size: 18px; font-weight: 700; color: #139412;">Clear Balance : ₹ <?php echo $wallet_summary[0]['wallet_balance']?></div>
																<!-- <div>Approve/Wait Trans : </h3> <?php echo $wallet_summary[0]['no_of_transactions']?>/<?php echo $wallet_summary[0]['no_of_unapproved_transactions']?></div> -->
															<?php } else { ?>
																&nbsp;
															<?php } ?>
														</div>
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
													    <div class="col-sm-4 col-md-4">
															<span><i class="fa fa-bank"></i></span>
															<h3><?php echo strtoupper($cname);?> - BANK DETAILS</h3>
                                                        </div>

														<div class="col-sm-8 col-md-8">
															<div class="row">
																<?php 
																for ($i=0; $target_accounts && $i < count($target_accounts); $i++) { ?>
																	<div class="col-sm-6 col-md-6" style="border-bottom: 1px solid #ccc;">
																		<?php 
																			$target_account = $target_accounts[$i];
																			// $bank = isset($company_setting[0]["bank_name"])?$company_setting[0]["bank_name"]:$setting[0]["bank_name"];
																			// $branch = isset($company_setting[0]["bank_branch"])?$company_setting[0]["bank_branch"]:$setting[0]["bank_branch"];
																			// //$acc_name = $setting[0]["acc_name"]; //$company_setting["acc_name"]?'true':'false';
																			// $acc_name = isset($company_setting[0]["acc_name"])?$company_setting[0]["acc_name"]:$setting[0]["acc_name"];
																			// $acc_no = isset($company_setting[0]["acc_no"])?$company_setting[0]["acc_no"]:$setting[0]["acc_no"];
																			// $ifsc = isset($company_setting[0]["ifsc"])?$company_setting[0]["ifsc"]:$setting[0]["ifsc"];

																			$bank = isset($target_account["bank_name"])?$target_account["bank_name"]:$setting[0]["bank_name"];
																			$branch = isset($target_account["bank_branch"])?$target_account["bank_branch"]:$setting[0]["bank_branch"];
																			//$acc_name = $setting[0]["acc_name"]; //$company_setting["acc_name"]?'true':'false';
																			$acc_name = isset($target_account["acc_name"])?$target_account["acc_name"]:$setting[0]["acc_name"];
																			$acc_no = isset($target_account["acc_no"])?$target_account["acc_no"]:$setting[0]["acc_no"];
																			$ifsc = isset($target_account["ifsc"])?$target_account["ifsc"]:$setting[0]["ifsc"];
																			$acc_type = isset($target_account["acc_type"])?$target_account["acc_type"]:'CURRENT';
																			?>
																		<p style="text-align: left"><b>A/C NAME</b> <?php echo $acc_name;?></p>
																		<p style="text-align: left"><b>BANK NAME : </b> <?php echo $bank;?></p>
																		<p style="text-align: left"><b>BRANCH : </b> <?php echo $branch;?></p>
																	</div>												
																	<div class="col-sm-6 col-md-6" style="border-bottom: 1px solid #ccc;">
																		<p style="text-align: left"><b>A/C NO. : </b> <?php echo $acc_no;?></p>
																		<p style="text-align: left"><b>A/C TYPE. : </b> <?php echo $acc_type;?></p>
																		<p style="text-align: left"><b>IFSC : </b> <?php echo $ifsc;?></p>
																	</div>
																<?php		
																}
																?>
															</div>
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
                                                                <li><span class="title">Name:</span> <span><?php echo $user_details[0]["name"]; ?></span></li>
                                                                <li><span class="title">Date of Joining:</span> <span><?php echo date("jS M Y",strtotime($user_details[0]["doj"])); ?></span></li>
                                                                <li><span class="title">Email:</span> <span><?php echo $user_details[0]["email"]; ?></span></li>
                                                                <li><span class="title">Mobile No. :</span> <span><?php echo $user_details[0]["mobile"]; ?></span></li>
																<?php if(($user_details[0]['type']=='B2B' || $user_details[0]['type']=='EMP') && $user_details[0]['is_admin']=='1') {?>
																	<li><span class="title">User Type :</span> <span>Employee (Admin)</span></li>
																<?php } ?>
																<?php if($user_details[0]['type']=='B2B' && $user_details[0]['is_admin']!='1') {?>
                                                                	<li><span class="title">Admin Markup. :</span> <span><?php echo $current_user["admin_markup"]; ?></span></li>
                                                                <?php } ?>
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
                                                             if($sale_order && is_array($sale_order))
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
					<form id="frm_edit_user" action="<?php echo base_url(); ?>user/update_profile" method="post">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h3 class="modal-title">Edit Profile</h3>
						</div><!-- end modal-header -->
						
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<label>Your Name</label>
										<input type="text" class="form-control" placeholder="Name" name="name" id="name" value="<?php echo $user_details[0]["name"]; ?>"/>
									</div><!-- end form-group -->
								</div><!-- end col -->
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<label>Your Mobile No.</label>
										<input type="text" class="form-control" placeholder="Mobile No."  name="mobile" id="mobile" value="<?php echo $user_details[0]["mobile"]; ?>"/>
									</div><!-- end form-group -->							                            
								</div><!-- end col -->
							</div><!-- end row -->
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<label>Your Email.ID</label>
										<input type="text" class="form-control" placeholder="Email Id"  name="email" id="email" value="<?php echo $user_details[0]["email"]; ?>"/>
									</div><!-- end form-group -->							                            
								</div><!-- end col -->
								<?php
								if($user_details[0]['type']=='B2B' && $user_details[0]['is_admin']!='1') {?>
								<div class="col-xs-6 col-sm-6 col-md-6">
									<div class="form-group">
										<label>Admin Markup</label>
										<input type="text" class="form-control" placeholder="Markup" name="markup" id="markup" value="<?php echo $current_user["admin_markup"]; ?>"/>
									</div><!-- end form-group -->
								</div><!-- end col -->
								<?php } ?>
							</div>
							<button id="btn_update" type="button" class="btn btn-orange" onclick="javascript:update_profile(<?php echo $user_details[0]['id']?>);">Save Changes</button>
							<div class="form-group error" id="status"></div>
						</div><!-- end modal-bpdy -->
					</form>
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-profile -->
        
		 <div id="make-payment" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header model-header">
                        <button type="button" class="close" data-dismiss="modal" style="background-color: #000000; color: #ffffff; opacity: 1;">&times;</button>
                        <h3 class="modal-title">Make Payment</h3>
                    </div><!-- end modal-header -->
                    
                    <div class="modal-body">
                        <form id="frm_make_payment" action="<?php echo base_url(); ?>user/addtowallet" method="post" onsubmit="return validate_payment();">
							<div class="container-fluid">
								<div class="row">
									<div class="form-group col-sm-6 col-md-6">
										<label>Payment Mode</label>
										<select class="form-control" name="payment_type" id="payment_type">
											<option value="-1">Select Payment Mode</option>
											<option value="1">Cheque</option>
											<option value="2">Draft</option>
											<option value="3">Cash</option>
											<option value="4">RTGS</option>
											<option value="5">Credit Card</option>
											<option value="6">Debit Card</option>
											<option value="7">Net Banking</option>
											<option value="8">NEFT</option>
											<option value="9">Transfer</option>
											<option value="10">EDC Machine</option>
											<!--<option value="3">Online Payment</option>-->
										</select>
									</div>
									<div class="form-group col-sm-6 col-md-6" id="div_amount">
										<label>Amount</label>
										<input type="number" min=500 max=100000 class="form-control" name="amount" id="amount" placeholder="5000" />
									</div>
								</div>
								<div class="row" id="div_refrence">
									<div class="form-group col-sm-6 col-md-6" id="div_refrence_id">
										<label>Refrence ID</label>
										<input  type="text" class="form-control" name="reference_id" id="reference_id" placeholder="Reference #" />
									</div>
									<div class="form-group col-sm-6 col-md-6" id="div_refrence_date">
										<label>Refrence Date</label>
										<input class="form-control datepicker" placeholder="dd/mm/yyyy" name="reference_date" id="reference_date"/>
									</div>
								</div>
								<div class="row" id="div_financials">
									<div style="display:none" id="div_bank">
										<div class="form-group col-sm-6 col-md-6" id="div_bank_name">
											<label>Your Bank</label>
											<input  type="text" class="form-control" name="bank" id="bank" placeholder="SBI/BOI" />
										</div>
										<div class="form-group col-sm-6 col-md-6" id="div_branch_name">
											<label>Branch</label>
											<input  type="text" class="form-control" name="branch" id="branch" placeholder="" />
										</div>
									</div>
								</div>
								<div class="row" style="display:none" id="div_cheque">
									<div class="form-group col-sm-6 col-md-6" id="div_cheque_no">
										<label>Cheque No.</label>
										<input  type="number" class="form-control" name="cheque_no" id="cheque_no" placeholder="XXXX" />
									</div>	

									<div class="form-group col-sm-6 col-md-6" id="div_cheque_date">
										<label>Cheque Date</label>
										<input class="form-control datepicker" placeholder="dd/mm/yyyy" name="cheque_date" id="cheque_date" readonly/>
									</div>
								</div>
								<div class="row" style="display:none" id="div_draft">
									<div class="form-group col-sm-6 col-md-6" id="div_draft_no">
										<label>Draft No.</label>
										<input  type="number" class="form-control" name="draft_no" id="draft_no" placeholder="XXXX" />
									</div>	

									<div class="form-group col-sm-6 col-md-6" id="div_draft_date">
										<label>Draft Date</label>
										<input class="form-control datepicker" placeholder="dd/mm/yyyy" name="draft_date" id="draft_date" readonly/>
									</div>
								</div>
								<div class="row" id="div_account">
									<div class="form-group col-sm-6 col-md-6" style="display:none" id="div_account_no">
										<label>Your Account No.</label>
										<input  type="number" class="form-control" name="account_no" id="account_no" placeholder="XXXX" />
									</div>

									<div class="form-group col-sm-6 col-md-6" id="div_target_accountid">
										<label>Our Bank Account <i style="color: #ff0000;">*</i></label>
										<select class="form-control" name="target_accountid" id="target_accountid">
											<option value="-1">Select our account</option>
											<?php for ($i=0; $target_accounts && $i < count($target_accounts); $i++) { 
												$targetAccount = $target_accounts[$i]; ?>
												<option value="<?php echo $targetAccount['id']?>"><?php echo $targetAccount['bank_name'].'-'.$targetAccount['acc_no'].'-'.$targetAccount['ifsc']?></option>
											<?php 
											}?>
										</select>
									</div>
								</div>

								<?php if($payment_gateway && count($payment_gateway)>0) { ?>
								<div class="row" id="div_pw" style="display:none;">
									<div class="form-group col-sm-12 col-md-12" id="div_payment_gw">
										<label for="payment_gw">Payment Gateway</label>
										<?php 
										for ($i=0; $payment_gateway && $i < count($payment_gateway); $i++) { 
											$pw = $payment_gateway[$i];
											$terms = $pw['terms_condition'];
											$conv_rate = json_encode($pw['conv_rate'], JSON_HEX_QUOT);
										?>
											<div class="pw_item">
												<!-- <input type="radio" id="rd_<?php echo $i?>" name="rd_<?php echo $i?>" value="<?php echo $pw['id']?>" style="display: inline-block;" data='<?php echo $conv_rate?>' onclick="pw_selected(this);"/> -->
												<input type="radio" name="pw" value="<?php echo $pw['id']?>" style="display: inline-block;" terms='<?php echo $terms ?>' data='<?php echo $conv_rate?>' onclick="pw_selected(this);"/>
												<span style="display: inline-block;"><?php echo $pw['pw_name']?></span>
												<img src="<?php echo ($pw['icon']?$pw['icon']:'../images/makepayment.png') ?>" class="" alt="<?php echo $pw['pw_name'] ?> payment gateway" style="display: inline-block; width: 25px; height:20px;"/>
											</div>
										<?php 
										}
										?>
									</div>
								</div>

								<div class="row" id="div_convenience" style="display:none;">
									<div class="form-group col-sm-12 col-md-12">
										<div><span>Convenience Charge  </span> ₹ <input id="convenience" value="0.00" readonly style="border: none; width: 5%; background-color: #ffffff; text-align: right;"/> (<span id="conv_rate_text"></span>)</div>
										<div><span>Amount to be charged</span> ₹ <input id="final_amount" value="0.00" readonly style="border: none; width: 5%; background-color: #ffffff; text-align: right;"/></div>
										<span style="color: #ff0000; font-size: 12px;margin: 10px 0px;">Notes : </span><div id="terms" style="display:none; text-align: left;margin: 10px 0px; font-family: monospace;font-size: 10px;font-weight: 800;text-decoration: underline;color: #ff0000;"></div>
									</div>
								</div>

								<?php } ?>

								<div class="row" id="div_narration_section">
									<div class="form-group col-sm-12 col-md-12" id="div_narration">
										<label for="narration">Narration</label>
										<textarea id="narration" class="form-control" name="narration" rows="3"></textarea>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-12 col-md-12" id="div_action">
										<button type="submit" class="btn btn-orange" id="btn_make_payment">Done</button>
										<button type="button" class="btn btn-orange" id="btn_make_payment" style="float: right;" data-dismiss="modal">Close</button>
										<!-- <button class="btn btn-orange" id="btn_make_payment">DONE</a> -->
										<div class="form-group" id="make_payment_status"></div>
									</div>	
								</div>
							</div>
                        </form>
                    </div><!-- end modal-bpdy -->
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-card -->
        
        <script language="javascript">
			let conv_rates = [];
			let conv_rate = 0.00;
			let conv_charge = 0.00;
			let final_amount = 0.00;
			$('#amount').change(function(ev) {
				let payment_mode = parseInt($('#payment_type').val());
				let amount = parseFloat($('#amount').val());

				conv_rates.forEach(cnvrt => {
					if(cnvrt.payment_mode == payment_mode) {
						//conv_rate = parseInt(parseFloat(cnvrt.conv_rate)*100);
						conv_rate = (parseFloat(cnvrt.conv_rate)*100);
						return false;
					}
				});

				if(payment_mode===5 || payment_mode===6 || payment_mode===7) {
					//console.log(`amount changed - ${amount} -- ${payment_mode}`);
					showConvenienceCharge(amount);
				}
			});

			function showConvenienceCharge(amount) {
				//console.log(`amount changed 1 - ${amount} - ${conv_rate}`);

				conv_charge = (parseFloat(amount) * conv_rate)/100;
				final_amount = Math.round(parseFloat(amount) + conv_charge, 0);

				$('#conv_rate_text').text(`${conv_rate}% of net amount`);
				$('#convenience').val(conv_charge);
				$('#final_amount').val(final_amount);
			}

			function pw_selected(element) {
				if(!element) return;
				let payment_mode = parseInt($('#payment_type').val());

				//console.log($(element).attr('data'));
				conv_rates = JSON.parse($(element).attr('data'));
				terms = $(element).attr('terms');

				$('#terms').text(terms);
				$('#terms').show();
				//console.log(conv_rates);

				conv_rates.forEach(cnvrt => {
					if(cnvrt.payment_mode == payment_mode) {
						//conv_rate = parseInt(parseFloat(cnvrt.conv_rate)*100);
						conv_rate = (parseFloat(cnvrt.conv_rate)*100);
						return false;
					}
				});

				// conv_rate = parseInt(parseFloat($(element).attr('data'))*100);

				let amount = $('#amount').val();
				showConvenienceCharge(amount);
			}

			$("#payment_type").change(function(ev) {
				let value = parseInt(this.value);
				// alert( "Handler for .change() called. - " + value);
				$('#amount').val(0.00);
				$('#div_account').show();
				$('#div_narration_section').show();
				$('#div_bank').hide();
				$('#div_account_no').hide();
				$('#div_cheque').hide();
				$('#div_draft').hide();
				$('#div_refrence_id').hide();
				$('#div_refrence_date').hide();
				$('#div_pw').hide();
				$('#div_convenience').hide();
				$('#terms').hide();

				if(value===1) { //Cheque
					$('#div_bank').show();
					$('#div_account_no').show();
					$('#div_cheque').show();
				}
				else if(value===2) { //Draft
					$('#div_bank').show();
					$('#div_draft').show();
				}
				else if(value===3 || value===9 || value===10) { //Cash, Transfer, EDC Machine
					$('#div_bank').show();
					$('#div_refrence_id').show();
					$('#div_refrence_date').hide();
				}
				else if(value===4 || value===8) { //NEFT, RTGS
					$('#div_bank').show();
					$('#div_refrence_id').show();
					$('#div_refrence_date').show();
				}
				else if(value===5 || value===6 || value===7) { //Credit Card, Debit Card, Net Banking
					$('#div_pw').show();
					$('#div_convenience').show();
					$('#div_account').hide();
					$('#div_narration_section').hide();
				}
			});

			function validate_payment() {
				//alert('Submitting form');
				
				let payment_type = parseInt($('#payment_type').val());
				let reference_id = $('#reference_id').val();
				let reference_date = $('#reference_date').val();
				let bank = $('#bank').val();
				let branch = $('#branch').val();
				let cheque_no = $('#cheque_no').val();
				let draft_no = $('#draft_no').val();
				let account_no = parseInt($('#account_no').val());
				let narration = $('textarea#narration').val();
				let amount = parseInt("0"+$('#amount').val());
				let target_accountid = $('#target_accountid').val();
				let flag = true;

				if(target_accountid<=0 && (payment_type!==5 && payment_type!==6 && payment_type!==7)) {
					alert('Please select our account number where you are depositing the amount. It is mandatory.');
					flag = false;
					return flag;
				}
				
				if(payment_type<=0) {
					alert('Please select your payment mode.');
					flag = false;
					return flag;
				}

				if(amount<=0) {
					alert('Depositing amount should not be zero or negative.');
					flag = false;
					return flag;
				}

				if(payment_type===1 || payment_type===2) {
					let err = '';
					if(bank===null || bank==='') {
						err += 'Please provide bank details.\n';
						flag = false;
					}

					if(payment_type===1 && (cheque_no===null || cheque_no==='')) {
						err += 'Please provide cheque number.\n';
						flag = false;
					}

					if(payment_type===2 && (draft_no===null || draft_no==='')) {
						err += 'Please provide draft number.\n';
						flag = false;
					}

					if(account_no<=0) {
						err += 'Please provide account details.\n';
						flag = false;
					}

					if(!flag) {
						alert(err);

						return flag;
					}
				}

				if(payment_type===3 || payment_type===9 || payment_type===10 || payment_type===4 || payment_type===8) {
					let err = '';
					if(bank===null || bank==='') {
						err += 'Please provide bank details.\n';
						flag = false;
					}

					if(branch===null || branch==='') {
						err += 'Please provide bank branch details.\n';
						flag = false;
					}

					if(reference_id===null || reference_id==='') {
						err += 'Please provide transaction reference number.\n';
						flag = false;
					}

					if((payment_mode===4 || payment_mode===8) && (reference_date===null || reference_date==='')) {
						err += 'Please provide transaction reference date.\n';
						flag = false;
					}

					if(!flag) {
						alert(err);

						return flag;
					}
				}

				return flag;
			}

			let uuid = parseInt('<?php echo $user_details[0]['id']?>');
			// $("#mobile").on('change', function() {
			// 	console.log('I am pretty sure the mobile box changed');
			// 	if(!$("#mobile").hasClass('error')) {
			// 		$("#mobile").addClass('error');
			// 	}
			// });
			// $("#email").on('change', function() {
			// 	console.log('I am pretty sure the email box changed');
			// 	if(!$("#email").hasClass('error')) {
			// 		$("#email").addClass('error');
			// 	}
			// });

			function update_profile(uid) {
				let name = $('#name').val();
				let mobile = $('#mobile').val();
				let email = $('#email').val();
				let admin_markup = $('#markup').val();
				let flag = true;
				let data = {
					'id': uid,
					'name': name,
					'mobile': mobile,
					'email': email,
					'admin_markup': admin_markup
				};
				//alert(JSON.stringify(data));
				$.ajax({
					url: '<?php echo base_url(); ?>user/update_profile',
					dataType: 'json',
					type: 'POST',
					contentType: 'application/json',
					data: JSON.stringify(data),
					processData: false,
					success: function( data, textStatus, jQxhr ){
						if(data && data.status) {
							if($('#status').hasClass('error')) {
								$('#status').removeClass('error');
								$('#status').addClass('success');
							}
							$('#status').html( data.message );
							setTimeout(() => {
								window.location.href = '<?php echo base_url(); ?>user/logout';
							}, 5000);
						}
						else {
							$('#status').html( data.message );
						}
					},
					error: function( jqXhr, textStatus, errorThrown ){
						console.log( errorThrown );
					}
				});				

				return flag;
			}
		</script>
       
	   