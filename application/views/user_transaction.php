        <style>
			@media (min-width: 768px)
			{
			.custom-modal .modal-dialog 
			{
			width: 800px;
			}
			}
			.invoices td.invoice-text .invoice-info
			{
				font-size:13px !important;
			}
			.dashboard-listing table td.dash-list-icon
			{
				padding-left: 35px;
                font-size: 13px;
				width:auto !important;

			}
			.invoices td.invoice-text .invoice-info li
			{
				color:#000 !important;
			}
			th
			{
				background:#E8E6E4;
				text-align:center;
			}
			.table-responsive tr:nth-child(even) {background: #f5f5f5}
            .table-responsive tr:nth-child(odd) {background: #FFF}
		</style>
		<?php
		 $ctr=1;
		 $total=0;
		?>
        <section class="page-cover dashboard">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Transaction History</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>user">My Account</a></li>
                            <li class="active">Transaction</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="dashboard" class="innerpage-section-padding" style="padding-top:0">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                        	<!--<div class="dashboard-heading">
                                <h2>OXY <span>TRA</span></h2>								
                                <p>Hi <?php //echo $user_details[0]["name"];?>, Welcome to OXY TRA</p>
                                
                            </div> -->
							<?php 
								$dt_from = isset($_POST['dt_from'])?$_POST['dt_from']:'';
								$dt_to = isset($_POST['dt_to'])?$_POST['dt_to']:'';
								$user_name = $target_user['name'];
								$uuserid = intval($target_user['id']);

								$uuserid = $target_usertype.$uuserid;
							?>
                            
                            <div id="dashboard-tabs">                            	                            	
                                <div class="tab-content">
                                	<div id="dsh-dashboard" class="tab-pane in active fade" style="border-top: 1px solid #e6e7e8;">
										<?php if(intval($user_details[0]['is_admin']) === 1) { ?>
											<div class="row">
												<form action="<?php echo base_url()?>user/transaction" method="POST">
													<div class="col-xs-12 col-sm-12 col-md-12" style="padding: 30px 30px 0px 30px;">
														<div class="row">
															<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
																<div class="form-group dropdown" style="margin: 3px 10px;">
																	<label for="user">Select account:</label>
																	<select class="form-control" id="user" name="user">
																		<option value="<?= intval($user_details[0]['id']); ?>" <?= (($target_usertype === '' && $uuserid == intval($user_details[0]['id']))? 'selected' : '') ?>><?= $company['name']; ?></option>
																		<?php 
																		for ($i=0; $agents && $i < count($agents); $i++) { ?>
																			<option value="<?= $agents[$i]['id']; ?>" <?= (($target_usertype === '' && $uuserid == intval($agents[$i]['id']))? 'selected' : '') ?>>(B2B) - <?= $agents[$i]['name']; ?></option>
																		<?php } ?>
																		<?php 
																		for ($i=0; $retail && $i < count($retail); $i++) { ?>
																			<option value="<?= $retail[$i]['id']; ?>" <?= (($target_usertype === '' && $uuserid == intval($retail[$i]['id']))? 'selected' : '') ?>>(B2C) - <?= $retail[$i]['name']; ?></option>
																		<?php } ?>
																		<?php 
																		for ($i=0; $wholesalers && $i < count($wholesalers); $i++) { ?>
																			<option value="WHL<?= $wholesalers[$i]['id']; ?>" <?= (($uuserid == $target_usertype.intval($wholesalers[$i]['id']))? 'selected' : '') ?>>(WHL) - <?= $wholesalers[$i]['display_name']; ?></option>
																		<?php } ?>
																		<?php 
																		for ($i=0; $suppliers && $i < count($suppliers); $i++) { ?>
																			<option value="SPL<?= $suppliers[$i]['id']; ?>" <?= (($uuserid == $target_usertype.intval($suppliers[$i]['id']))? 'selected' : '') ?>>(SPL) - <?= $suppliers[$i]['display_name']; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
															<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
																<div class="form-group">												    
																	<label for="dt_from">From Date:</label>
																	<input type="text" class="form-control dpd3" name="dt_from" id="dt_from" placeholder="From Date" value="<?= $dt_from;?>" autocomplete="off"/>
																</div>
															</div>
															<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
																<div class="form-group">
																	<label for="dt_to">From Date:</label>
																	<input type="text" class="form-control dpd3" name="dt_to" id="dt_to" placeholder="To Date" value="<?= $dt_to;?>" autocomplete="off"/>
																</div>
															</div>
															<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
																<div class="form-group">
																	<!-- <label for="btnquery">&nbsp;</label> -->
																	<br/>
																	<button id="btnquery" name="btnquery" class="btn btn-primary" type="submit"> Go
																</div>
															</div>
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-12">
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<div class="form-group" style="margin: 0px 15px 20px 15px;" id="trans_type" name="trans_type">
																<label for="account_type_wlt">Wallet Transactions</label>
																<input type="radio" class="" name="account_type" id="account_type_wlt" value="wallet" <?= (($account_type == "wallet")? 'checked' : '') ?> style="margin: 0px 10px 0px 0px;"/>
																<label for="account_type_acc">Account Transactions</label>
																<input type="radio" class="" name="account_type" id="account_type_acc" value="account" <?= (($account_type == "account")? 'checked' : '') ?>/>
															</div>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														</div>
													</div>												
												</form>
											</div>
										<?php } ?>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
												<h1 style="background-color: #faa61a; color: #ffffff; margin: 0px 15px; padding: 5px 10px; text-align: right;"><?= $target_usertype===''? '' : '('.$target_usertype.') '?><?=$user_name?> (<?= $account_type?> transaction)</h1>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 dashboard-content" style="padding-top:0;">
												<div class="dashboard-listing invoices">
													<!--<h3 class="dash-listing-heading">Invoices</h3>-->
													<div class="table-responsive">
														<table class="table table-hover">
															<tbody>
																<?php if($account_type==='wallet') { ?>
																<tr>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">SI</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Date</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Purpose</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Booking Details</h3></th> 
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Status</h3></th> 
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Credit</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Debit</h3></th>
																</tr>
																<?php } 
																else { ?>
																<tr>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">SI</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Date</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Narration</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Voucher #</h3></th> 
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Credit</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Debit</h3></th>
																	<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Balance</h3></th>
																</tr>
																<?php } ?>
																<?php
																if($wallet_transaction && is_array($wallet_transaction) && count($wallet_transaction)>0)
																{
																	$ob = floatval($wallet_transaction[0]['OB']);
																	$total = $ob;
																	foreach($wallet_transaction as $key=>$value)
																	{
																		if($ctr === 1 && $ob!==0.00) { ?>
																			<tr>
																				<td colspan="5" class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #000000;">Opening Balance</h3></td>
																				<?php if($ob<0) { ?>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #000000;"></h3></td>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #ff0000;">₹ <?php echo number_format(abs($ob),2,".",",");?></h3></td>
																				<?php } else { ?>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #1f800f;">₹ <?php echo number_format(abs($ob),2,".",",");?></h3></td>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #000000;"></h3></td>
																				<?php } ?>
																			</tr>
																		<?php }
																	?>
																	<tr>
																		<td class="dash-list-icon invoice-icon"><?php echo $ctr; ?></td>
																		<td class="dash-list-icon invoice-icon"><?php echo date("d/m/Y h:i:s",strtotime($wallet_transaction[$key]["date"])); ?></td>
																		<?php if(intval($wallet_transaction[$key]["booking_no"])==0 ) {?>
																			<td class="dash-list-icon invoice-icon">ADMIN ADDED TO WALLET</td>
																		<?php } else {?>
																			<td class="dash-list-icon invoice-icon">Booking No. : <a href="<?= base_url()?>search/thankyou/<?= $wallet_transaction[$key]["booking_id"];?>" alt="Find booking details" target="_blank"><?php echo $wallet_transaction[$key]["booking_id"]; ?></a></td>
																		<?php } ?>
																		
																		<?php if(intval($wallet_transaction[$key]["booking_no"])!=0 && ($wallet_transaction[$key]["type"]=="PURCHASE")) {?>
																			<?php if($wallet_transaction[$key]["narration"]!="") {?>
																				<td class="dash-list-icon invoice-icon"><?php echo $wallet_transaction[$key]["narration"]; ?></td>
																			<?php } else {?>
																				<td class="dash-list-icon invoice-icon">PURCHASE</td>
																			<?php } ?>
																		<?php } 
																		else if($wallet_transaction[$key]["type"]=="DEBIT NOTE") { ?>
																			<td class="dash-list-icon invoice-icon">DEBIT NOTE</td>
																		<?php } ?>
																		
																		<?php if(intval($wallet_transaction[$key]["booking_no"])==0 && ($wallet_transaction[$key]["type"]=="PAYMENT" || $wallet_transaction[$key]["type"]=="CREDIT NOTE")) {?>
																		<?php if($wallet_transaction[$key]["narration"]!="") {?>
																				<td class="dash-list-icon invoice-icon"><?php echo $wallet_transaction[$key]["narration"]; ?></td>
																			<?php } else {?>
																				<td class="dash-list-icon invoice-icon">PAYMENT</td>
																			<?php } ?>
																		<?php } ?>

																		<td class="dash-list-icon invoice-icon">
																		<?php 
																		if($wallet_transaction[$key]["wallet_trans_status"]=="1")
																		{
																			echo "CONFIRM";
																		}
																		else if($wallet_transaction[$key]["wallet_trans_status"]=="2")
																		{
																			echo "REJECTED";
																		}
																		else
																		{
																			echo "PENDING";
																		}
																		?>
																		</td>
																		<td class="dash-list-icon invoice-icon">
																			<?php if(($wallet_transaction[$key]["type"]=="PAYMENT" || $wallet_transaction[$key]["type"]=="CREDIT NOTE")) {
																				echo number_format($wallet_transaction[$key]["amount"],2,".",","); 
																			}
																			else {
																				echo number_format(0,2,".",","); 
																			}
																			?>
																		</td>
																		<td class="dash-list-icon invoice-icon">
																			<?php if($wallet_transaction[$key]["type"]=="PURCHASE" || $wallet_transaction[$key]["type"]=="DEBIT NOTE") {
																				echo number_format(floatval($wallet_transaction[$key]["amount"]),2,".",","); 
																			}
																			else {
																				echo number_format(floatval(0),2,".",","); 
																			}
																			?>
																		</td>
																	</tr>
																	<?php
																	if($wallet_transaction[$key]["wallet_trans_status"]=="1") {
																		if($wallet_transaction[$key]["type"]=="PAYMENT" || $wallet_transaction[$key]["type"]=="CREDIT NOTE") {
																			$total+=floatval($wallet_transaction[$key]["amount"]);
																		}
																		else if($wallet_transaction[$key]["type"]=="PURCHASE" || $wallet_transaction[$key]["type"]=="DEBIT NOTE") {
																			$total-=floatval($wallet_transaction[$key]["amount"]);
																		}
																	}
																	$ctr++;
																	}
																} 
																else if($account_transaction && is_array($account_transaction) && count($account_transaction)>0) { 
																	$ob = floatval($account_transaction[0]['OB']);
																	foreach($account_transaction as $key=>$value)
																	{
																		if($ctr === 1 && $ob!==0.00) { 
																		?>
																			<tr>
																				<td colspan="4" class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #000000;">Opening Balance</h3></td>
																				<?php if($ob<0) { ?>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #000000;"></h3></td>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #ff0000;">₹ <?php echo number_format(abs($ob),2,".",",");?></h3></td>
																				<?php } else { ?>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #1f800f;">₹ <?php echo number_format(abs($ob),2,".",",");?></h3></td>
																					<td class="dash-list-icon invoice-icon"><h3 class="dash-listing-heading" style="padding:15px 10px; color: #000000;"></h3></td>
																				<?php } ?>
																			</tr>
																		<?php }

																		$ob += (floatval($account_transaction[$key]["credit"]) - floatval($account_transaction[$key]["debit"]));
																	?>
																	<tr>
																		<td class="dash-list-icon invoice-icon"><?php echo $ctr; ?></td>
																		<td class="dash-list-icon invoice-icon"><?php echo date("d/m/Y h:i:s",strtotime($account_transaction[$key]["date"])); ?></td>
																		<td class="dash-list-icon invoice-icon"><?= $account_transaction[$key]["narration"]; ?></td>
																		<td class="dash-list-icon invoice-icon"><?= $account_transaction[$key]["voucher_no"]; ?></td>
																		<td class="dash-list-icon invoice-icon"><?= number_format(abs($account_transaction[$key]["credit"]),2,".",","); ?></td>
																		<td class="dash-list-icon invoice-icon"><?= number_format(abs($account_transaction[$key]["debit"]),2,".",","); ?></td>
																		<td class="dash-list-icon invoice-icon"><?= number_format(abs($ob),2,".",","); ?></td>
																	</tr>
																	<?php 
																	$ctr++;
																	} ?>
																<?php 
																	$total = $ob;
																}
																?>
																<tr>
																	<td colspan="3" class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Total</h3></td>
																	
																	<td colspan="4" class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0; text-align: right">
																		<?php if($total>=0) { ?>
																			<h3 class="dash-listing-heading" style="padding:15px 10px; color: #1f800f;">₹ <?php echo number_format($total,2,".",",");?> (CR)</h3>
																		<?php } else { ?>
																			<h3 class="dash-listing-heading" style="padding:15px 10px; color: #ff0000;">₹ <?php echo number_format(abs($total),2,".",",");?> (DR)</h3>
																		<?php } ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</div><!-- end table-responsive -->
												</div><!-- end invoices -->
											</div><!-- end dashboard-content -->
										</div>
                                    </div><!-- end dsh-dashboard -->
                                </div><!-- end tab-content -->
                            </div><!-- end dashboard-tabs -->
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->   
            </div><!-- end contact-us -->
        </section><!-- end innerpage-wrapper -->

		<script>
			$(document).ready(function(){
				$("#myInput").on("keyup", function() {
					var value = $(this).val().toLowerCase();
					$(".dropdown-menu li").filter(function() {
						$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
					});
				});

				$("#user").change(function() {
					console.log($(this).val());
					
					try {
						// var userid = $(this).val();
						// if(userid.indexOf('SPL')>-1 || userid.indexOf('WHL')>-1) {
						// 	//$('#trans_type').prop('disabled', true);
						// 	$('#trans_type').show();
						// }
						// else {
						// 	$('#trans_type').hide();
						// }
					}
					catch(e) {
						console.log(e);
					}
				});
			});
		</script>
