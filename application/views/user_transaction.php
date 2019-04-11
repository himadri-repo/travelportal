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
                                <p>Hi <?php echo $user_details[0]["name"];?>, Welcome to OXY TRA</p>
                                
                            </div> -->
                        	
                            
                            <div id="dashboard-tabs">                            	                            	
                                <div class="tab-content">
                                	<div id="dsh-dashboard" class="tab-pane in active fade" style="border-top: 1px solid #e6e7e8;">
                                		<div class="dashboard-content" style="padding-top:0">
                                           
											
											<div class="dashboard-listing invoices">
                                                <!--<h3 class="dash-listing-heading">Invoices</h3>-->
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <tbody>
														    <tr>
															    <th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">SI</h3></th>
                                                                <th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Date</h3></th>
																<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Purpose</h3></th>
                                                                <th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Booking Details</h3></th> 
																<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Status</h3></th> 
																<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Credit</h3></th>
																<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Debit</h3></th>
                                                            </tr>
															 <?php
														     if(is_array($wallet_transaction))
														     {
																 
																  foreach($wallet_transaction as $key=>$value)
																  {
																  ?>
																<tr>
																	<td class="dash-list-icon invoice-icon"><?php echo $ctr; ?></td>
																	<td class="dash-list-icon invoice-icon"><?php echo date("d/m/Y h:i:s",strtotime($wallet_transaction[$key]["date"])); ?></td>
																	<?php if($wallet_transaction[$key]["booking_id"]==0 ) {?>
																	<td class="dash-list-icon invoice-icon">ADMIN ADDED TO WALLET</td>
																	<?php } ?>
																	
																	<?php if($wallet_transaction[$key]["booking_id"]!=0 && $wallet_transaction[$key]["type"]=="CR") {?>
																		<?php if($wallet_transaction[$key]["narration"]!="") {?>
																		<td class="dash-list-icon invoice-icon"><?php echo $wallet_transaction[$key]["narration"]; ?></td>
																		<?php } else {?>
																		<td class="dash-list-icon invoice-icon">SALE</td>
																		<?php } ?>
																	<?php } ?>
																	
																	<?php if($wallet_transaction[$key]["booking_id"]!=0 && $wallet_transaction[$key]["type"]=="DR") {?>
																	<?php if($wallet_transaction[$key]["narration"]!="") {?>
																		<td class="dash-list-icon invoice-icon"><?php echo $wallet_transaction[$key]["narration"]; ?></td>
																		<?php } else {?>
																		<td class="dash-list-icon invoice-icon">PURCHASE</td>
																		<?php } ?>
																	<?php } ?>
																	<td class="dash-list-text invoice-text">
																	    <?php if($wallet_transaction[$key]["booking_id"]!=0) {?>
																		<ul class="list-unstyled list-inline invoice-info">
																			<!--<li class="invoice-order">Customer Name : <?php echo $wallet_transaction[$key]["name"]; ?></li>--> 
																			<li class="invoice-order">Booking No. : <?php echo $wallet_transaction[$key]["booking_id"]; ?></li> 																			
																			<!--<li class="invoice-order">PNR. : <?php echo $wallet_transaction[$key]["pnr"]; ?></li>-->
																		</ul>
																		<?php } ?>
																	</td>  
																	<td class="dash-list-icon invoice-icon">
																	<?php 
																	 if($wallet_transaction[$key]["status"]=="CANCELLED" && $wallet_transaction[$key]["narration"]=="")
																	 {
																		 echo "CONFIRM";
																	 }
																	 else
																	 {
																		 echo $wallet_transaction[$key]["status"];
																	 }
																	?></td>
																	<td class="dash-list-icon invoice-icon"><?php if($wallet_transaction[$key]["type"]=="CR")echo number_format($wallet_transaction[$key]["amount"],2,".",","); ?></td>
																	<td class="dash-list-icon invoice-icon"><?php if($wallet_transaction[$key]["type"]=="DR")echo number_format(floatval(0-$wallet_transaction[$key]["amount"]),2,".",","); ?></td>
																</tr>
																<?php
																 $total=$total+$wallet_transaction[$key]["amount"];
																$ctr++;
																  }
															 } 
															?>
                                                             <tr>
															    <td colspan="6" class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px">Total</h3></td>
                                                                
																<td class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0"><h3 class="dash-listing-heading" style="padding:15px 10px"><?php echo number_format($total,2,".",",");?></h3></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- end table-responsive -->
                                            </div><!-- end invoices -->
                                                                                                                                                                                
                                        </div><!-- end dashboard-content -->
                                    </div><!-- end dsh-dashboard -->
                                                                                                                                                																		                                                                                                      
                                </div><!-- end tab-content -->
                            </div><!-- end dashboard-tabs -->
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->   
            </div><!-- end contact-us -->
        </section><!-- end innerpage-wrapper -->