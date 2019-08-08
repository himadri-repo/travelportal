<!DOCTYPE html>
<html lang="en">
<head>
	<title>Air Ticket No - <?php echo $details[0]["ticket_no"] ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
	<link rel="icon" href="<?php echo base_url(); ?>images/favicon.png" type="image/x-icon">

	<style>
		@page {
			margin: 40px 4px;
		}
		html, body {
			height: auto;
		}
		body {
			font-family: courier, serif, 'Merriweather';
			font-size: 16px;
			padding: 15px 5px 20px 5px;
			height: auto;
			margin: 0px;
		}

		.seller-name {
			display: inline-block;
    		margin: 10px 10px;			
		}

		.logo {
			width: 85px;
			height: 30px;
			display: inline-block;			
		}

		.container:before {
			clear: both; 
		}

		.container:after {
			clear: both; 
			content: "";
			display: table; 
  		}

		.container {
			width: 1170px; 
			height: auto;
			overflow: auto;
			display: block;
			padding-right: 15px;
			padding-left: 15px;
			margin-right: auto;
			margin-left: auto;
		}

		.row-section:before {
			clear: both;
		}

		.row-section:after {
			clear: both; 
			content: "";
			display: table; 
		}

		.row-section {
			margin-left: -15px;
			margin-right: -15px;
			height: auto;
			margin-top: 10px;
			margin-bottom: 10px;
		}

		.clear {
			clear: both;
		}

		.clear:after {
			clear: both; 
			content: "";
			display: table; 
  		}

		.md-1, .md-2, .md-3, .md-4, .md-5, .md-6, .md-7, .md-8, 
		.md-9, .md-10, .md-11, .md-12 {
			float: left;
		}

		.md-2 {
			width: 16.67%;
		}
		.md-3 {
			width: 25%;
		}
		.md-4 {
			width: 33.33%;
		}
		.md-5 {
			width: 41.67%;
		}
		.md-6 {
			width: 50%;
		}
		.md-7 {
			width: 58.33%;
		}
		.md-8 {
			width: 66.66%;
		}
		.md-9 {
			width: 75%;
		}
		.md-10 {
			width: 83.33%;
		}
		.md-11 {
			width: 91.66%;
		}
		.md-12 {
			width: 100%;
		}

		p.md-* {
			margin: 0px;
		}

		.align-right {
			text-align:	right;
		}

		.align-center {
			text-align:	center;
		}

		.title {
			font-size: 18px;
			font-weight: 600;
			color: #d87811;
		}

		th, td {
			padding: 10px;
		}

		table {
			width: 100%;
			border: 1px solid #ccc;
		}

		tr {
			border-bottom: 1px solid #ccc;
		}

		thead {
			background-color: #faa61a; color: #ffffff
		}
	</style>

</head>
<body style="font-family: courier, serif, 'Merriweather'; font-size: 12px; padding: 15px 5px 20px 5px; height: auto;margin: 0;">

<div style="width: 720px; height: auto; overflow: auto; display: block; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;">
	<!-- first row-section -->
	<div style="margin-bottom: 35px; margin-top: 20px; margin-left: -15px; margin-right: -15px; height: auto; margin-top: 10px; margin-bottom: 10px;">
		<div style="width: 30%; float: left">
			<?php 
			if($details[0]["type"]=="B2C") {
			?>
				<h2 style="padding: 0; margin: 0;"><?php echo $details[0]["name"];?></h2> 
				<div><span><i class="fa fa-envelope"></i></span> <?php echo $details[0]["email"];?></div>
				<div><span><i class="fa fa-phone"></i></span> <?php echo $details[0]["mobile"];?></div>
				<div><span><i class="fa fa-map-marker"></i></span> <?php echo $details[0]["address"];?></div>
			<?php 
			} else { ?>
				<img src='<?php echo base_url(); ?>upload/<?php echo $setting[0]["logo"]; ?>' alt='<?php echo $setting[0]["acc_name"]; ?>' style="width: 45px; height: 15px;"/>
				<h2 style="padding: 0; margin: 0;"><?php echo $setting[0]["acc_name"];?></h2> 
				<div><span><i class="fa fa-envelope"></i></span> <?php echo $setting[0]["email"];?></div>
				<div><span><i class="fa fa-phone"></i></span> <?php echo $setting[0]["phone_no"];?></div>
				<div><span><i class="fa fa-map-marker"></i></span> <?php echo $setting[0]["address"];?></div>
			<?php }?>
		</div>
		<div style="width: 35%; text-align: center; float: left;">
			<h3>E-TICKET</h3>
		</div>
		<div style="width: 30%; text-align: right; float: left;">
			<h2 style="padding: 0; margin: 0;"><?php echo $details[0]["first_name"].' '.$details[0]["last_name"];?></h3>
			<div><i class="fa fa-calendar"></i></span> <?php echo date("d-m-Y",strtotime($details[0]["date"]));?></div>
			<div><i class="fa fa-envelope"></i></span> <?php echo $details[0]["email"];?></div>
			<div><i class="fa fa-phone"></i></span> <?php echo $details[0]["mobile"];?></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<!-- end of second row-section -->

	<!-- second row-section -->
	<div style="margin-bottom: 35px; margin-top: 20px; margin-left: -15px; margin-right: -15px; height: auto; margin-top: 10px; margin-bottom: 10px;">
		<div style="width: 100%;">
			<span style="font-size: 14px; font-weight: 600; color: #d87811;">
				<i class="fa fa-info-circle"></i> Passenger Details [<?php echo $details[0]["source"]." To ".$details[0]["destination"];?>]
				<span><?php if($details[0]["trip_type"]=="ONE") echo "ONE WAY"; if($details[0]["trip_type"]=="ROUND") echo "ROUND TRIP"; ?></span>
			</span>
		</div>
		<div style="width: 100%; float: left;">
			<table>
				<thead>
					<tr>
						<th style="width: 15%;">Sl.No</th>
						<th style="width: 65%;">Passenger Name</th>
						<th style="width: 20%;">PNR</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$ctr=1;
					foreach($details as $key=>$value)
					{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $details[$key]["prefix"]." ".$details[$key]["first_name"]." ".$details[$key]["last_name"] ?></td>
							<td><?php echo $details[$key]["pnr"];?></td>
						</tr>
					<?php
						$ctr++;
					}												
					?>												
				</tbody>
			</table>
		</div>
		<div style="clear: both;"></div>
	</div>
	<!-- end of second row-section -->
	<?php 
		$dateDiff = intval((strtotime($details[0]["arrival_date_time"])-strtotime($details[0]["departure_date_time"]))/60);
		$dateDiff1 = intval((strtotime($details[0]["arrival_date_time1"])-strtotime($details[0]["departure_date_time1"]))/60);
	?>
	<!-- third row-section -->
	<div style="margin-bottom: 35px; margin-top: 20px; margin-left: -15px; margin-right: -15px; height: auto; margin-top: 10px; margin-bottom: 10px;">
		<div style="width: 100%;"><span style="font-size: 14px; font-weight: 600; color: #d87811;"><i class="fa fa-info-circle"></i> Itinerary</span></div>
		<div style="width: 100%; float: left;">
			<table>
				<thead>
					<tr>
						<th style="width: 16%;">Flight</th>
						<th style="width: 33%;">Departure</th>
						<th style="width: 33%;">Arrival</th>
						<th style="width: 18%;">Status/Flight Duration</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="padding: 10px;">
							<div style="width: 10%;display: inline-block;">
								<img style="width: 18px; height: 18px;" src="<?php echo base_url(); ?>upload/thumb/<?php echo $details[0]["image"]; ?>" alt="<?php echo $details[0]["airline"];?>">
							</div>
							<div style="width: 70%; display: inline-block; float: right;">
								<div style="">
									<?php echo $details[0]["airline"];?><br/>
									<?php echo $details[0]["flight_no"];?>
								</div>
								<div style="">
									<?php echo $details[0]["class"];?>
								</div>
							</div>
						</td>
						<td style="padding: 10px;">
							<div style="display: inline-block;">
								<div style="">
									<div><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo $details[0]["source"];?></div>
									<div style="margin: 0px 23px;"><?php echo $details[0]["terminal"];?></div>
									<div style="margin: 0px 23px;">
										<?php echo date("jS M y",strtotime($details[0]["departure_date_time"]))." ( ".date("h:i a",strtotime($details[0]["departure_date_time"]))." )";?>
									</div>
								</div>
							</div>
						</td>
						<td style="padding: 10px;">
							<div style="display: inline-block;">
								<div style="">
									<div><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo $details[0]["destination"];?></div>
									<div style="margin: 0px 23px;"><?php echo $details[0]["terminal1"];?></div>
									<div style="margin: 0px 23px;">
										<?php echo date("jS M y",strtotime($details[0]["arrival_date_time"]))." ( ".date("h:i a",strtotime($details[0]["arrival_date_time"]))." )";?>
									</div>
								</div>
							</div>
						</td>

						<td><?php echo $details[0]["status"];?><br/><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></td>
					</tr>
					<?php
					if($details[0]["trip_type"]=="ROUND") {
					?>
					<tr>
						<td style="padding: 10px;">
							<div style="width: 10%;display: inline-block;">
								<img style="width: 18px; height: 18px;" src="<?php echo base_url(); ?>upload/thumb/<?php echo $details[0]["image1"]; ?>" alt="<?php echo $details[0]["airline1"];?>">
							</div>
							<div style="width: 80%; display: inline-block; float: right;">
								<div style="">
									<?php echo $details[0]["airline1"];?>
									<?php echo $details[0]["flight_no1"];?>
								</div>
								<div style="">
									<?php echo $details[0]["class1"];?>
								</div>
							</div>
						</td>
						<td style="padding: 10px;">
							<div style="display: inline-block;">
								<div style="">
									<div><i class="fa fa-plane"></i>&nbsp;&nbsp;<?php echo $details[0]["source1"];?></div>
									<div style="margin: 0px 23px;"><?php echo $details[0]["terminal1"];?></div>
									<div style="margin: 0px 23px;">
										<?php echo date("jS M y",strtotime($details[0]["departure_date_time1"]))." ( ".date("h:i a",strtotime($details[0]["departure_date_time1"]))." )";?>
									</div>
								</div>
							</div>
						</td>
						<td style="padding: 10px;">
							<div style="display: inline-block;">
								<div style="">
									<div><i class="fa fa-plane" style="transform:rotate(83deg)"></i>&nbsp;&nbsp;<?php echo $details[0]["destination1"];?></div>
									<div style="margin: 0px 23px;"><?php echo $details[0]["terminal1"];?></div>
									<div style="margin: 0px 23px;">
										<?php echo date("jS M y",strtotime($details[0]["arrival_date_time1"]))." ( ".date("h:i a",strtotime($details[0]["arrival_date_time1"]))." )";?>
									</div>
								</div>
							</div>
						</td>


						<td><?php echo $details[0]["status"];?><br/><?php echo intval($dateDiff1/60)." Hours ".($dateDiff1%60)." Minutes"; ?></td>
					</tr>
					<?php 
					}
					?>
				</tbody>
			</table>
		</div>
		<div style="clear: both;"></div>
	</div>
	<!-- end of third row-section -->

	<!-- forth row-section -->
	<div style="margin-bottom: 35px; margin-top: 20px; margin-left: -15px; margin-right: -15px; height: auto; margin-top: 10px; margin-bottom: 10px;">
		<div style="width: 100%;"><span style="font-size: 14px; font-weight: 600; color: #d87811;"><i class="fa fa-info-circle"></i> Booking Details</span></div>
		<div style="width: 100%; float: left;">
			<table>
				<thead>
					<tr>
						<th style="width: 10%;">Booking No.</th>
						<th style="width: 15%;">Booking Date</th>
						<th style="width: 25%;">Price Summary</th>
						<th style="width: 10%;">Status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center;">BK-<?php echo $details[0]["id"];?></td>
						<td style="text-align: center;"><?php echo date("d/m/Y h:i:s",strtotime($details[0]["date"]));?></td>													
						<td>
							<?php 
							if($options['showprice']) {
							?>
							<table style="border: 0px; padding: 0px;">
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Ticket Fare (each) : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format($details[0]["rate"],2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Qty : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo $details[0]["qty"]; ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Sub Total : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format($details[0]["amount"],2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Service Charge : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format($details[0]["service_charge"],2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>GST : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format(($details[0]["sgst"]+$details[0]["cgst"]),2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Grand Total : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px; padding: 0px; font-weight: 800;"><?php echo number_format($details[0]["total"],2,".",","); ?></td>
								</tr>
							</table>
							<?php 
							}
							?>
						</td>
						
						<td style="text-align: center;"><?php echo $details[0]["status"]."<br>";?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="clear: both;"></div>
	</div>
	<!-- end of forth row-section -->

	<!-- five row-section -->
	<div style="margin-bottom: 35px; margin-top: 20px; margin-left: -15px; margin-right: -15px; height: auto; margin-top: 10px; margin-bottom: 10px;">
		<?php 
		if($options['showprice']) {
		?>
		<div style="width: 100%;"><span style="font-size: 14px; font-weight: 600; color: #d87811;"><i class="fa fa-info-circle"></i> Paymet Details</span></div>
		<?php
		}
		?>
		<div style="width: 100%; float: left;">
			<table style="width: 100%; border: 1px solid #ccc;">
				<!-- <thead>
					<tr>
						<th style="width: 10%;">Booking No.</th>
						<th style="width: 15%;">Booking Date</th>
						<th style="width: 25%;">Price Summary</th>
						<th style="width: 10%;">Status</th>
					</tr>
				</thead> -->
				<tbody>
					<tr>
						<td style="text-align: left;">This is an Electronic ticket. Please carry a positive identification for Check in.</td>
						<td>
							<?php 
							if($options['showprice']) {
							?>
							<table style="border: 0px; padding: 0px;">
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Ticket Fare (each) : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format($details[0]["rate"],2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Qty : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo $details[0]["qty"]; ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Sub Total : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format($details[0]["amount"],2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Service Charge : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format($details[0]["service_charge"],2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>GST : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px;"><?php echo number_format(($details[0]["sgst"]+$details[0]["cgst"]),2,".",","); ?></td>
								</tr>
								<tr>
									<td style="width: 66.66%; padding: 0px;"><label>Grand Total : </label></td>
									<td style="width: 33.33%; text-align: right; padding: 0px; font-weight: 800;"><?php echo number_format($details[0]["total"],2,".",","); ?></td>
								</tr>
							</table>
							<?php
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="clear: both;"></div>
	</div>
	<!-- end of five row-section -->

	<!-- six row section-->
	<div style="margin-bottom: 35px; margin-top: 20px; margin-left: -15px; margin-right: -15px; height: auto; margin-top: 10px; margin-bottom: 10px;">
		<p style="color: #0000ff; font-weight: 400; font-size: 12px;">
				Carriage and other services provided by the carrier are subject to conditions of carriage which hereby incorporated by reference. These
		conditions may be obtained from the issuing carrier. If the passenger's journey involves an ultimate destination or stop in a country other than
		country of departure the Warsaw convention may be applicable and the convention governs and in most cases limits the liability of carriers for
		death or personal injury and in respect of loss of or damage to baggage.
		</p>
		<p style="color: #ff0000; font-weight: 700; font-size: 16px;">Don't Forget to purchase travel insurance for your Visit. Please Contact your travel agent to purchase travel insurance.</p>
	</div>
	<hr/>
</div>

</body>
</html>