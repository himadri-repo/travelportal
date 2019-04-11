<?php
include("header.php");
require "instamojo.php";
if( isset($_GET["check_in"]) && isset($_GET["check_out"]) && isset($_GET["guests"]) && isset($_GET["rooms"]) && isset($_GET["hotel_id"]) && isset($_GET["room_type_id"]))
{
  $sql="SELECT h.*,c.city as city_name FROM hotel_tbl h 
  INNER JOIN city_tbl c ON  c.id=h.city  WHERE h.id='".$_GET["hotel_id"]."'";
  $result=mysql_query($sql);
  if(mysql_num_rows($result)==0)
  {
	  ?>
	   <script>window.location.href="index.php";</script>
	  <?php
  }
  else
  {
	  $row=mysql_fetch_array($result);
	  $ids=$row["room_types"];
	  $hotel_name=$row["hotel_name"];
	  $address=$row["address"];
	  $city_name=$row["city_name"];
	  $sgst=$row["sgst"];
	  $cgst=$row["cgst"];
	  $check_in_time=$row["check_in_time"];
	  $check_out_time=$row["check_out_time"];
  }
}
else
{
  ?>
   <script>window.location.href="search-hotel";</script>
  <?php 
}
function payment($mobile_no,$amount)
{
	       $api = new Instamojo\Instamojo('ed2c04b3c5aa8673afcdde87332fe36a','78f8321ec0fe782c6af25ffa9f2e2ac0');	
			
			try {
					$response = $api->paymentRequestCreate(array(
					"purpose" =>'PAYMENT',
					"amount" => $amount,
					"phone" => $mobile_no,
					"send_sms"=>true,
					"send_email" => false,		
					"email" =>'',
					"redirect_url" => "https://yourwebsite.co.in/simply/invoice",
					));

					}
					catch (Exception $e) 
					{
					print('Error: ' . $e->getMessage());
					}
					$payemnt_request_id=$response['id'];

					try 
					{
					$response1 = $api->paymentRequestStatus($payemnt_request_id);					
					  $link= $response1['longurl'];
					}
					catch (Exception $e) 
					{
					print('Error: ' . $e->getMessage());
					}
					if(!empty($link))
					{
						?>
						 <script>window.location.href='<?php echo $link;?>';</script>
						<?php
					}
}
if(isset($_POST["submit"]))
{
	$first_name=isset($_POST['first_name'])? $_POST['first_name']:"";
	$last_name=isset($_POST['last_name'])? $_POST['last_name']:"";
	$mobile_no=isset($_POST['mobile_no'])? $_POST['mobile_no']:"";
	$email=isset($_POST['email'])? $_POST['email']:"";
	$country=isset($_POST['country'])? $_POST['country']:"";
	$city=isset($_POST['city'])? $_POST['city']:"";
	
	$hotel_name=isset($_POST['hotel_name'])? $_POST['hotel_name']:"";
	$hotel_address=isset($_POST['hotel_address'])? $_POST['hotel_address']:"";
	$check_in_date=isset($_POST['check_in_date'])? $_POST['check_in_date']:"";
	$check_out_date=isset($_POST['check_out_date'])? $_POST['check_out_date']:"";
	$check_in_time=isset($_POST['check_in_time'])? $_POST['check_in_time']:"";
	$check_out_time=isset($_POST['check_out_time'])? $_POST['check_out_time']:"";
	$room_name=isset($_POST['room_name'])? $_POST['room_name']:"";
	$no_of_rooms=isset($_POST['no_of_rooms'])? $_POST['no_of_rooms']:"";
	$guests=isset($_POST['guests'])? $_POST['guests']:"";
	$no_of_nights=isset($_POST['no_of_nights'])? $_POST['no_of_nights']:"";
	$room_rate=isset($_POST['room_rate'])? $_POST['room_rate']:"";
	$sgst=isset($_POST['sgst'])? $_POST['sgst']:"";
	$cgst=isset($_POST['cgst'])? $_POST['cgst']:"";
	$grand_total=isset($_POST['grand_total'])? $_POST['grand_total']:"";
	
	$sql_id="SELECT MAX( id ) as num FROM  `hotel_booking_tbl` ";
	$result_id=mysql_query($sql_id);
	$row_id=mysql_fetch_array($result_id);	   
	$id= $row_id['num'];	
	$id=$id+1;

	 $dt=new DateTime();
	 $dt=$dt->format('dmY');															 
	 $order_no="INV".$dt."".sprintf('%02d',$id);
	 
	 $dt=new DateTime();
	 $order_date_time=$dt->format('Y-m-d h:i:s');
	 
	 $_SESSION['sql']="INSERT INTO `hotel_booking_tbl` (`id`, `first_name`, `last_name`, `mobile_no`, `email`, `country`, `city`, `order_date_time`, `order_no`, `hotel_name`, `hotel_address`,`check_in_date`, `check_out_date`, `check_in_time`, `check_out_time`, `room_name`, `no_of_rooms`, `guests`,`no_of_nights`, `room_rate`, `sgst`, `cgst`, `grand_total`) 
	 VALUES ('', '$first_name', '$last_name', '$mobile_no', '$email', '$country', '$city', '$order_date_time', '$order_no', '$hotel_name', '$hotel_address','$check_in_date', '$check_out_date', '$check_in_time', '$check_out_time', '$room_name', '$no_of_rooms', '$guests','$no_of_nights', '$room_rate', '$sgst', '$cgst', '$grand_total');";
	 
	 payment($mobile_no,$grand_total);
}
?>
 <style>
		ul.dropdown-menu
		{
		  width:89%;
		}
		.fsize20
		{
			margin-bottom:0 !important;
		}
		</style>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800" rel="stylesheet" type="text/css" />
		<!--<link href="assets1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />-->
		<link href="assets1/css/font-awesome.css" rel="stylesheet" type="text/css" />
		<link href="assets2/plugins/owl-carousel/owl.carousel.css" rel="stylesheet" type="text/css" />
		<link href="assets2/plugins/owl-carousel/owl.theme.css" rel="stylesheet" type="text/css" />
		<link href="assets2/plugins/owl-carousel/owl.transitions.css" rel="stylesheet" type="text/css" />		
		<link href="assets2/css/realestate.css" rel="stylesheet" type="text/css" />
		<link href="assets2/css/essentials.css" rel="stylesheet" type="text/css" />
		<link href="assets2/css/layout.css" rel="stylesheet" type="text/css" />								
	</head>
	<body>
	<div id="wrapper">
			<section class="container top-no-header">
				<div class="row">
				    
					<div class="col-md-6  shadow" style="background:#fff;padding:20px">						
						<h3 class="page-header nomargin-top" style="border:none">
							<strong class="styleColor">Booking </strong>Summary					
						</h3>
                        <h4 class="page-header nomargin-top nomargin-bottom" style="border:none;color:#2564e3">
							<strong class="styleColor"><?php echo $hotel_name;?>, </strong><?php echo $address.", ".$city_name;?>					
						</h4>
						<div class="col-md-6">
						  <?php
						     $day2=explode("/",$_GET["check_out"]);
							 $day2=$day2[2]."-".$day2[1]."-".$day2[0];
							 $day1=explode("/",$_GET["check_in"]);
							 $day1=$day1[2]."-".$day1[1]."-".$day1[0]; 
						     $dt1=new DateTime($day1);							
							 $dt2=new DateTime($day2);	
						  ?>
						  <span style="color: #ff5c5c;font-weight:bold;font-size:16px">Check In</span><?php echo " : ".$dt1->format('M d, Y')." ".$check_in_time;?>
						</div>
						<div class="col-md-6">
						 <span style="color:#ff5c5c;font-weight:bold;font-size:16px">Check Out</span><?php echo " : ".$dt2->format('M d, Y')." ".$check_out_time;?>
						</div>
						<div class="divider half-margins"><!-- divider -->
							
						</div>
						<?php
						 $sql_details="SELECT * FROM room_type_tbl WHERE id='".$_GET["room_type_id"]."'";
						 $result_details=mysql_query($sql_details);
						 $row_details=mysql_fetch_array($result_details);
						 
						 $diff = abs(strtotime($day2) - strtotime($day1));
						 						 

						$years = floor($diff / (365*60*60*24));
						$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
						$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
						
						$room_total=$days *  $_GET["rooms"] * $row_details['rate'];
						$cgst_amount=$room_total*($cgst/100);
						$sgst_amount=$room_total*($sgst/100);
						$tax=$cgst_amount+$sgst_amount;
						
						$booking_amount=$tax+$room_total;
						
						?>
                       <div class="col-md-6 col-sm-6 col-xs-6">
						<p style="font-size:18px">
							<span class="block"><strong><?php echo $_GET["rooms"]." ". $row_details['room_type']." : ";?>  </strong></span>
							<span class="block">Price / Night :</span> 
							<span class="block">No of Nights :</span>
							<span class="block">No of Rooms :</span>
							<span class="block">No of Adults :</span>
						</p>
						</div>
						
					   <div class="col-md-6 col-sm-6 col-xs-6">
						<p style="font-size:14px">
							<span class="block"><strong><i class="fa fa-inr"></i> <?php echo "  ".number_format($room_total,2, '.', ',');?></strong></span>
							<span class="block"><i class="fa fa-inr"></i> <?php echo "  ".number_format($row_details['rate'],2, '.', ',');?></span>
							<span class="block"><i class="fa fa-inr"></i> X <?php echo $days; ?></span>
							<span class="block"><i class="fa fa-inr"></i> X <?php echo $_GET["rooms"];?></span>
							<span class="block"><i class="fa fa-users"></i> X <?php echo $_GET["guests"];?></span>
						</p>
						</div>

						<div class="divider half-margins"><!-- divider -->
							
						</div>
						
						<div class="col-md-6 col-sm-6 col-xs-6">
						<p style="font-size:18px">
							<span class="block"><strong>Hotel Taxes : </strong></span>
							<span class="block">SGST ( <?php echo $sgst."%"  ?> ) : 
							<span class="block">CGST ( <?php echo $cgst."%"  ?> ) : 
							
						</p>
						</div>
						
					   <div class="col-md-6 col-sm-6 col-xs-6">
						<p style="font-size:14px">
							<span class="block"><strong><i class="fa fa-inr"></i> <?php echo "  ".number_format($tax,2, '.', ',');?></strong></span>
							<span class="block"><i class="fa fa-inr"></i> <?php echo "  ".number_format($cgst_amount,2, '.', ',');?></span>
							<span class="block"><i class="fa fa-inr"></i> <?php echo "  ".number_format($sgst_amount,2, '.', ',');?></span>
							
						</p>
						</div>
						<div class="divider half-margins"><!-- divider -->
							
						</div>
						
						<div class="col-md-6 col-sm-6 col-xs-6">
						<p style="font-size:18px">
							
							<span class="block"><strong>Booking Amount</strong></span>
						</p>
						</div>
						
						<div class="col-md-6 col-sm-6 col-xs-6">
						<p style="font-size:14px">							
							<span class="block"><strong><i class="fa fa-inr"></i> <?php echo "  ".number_format($booking_amount,2, '.', ',');?></strong></span>
						</p>
						</div>												
					</div> 
					
					<div class="col-md-6">
						<h2>Make Payment</h2>
						<form class="white-row shadow" method="post" action="">
                          	<input type="hidden" name="hotel_name" value="<?php echo $hotel_name;?>">				
							<input type="hidden" name="hotel_address" value="<?php echo $address.",".$city_name;?>">
							<input type="hidden" name="check_in_date" value="<?php echo $day1; ?>">	
							<input type="hidden" name="check_out_date" value="<?php echo $day2; ?>">	
							<input type="hidden" name="check_in_time" value="<?php echo $check_in_time; ?>">	
							<input type="hidden" name="check_out_time" value="<?php echo $check_out_time; ?>">	
							<input type="hidden" name="room_name" value="<?php echo $row_details['room_type'];?>">	
							<input type="hidden" name="no_of_rooms" value="<?php echo $_GET["rooms"];?>">	
							<input type="hidden" name="no_of_nights" value="<?php echo $days; ?>" >
							<input type="hidden" name="room_rate" value="<?php echo $row_details['rate'];?>">
							<input type="hidden" name="sgst" value="<?php echo $sgst; ?>">
							<input type="hidden" name="cgst" value="<?php echo $cgst; ?>">
							<input type="hidden" name="grand_total" value="<?php echo $booking_amount;?>">
							<input type="hidden" name="guests" value="<?php  echo $_GET["guests"];?>">
							<div class="row">
								<div class="form-group">
									<div class="col-md-6">
										<label class="control-label">First Name</label>
										<input required type="text" class="form-control" name="first_name">
									</div>
									
									<div class="col-md-6">
										<label class="control-label">Last Name</label>
										<input required type="text" class="form-control" name="last_name">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="form-group">
									<div class="col-md-6">
										<label class="control-label">Mobile No.</label>
										<input required type="text" class="form-control" name="mobile_no">
									</div>
									
									<div class="col-md-6">
										<label class="control-label">Email</label>
										<input required type="email" class="form-control" name="email">
									</div>
								</div>
							</div>

							<!-- Card Type -->
							<div class="row">
								<div class="form-group">
									<div class="col-md-6">
										<label class="control-label">Country</label>
										<select name="country" class="form-control pointer">
											<option value="India">India</option>											
										</select>
									</div>
									
									<div class="col-md-6">
										<label class="control-label">City</label>
										<input required type="text" class="form-control" name="city">
									</div>
								</div>
							</div>

						   <div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<label class="control-label">Amount to be Paid</label>
										 <input  type="text" disabled class="form-control" name="payment" value="<?php echo "  ".number_format($booking_amount,2, '.', ',')." INR";?>">										
										</select>
									</div>
									
								</div>
							</div>

							
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<button class="btn btn-primary btn-lg" type="submit" name="submit"><i class="fa fa-check"></i> &nbsp; PAY NOW</button>
									</div>
								</div>
							</div>
							<!-- /CREDIT CARD -->

						</form>

					</div>
                			
				</div>
			</section>
	</div>		
<?php include("footer.php");?>
<script src="assets/js/jquery.2.1.1.min.js"></script>
	
	