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
			.table th.dash-list-icon
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
				text-align:center;
			}
			h3 span
			{
				font-size:15px;
				font-weight:normal;
			}
		</style>
		<?php
		 $ctr=1;
		 $total=0;
		?>
        <section class="page-cover dashboard">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">My Tickets</h1>
                        <ul class="breadcrumb">
                            <li><a href="<?php echo base_url(); ?>user">My Account</a></li>
                            <li class="active">My Tickets</li>
                        </ul>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end page-cover -->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="dashboard" class="innerpage-section-padding" style="padding-top:0">
                <div class="container-fluid">
                    <div class="row">
					        
                            <div class="col-xs-12 col-sm-12 col-md-12">                        	                            
                                            <div id="dashboard-tabs">                            	                            	
												<div class="tab-content">
													<div id="dsh-dashboard" class="tab-pane in active fade" style="border-top: 1px solid #e6e7e8;">
													    
														<div class="dashboard-content" style="padding-top:0">
														    <div class="row" style="margin-top:40px">
														    <div class="col-sm-3 col-md-3">
															<a href="<?php echo base_url()?>user/add-oneway-ticket" class="btn btn-orange">Add One Way Ticket</a>
															</div>
															
															<div class="col-sm-3 col-md-3">
															<a href="<?php echo base_url()?>user/add-return-ticket" class="btn btn-orange">Add Return Ticket</a>
															</div>
															
															<div class="col-sm-3 col-md-3">
																													    
																			<a href="<?php echo base_url()?>user/tickets"  class="btn btn-orange">Reset</a>
																		
															</div>
															</div>
															
															
															<div class="row" style="margin-top:40px">
																<form action="<?php echo base_url()?>user/tickets" method="POST">
																
																	<div class="col-xs-6 col-sm-2">
																		<div class="form-group">												    
																			<input type="text" class="form-control dpd3" name="dt_from" id="dt_from" placeholder="Going Date From" value="<?php echo $dt_from;?>"/>
																		 </div>
																	</div>
																	
																	
																	<div class="col-xs-6 col-sm-2">
																		<div class="form-group">												    
																			<input type="text" class="form-control dpd3" name="dt_to" id="dt_to" placeholder="Going Date To" value="<?php echo $dt_to;?>"/>
																		 </div>
																	</div>
																	
																	
																	<div class="col-xs-6 col-sm-2">
																		<div class="form-group right-icon">												    
																			 <select class="form-control" name="source" id="source">
																			  <option value="">Source</option>																		 
																			  <?php
																			  foreach($city as $key=>$value)
																			  {
																			  ?>
																			  <option <?php if($city[$key]["id"]==$source) echo"selected"; ?>  value="<?php echo $city[$key]["id"];?>"><?php echo $city[$key]["city"];?></option>
																			  <?php
																			  }
																			   ?>										  
																			</select>
																			
																		</div>
																	</div>
																	
																	
																	<div class="col-xs-6 col-sm-2">
																		<div class="form-group right-icon">												    
																			 <select class="form-control" name="destination" id="destination">													 
																			  <option value="">Destination</option>	
																			  <?php
																			  foreach($city as $key=>$value)
																			  {
																			  ?>
																			  <option <?php if($city[$key]["id"]==$destination) echo"selected"; ?>  value="<?php echo $city[$key]["id"];?>"><?php echo $city[$key]["city"];?></option>
																			  <?php
																			  }
																			   ?>										  
																			</select>
																			
																		</div>
																	</div>
																	
																	<div class="col-xs-6 col-sm-2">
																		<div class="form-group">												    
																			<input type="text" class="form-control" name="pnr" id="pnr" placeholder="PNR" value="<?php echo $pnr;?>"/>
																		 </div>
																	</div>
																	
																	<div class="col-xs-6 col-sm-2">
																		<div class="form-group">												    
																			<button type="submit"  class="btn btn-orange">Search</button>
																		 </div>
																	</div>
																	
																	
																	
																</form>
															</div>
															
															<div class="row">
															<div class="dashboard-listing invoices">
																<!--<h3 class="dash-listing-heading">Invoices</h3>-->
																<div class="table-responsive">
																	<ul class="pager">
																		<?php																			  
																			if($total_tickets>0)
																			{
																				$ctr=1;	 
																				for ($i=0; $i<ceil($total_tickets/$page_size); $i++) 
																				{ 
																					if(($i>=($page_index-3)) && ($i<=($page_index+3))) {
																		?>
																					<li class="<?php echo ($i==($page_index-1)? 'active' : ''); ?>"><a href='<?php echo base_url(); ?>user/tickets?pageindex=<?php echo ($i+1);?>&pagesize=<?php echo ($page_size)?>' title=''><?php echo ($i+1); ?></a></li>
																		<?php 
																					}
																				}
																			}?>
																	</ul>
																	<table class="table table-hover">
																		<tbody>
																			<tr>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">SI</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Ticket No.</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">PNR</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Type</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Going Date</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Returning Date</h3></th>
																				
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Journey</h3></th> 
																																				
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Total Fare</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Seats</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Seats Booked</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Status</h3></th>
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Avl</h3></th>	
																				<th class="dash-list-icon invoice-icon" style="width:auto;padding-left:0;padding-top:0;text-align:center"><h3 class="dash-listing-heading" style="padding:15px 10px;text-align:center">Action</h3></th>																				
																			</tr>
																			<?php																			  
																			 if(is_array($ticket))
																			 {
																			  $ctr=1;	 
																			  foreach($ticket as $key=>$value)
																			  {
																			  ?>                                              																							
																				<tr>
																					<td class="dash-list-icon invoice-icon"><center><?php echo $ctr; ?></center></td>
																					<td class="dash-list-icon invoice-icon"><center><?php echo $ticket[$key]["id"]; ?></center></td>
																					<td class="dash-list-icon invoice-icon"><?php echo $ticket[$key]["pnr"]; ?></td>
																					<td class="dash-list-icon invoice-icon" style="text-transform:uppercase;"><?php if($ticket[$key]["trip_type"]=="ONE") {echo $ticket[$key]["trip_type"]." WAY";}else{ echo $ticket[$key]["trip_type"]." TRIP";} ?><br/><?php  echo $ticket[$key]["sale_type"];?></td>
																					<td class="dash-list-icon invoice-icon"><i class='fa fa-plane' style='font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i><?php echo date("d/m/y h:i a",strtotime($ticket[$key]["departure_date_time"]))?><br/><?php if($ticket[$key]["arrival_date_time"]!="0000-00-00 00:00:00") { echo "<i class='fa fa-plane' style='transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i>".date("d/m/y h:i a",strtotime($ticket[$key]["arrival_date_time"]));} ?></td>																					
																					<?php if($ticket[$key]["departure_date_time1"]!="0000-00-00 00:00:00"){?>
																					<td class="dash-list-icon invoice-icon"><i class='fa fa-plane' style='font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i><?php echo date("d/m/y h:i a",strtotime($ticket[$key]["departure_date_time1"]))?><br/><?php if($ticket[$key]["arrival_date_time1"]!="0000-00-00 00:00:00") { echo "<i class='fa fa-plane' style='transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i>".date("d/m/y h:i a",strtotime($ticket[$key]["arrival_date_time1"]));} ?></td>
													                                <?php } else echo "<td></td>";?>
																					<td class="dash-list-icon invoice-icon"><?php echo $ticket[$key]["source"]."<br>".$ticket[$key]["destination"]; ?></td>
																					
																					<td class="dash-list-icon invoice-icon"><?php echo ($ticket[$key]["price"]+$ticket[$key]["markup"]); ?></td>
																					<td class="dash-list-icon invoice-icon"><?php if($ticket[$key]["sale_type"]!="quote") {echo $ticket[$key]["no_of_person"];} ?></td>
																					<td class="dash-list-icon invoice-icon"><a href="<?php echo base_url() ?>user/booking-details/<?php echo $ticket[$key]["id"];?>"><?php echo ($ticket[$key]["max_no_of_person"]-$ticket[$key]["no_of_person"]); ?></a></td>
																					<td class="dash-list-icon invoice-icon"><?php if($ticket[$key]["approved"]=="0"){echo "Pending";} else if($ticket[$key]["approved"]=="2"){echo "Rejected";}else{echo "Approved";}?></td>
																					<td class="dash-list-icon invoice-icon"><?php echo $ticket[$key]["available"];?></td>
																					<td class="dash-list-icon invoice-icon"><a class="btn btn-orange"  href="<?php echo base_url() ?>user/edit-ticket/<?php echo $ticket[$key]["id"]; ?>" style="cursor:pointer;font-size:11px">Edit</a> 
																					<?php if($ticket[$key]["no_of_person"]>0 && $ticket[$key]["approved"]=="1" && (strtotime($ticket[$key]["departure_date_time"])>strtotime(date("Y-m-d h:i:s"))) && ($ticket[$key]["sale_type"]!="quote")){ ?>
																					<button class="btn btn-orange btn_book_bckend" data-toggle="modal" data-target="#edit-card"  color="<?php echo $ticket[$key]["id"]; ?>" style="cursor:pointer;font-size:11px">Book</button></td>
																				    <?php } ?>
																				 </tr>	
                                                               
																				<?php 
																				$ctr++;
																			  }
																			 }
																			?>                                               
																			</tbody>
																		</table>
																	</div><!-- end table-responsive -->
															</div><!-- end invoices -->
															</div>																																		
														</div><!-- end dashboard-content -->
													</div><!-- end dsh-dashboard -->
																																																																																				  
												</div><!-- end tab-content -->
										    </div><!-- end dashboard-tabs -->
                            </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->   
            </div><!-- end contact-us -->
        </section><!-- end innerpage-wrapper -->
		
		
		<div id="edit-card" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Book Ticket</h3>
                    </div><!-- end modal-header -->
                    
                    <div class="modal-body">
                        <form method="POST" action="" id="frm_book_ticket">
                        	<div class="form-group">
                        		<label>No. of Person</label>
                            	<input type="number" class="form-control" min="1" name="back_end_qty" required />
                            </div><!-- end form-group -->
                            
                        	
                            <button type="submit" class="btn btn-orange">OK</button>
                        </form>
                    </div><!-- end modal-bpdy -->
                </div><!-- end modal-content -->
            </div><!-- end modal-dialog -->
        </div><!-- end edit-card -->