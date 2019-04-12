<?php
include_once('header.php');

$msg = "";
if (isset($_POST["update"])) {

		if ($_POST["approved"] == "CONFIRM") {
				$sql_check = "SELECT * FROM booking_tbl WHERE  id='" . $_POST["booking_id"] . "' AND status='CONFIRM'";
				$result_check = mysql_query($sql_check);
				if (mysql_num_rows($result_check) == 0) {
						$service_charge = $row_check["service_charge"];
						$sgst = $row_check["sgst"];
						$cgst = $row_check["cgst"];
						$igst = $row_check["igst"];
						$gst = $sgst + $cgst + $igst;

						foreach ($_POST["pnr"] as $key => $pnr) {
								$sql_pnr = "SELECT u.type,t.* FROM tickets_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.pnr='" . $pnr . "' AND t.approved=1";
								$result_pnr = mysql_query($sql_pnr);
								$row_pnr = mysql_fetch_array($result_pnr);
								$ticket_fare = $row_pnr["total"];
								$no_of_person = $row_pnr["no_of_person"];
								if ($no_of_person < 1)
									die($pnr . " has no seats");
							}

						foreach ($_POST["pnr"] as $key => $pnr) {

								$sql_pnr = "SELECT u.type,t.* FROM tickets_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.pnr='" . $pnr . "'";
								$result_pnr = mysql_query($sql_pnr);
								$row_pnr = mysql_fetch_array($result_pnr);
								$ticket_fare = $row_pnr["total"];
								$no_of_person = $row_pnr["no_of_person"];




								$sql_get_customer_info = "SELECT * FROM customer_information_tbl WHERE id='" . $_POST["customer_info_id"][$key] . "'";
								$result_customer_info = mysql_query($sql_get_customer_info);
								$row_customer_info = mysql_fetch_array($result_customer_info);

								$sql_get = "SELECT * FROM booking_tbl WHERE  id='" . $_POST["booking_id"] . "'";
								$result_get = mysql_query($sql_get);

								$row_get = mysql_fetch_array($result_get);
								$ticket_id = $row_get["ticket_id"];


								$sql_ticket = "SELECT t.*,u.email FROM tickets_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.id='" . $ticket_id . "'";
								$result_ticket = mysql_query($sql_ticket);
								$row_ticket = mysql_fetch_array($result_ticket);
								$no_of_person = $row_ticket["no_of_person"];
								$no_of_person = $no_of_person + 1;
								$email = $row_ticket["email"];

								//echo "OLD ".$no_of_person."<br>";								
								//$sql_update_ticket="UPDATE tickets_tbl SET no_of_person='$no_of_person' WHERE id='".$ticket_id."'";
								//mysql_query($sql_update_ticket);


								$sql_current_ticket = "SELECT * FROM tickets_tbl WHERE id='" . $row_pnr["id"] . "'";
								$result_current_ticket = mysql_query($sql_current_ticket);
								$row_current_ticket = mysql_fetch_array($result_current_ticket);

								$rate = $row_current_ticket["total"];
								$amount = $rate;
								$total = $amount;
								$current_no_of_person = $row_current_ticket["no_of_person"] - 1;
								$grand_total = $gst + $service_charge + $total;


								$sql_update_booking = "UPDATE booking_tbl SET status='CONFIRM',seller_status='PENDING',seller_id='" . $row_current_ticket["user_id"] . "' WHERE id='" . $_POST["booking_id"] . "'";
								mysql_query($sql_update_booking);


								$sql_new_booking = "INSERT INTO refrence_booking_tbl 
				(date,
				booking_confirm_date,
				ticket_id,
				prefix,
				first_name,
				last_name,
				mobile_no,
				age,
				email,				
				pnr,
				ticket_fare,
				seller_id,
				customer_id,
				status,
				qty,
				rate,
				amount,
				service_charge,
				igst,
				cgst,
				sgst,
				total,
				type,
				booking_id
				
				)
				VALUES
				('" . date("Y-m-d") . "',
				'" . date("Y-m-d h:i:s") . "',
				'" . $row_pnr["id"] . "',
				'" . $row_customer_info["prefix"] . "',
				'" . $row_customer_info["first_name"] . "',
				'" . $row_customer_info["last_name"] . "',
				'" . $row_customer_info["mobile_no"] . "',
				'" . $row_customer_info["age"] . "',
				'" . $row_customer_info["email"] . "',				
				'$pnr',
				'" . $row_customer_info["ticket_fare"] . "',	
				'" . $row_pnr["user_id"] . "',
				'" . $row_get["customer_id"] . "',
				'CONFIRM',
				'1',
				'" . $total . "',
				'" . $total . "',
				'0',
				'0',
				'0',
				'0','
				$total',
				'" . $row_pnr["type"] . "',
				'" . $_POST["booking_id"] . "'
				
				)";
								mysql_query($sql_new_booking);
								$refrence_id = mysql_insert_id();

								$sql_customer_info_update = "UPDATE customer_information_tbl SET pnr='" . $pnr . "',ticket_fare='$ticket_fare',refrence_id='" . $refrence_id . "' WHERE id='" . $_POST["customer_info_id"][$key] . "'";
								mysql_query($sql_customer_info_update);

								$sql_credit = "INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('" . date("Y-m-d h:i:s") . "','" . $row_pnr["user_id"] . "' ,'$amount','','" . $_POST["booking_id"] . "' ,'CR')";
								mysql_query($sql_credit);

								// echo "NEW ".$current_no_of_person."<br>"; 					
								$sql_update_ticket = "UPDATE tickets_tbl SET no_of_person='$current_no_of_person' WHERE id='" . $row_pnr["id"] . "'";
								mysql_query($sql_update_ticket);

								$msg = "<div class='alert alert-success'>Approved Successfully</div>";
							}


						$link = "";
						$msg0 = "Your Booking is  Approved ";
						$msg1 = "";
						$msg2 = "";
						$subject = "Booking Approve";
						$emailaddress = "OXYTRA <noreply@yourwebsite.co.in>";
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						// Create email headers
						$headers .= 'From: ' . $emailaddress . "\r\n" .
							'Reply-To: ' . $emailaddress . "\r\n" .
							'X-Mailer: PHP/' . phpversion();


						$message = '<html><body><table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;max-width:500px;color:#444444;text-align:left;background:#ffffff;border:1px solid #efefef"> 
					<tbody>
					<tr>
						<td bgcolor="#1a1a1a" background="https://ci4.googleusercontent.com/proxy/W2ALyHqtDi_l2MggkjvU3Kx_lXLpTmZgAIShkDbS_fywS0r1NS5timKZDvcG76_FjFp6pZXZ5xPFy7SFqaUZ8SiIYw=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/mailerBG.gif" align="center" valign="top"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"> 
						<tbody> 
						<tr> 
							<td width="25"><img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd"></td>
							<td width="450" align="center"> 
							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;font-size:20px;color:#444444;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif"> 
							<tbody> <tr> <td height="20"></td>
						</tr>
						
						<tr> 
							<td align="center">
							<a href="#m_-4915400257058116905_"><img src="https://yourwebsite.co.in/oxytra/upload/logo.png" alt="OXTRA" width="180" height="auto" border="0" style="margin:0;display:block;font-family:Arial,Helvetica,sans-serif;color:#007ebe;font-size:20px;text-align:center;font-weight:bold" class="CToWUd"></a> 
							</td>
						</tr>
						
						<tr> 
						<td height="20" style="border-bottom:1px solid #424649"></td>
						</tr>
						</tbody> 
					  </table> 
					  
					  <table border="0" cellspacing="0" cellpadding="0" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;color:#ffffff;text-align:center"> <tbody> <tr> <td height="20"></td></tr><tr> <td> <table width="100%" cellpadding="5" cellspacing="0" border="0" style="max-width:450px"> 
					  <tbody>
					  <tr> 
					  <td align="center" width="88" valign="top">
						<img src="https://ci3.googleusercontent.com/proxy/iGn4qvSkjqio9ZZtWraZHxDtcdGWXwp3dV4wsEKsxD2xvGybqW3-7oSijPd6lIrxxpjZm_bbgp-57yGbTl-oZijVqv6ulfSOQJ3ytTaAerc=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/account-activation-ic.png" width="80" height="54" vspace="0" hspace="0" align="absmiddle" border="0" class="CToWUd">
					   </td>					
						<td valign="middle" style="font-size:18px;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;text-align:left"> ' . $msg0 . ' </td>
					  </tr>
					  </tbody>
					  </table> 
					  
					  </td>
					  </tr>
					  
					  <tr> 
					  <td height="20"></td>
					  </tr>
					  
					  <tr> 
					  <td align="center"> 
					  <table width="189" cellpadding="0" cellspacing="0" border="0" align="center">
					  <tbody>
					  <tr> 
					  <td align="center"> 
					  <a href="#" style="display:block;background:#ed3b12;border-radius:3px;color:#ffffff;text-decoration:none;font-size:16px;text-align:center;line-height:37px;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;padding:0 10px" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://www.Job4Artist.in/redirect.php?type%3DverifyEmail%26src%3Dregistration%26em%3D%252B4f8IzHnK%252F5DnDaMFXK7e%252BrLZTT0Y5Ra%26t%3D3rdZWYQH8PM8d8mxCUHEfw%253D%253D&amp;source=gmail&amp;ust=1527935657727000&amp;usg=AFQjCNFUmFtukAzsTBSNN17O8mmr-daNCw"></a>
					  </td>
					  </tr>
					  </tbody>
					  </table> 
					  
					  </td>
					  </tr>
					  
					  <tr> 
					  <td height="30"></td>
					  </tr>
					  </tbody> 
					  </table> 
					  </td>
					  
					  <td width="25">
					  <img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd"></td>
					  </tr>
					  </tbody> 
					  </table> 
					  </td>
					</tr>
					
					<tr>
					  <td>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
						 <tbody> 
						 </tbody> 
						 </table>
					  </td>
					</tr>
																																																
				<tr> <td> <table width="100%" border="0" cellspacing="0" cellpadding="0"> <tbody>	
				<tr> 
				<td width="25">
				<img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.dazzlr.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd">
				</td>
				<td width="450" valign="top"> 
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;max-width:500px;color:#666666;text-align:left;font-size:13px"> 
				<tbody> 
				<tr> 
				<td height="20"></td>
				</tr>


				
				<tr> 
				<td height="25">
				</td>
				</tr>
				<tr> 
				<td>' . $msg1 . '</td></tr>

				<tr> <td height="15"></td></tr><tr> <td><strong style="color:#333333">' . $msg2 . '</strong></td></tr>

				<tr> <td height="25"></td></tr><tr> <td>Best Regards, <br><strong style="color:#333333">The <span class="il">OXYTRA</span> Team</strong></td></tr></tbody> 
				</table> </td><td width="25">
				<img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.dazzlr.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd">
				</td>			
				</tr>
				</tbody>
				</table>
				</td>
				</tr>
				
					</tbody>
					</table></body></html>';
						mail($email, $subject, $message, $headers);
					} else {
						$msg = "<div class='alert alert-danger'>This Booking already Confirmed</div>";
					}
			}


		if ($_POST["approved"] == "CANCELLED") {
				$admin_cancel_charge = isset($_POST["admin_cancel_charge"]) ? $_POST["admin_cancel_charge"] : "";
				$sql_check = "SELECT * FROM booking_tbl WHERE  id='" . $_POST["booking_id"] . "' AND status='CANCELLED'";
				$result_check = mysql_query($sql_check);
				if (mysql_num_rows($result_check) == 0) {

						$sql_end_details = "SELECT * FROM booking_tbl WHERE  id='" . $_POST["booking_id"] . "' ";
						$end_result_check = mysql_query($sql_end_details);
						$end_row_check = mysql_fetch_array($end_result_check);
						$total = $end_row_check["total"];
						$end_customer_id = $end_row_check["customer_id"];



						$sql_customer_credit = "INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('" . date("Y-m-d h:i:s") . "','$end_customer_id','" . $total . "','REFUND','" . $_POST["booking_id"] . "','CR')";
						mysql_query($sql_customer_credit);

						$sql_customer_debit = "INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('" . date("Y-m-d h:i:s") . "','$end_customer_id','" . (0 - $admin_cancel_charge) . "','ADMIN CANCELLATION CHARGE','" . $_POST["booking_id"] . "','DR')";
						mysql_query($sql_customer_debit);

						$sql_update = "UPDATE booking_tbl SET status='" . $_POST["approved"] . "',admin_cancel_charge='" . $_POST["admin_cancel_charge"] . "',refund_amount=(total-$admin_cancel_charge),cancel_date='" . date("Y-m-d h:i:s") . "' WHERE id='" . $_POST["booking_id"] . "'";
						if (mysql_query($sql_update)) {
								foreach ($_POST["refrence_booking_id"] as $key => $pnr) {

										//echo $_POST["refrence_booking_id"][$key]."<br>";
										$sql_get = "SELECT * FROM refrence_booking_tbl  WHERE id='" . $_POST["refrence_booking_id"][$key] . "'";
										$result_get = mysql_query($sql_get);
										$row_get = mysql_fetch_array($result_get);

										$seller_id = $row_get["seller_id"];
										$ticket_id = $row_get["ticket_id"];
										$ticket_fare = $row_get["ticket_fare"];
										$booking_id = $row_get["booking_id"];
										$supplier_cancel_charge = $row_get["supplier_cancel_charge"];
										$one = 1;

										$sql_update_refrence = "UPDATE refrence_booking_tbl set status='CANCELLED' WHERE id='" . $_POST["refrence_booking_id"][$key] . "'";
										mysql_query($sql_update_refrence);

										$sql_update = "UPDATE tickets_tbl SET  no_of_person=(no_of_person+1) WHERE id='" . $ticket_id . "'";
										if (mysql_query($sql_update)) {
												$sql_debit = "INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('" . date("Y-m-d h:i:s") . "','$seller_id','" . (0 - $ticket_fare) . "','BOOKING CANCEL','" . $booking_id . "','DR')";
												if (mysql_query($sql_debit)) {
														$sql_credit = "INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('" . date("Y-m-d h:i:s") . "','$seller_id','" . $supplier_cancel_charge . "','SUPPPLIER CANCELLATION CHARGE','" . $booking_id . "','CR')";
														if (mysql_query($sql_credit))
															$msg = "<div class='alert alert-success'>Cancelled Successfully</div>";
														else
															echo " SQL DEBIT ERROR : " . mysql_error();
													} else {
														echo " SQL CREDIT ERROR : " . mysql_error();
													}
											} else {
												echo " UPDATE TICKET QUERY ERROR : " . mysql_error();
											}
									}
							} else {
								echo " REFUND QUERY ERROR : " . mysql_error();
							}
					} else {
						$msg = "<div class='alert alert-danger'>This Booking already Cancelled</div>";
					}
			}
	}
if (!isset($_GET["id"])) {
		?>
	<script>
		window.location.href = "bookings.php";
	</script>
<?php
} else {
		$sql = "SELECT `cus`.`prefix`, `cus`.`age`, `cus`.`first_name`, `cus`.`last_name`, `cus`.`email`, `cus`.`mobile_no`, 
	`u`.`name`, `u`.`email`, `u`.`mobile`, `b`.`id`, `t`.`ticket_no`,t.price,t.discount,t.markup,t.admin_markup, `b`.`pnr`, 
	`b`.`date`, `c`.`city` as `source`, `c1`.`city` as `source1`, `ct`.`city` as `destination`, `ct1`.`city` as `destination1`, 
	`a`.`airline`, `a1`.`airline` as `airline1`, `t`.`class`, `t`.`class1`, `t`.`departure_date_time`, `t`.`departure_date_time1`, 
	`t`.`arrival_date_time`, `t`.`arrival_date_time1`, `t`.`trip_type`, `t`.`terminal`, `t`.`terminal1`, `t`.`terminal2`, `t`.`terminal3`, 
	`t`.`flight_no`, `t`.`flight_no1`, `b`.`service_charge`, `b`.`sgst`, `b`.`cgst`, `b`.`igst`, `b`.`rate`, `b`.`qty`, `b`.`amount`, `b`.`total`, 
	`b`.`type`, `b`.`status`,b.supplier_cancel_charge,b.cancel_request_date,b.admin_cancel_charge,b.cancel_date,b.refund_amount,b.customer_id,b.seller_id,
	 b.admin_cancel_charge, t.source as src, t.destination as dest	 
	 FROM `tickets_tbl` as `t` JOIN `booking_tbl` `b` ON `b`.`ticket_id` = `t`.`id` 
	 JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` LEFT JOIN `airline_tbl` `a1` ON `a1`.`id` = `t`.`airline1` 
	 JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
	 LEFT JOIN `city_tbl` `c1` ON `c1`.`id` = `t`.`source1` LEFT JOIN `city_tbl` `ct1` ON `ct1`.`id` = `t`.`destination1` 
	 JOIN `user_tbl` `u` ON `b`.`customer_id` =`u`.`id` LEFT JOIN `customer_information_tbl` `cus` ON `b`.`id` =`cus`.`booking_id` 
	 WHERE `b`.`id` ='" . $_GET["id"] . "'";

		$qty = 0;
		$triptype = "ONE";
		$source = -1;
		$destination = -1;
		$departure_date_time = '';
		$result = mysql_query($sql);
		if ($result) {
				$row = mysql_fetch_array($result);

				$qty = $row["qty"];
				$source = $row["src"];
				$destination = $row["dest"];
				$departure_date_time = $row["departure_date_time"];
				$arrival_date_time = $row["arrival_date_time"];

				if ($row["trip_type"] == "ONE") {
					$trip_type = "ONE WAY";
					$triptype = "ONE";
				} else {
					$trip_type = "ROUND TRIP";
					$triptype = "ROUND";
				}
			} else {
				echo "" . mysql_error();
			}
		$departure_date_time = date("Y-m-d", strtotime($departure_date_time));
		echo $departure_date_time;
		/*Get booking details by booking id and show all the available and approved tickets*/
		$sql_availabletickets = "SELECT `t`.`id` as ticket_no, `t`.`source`, `t`.`destination`, `t`.`pnr`, `t`.`departure_date_time`, `t`.`arrival_date_time`, `t`.`total`, `t`.`admin_markup`, `t`.`markup`, `t`.`sale_type`, `t`.`refundable`, `c`.`city` as `source_city`, `ct`.`city` as `destination_city`, `t`.`no_of_person`, `t`.`class`, `a`.`image`,a.airline, `t`.`user_id`, `t`.`no_of_stops`,t.trip_type,u.name,u.user_id as uid
    	  FROM `tickets_tbl` `t` JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` 
    	  JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` 
    	  JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
    	  JOIN `user_tbl` `u` ON `u`.`id` = `t`.`user_id` 
    	  WHERE `t`.`source` = '$source' AND `t`.`destination` = '$destination' AND DATE_FORMAT(t.departure_date_time, '%Y-%m-%d') = '$departure_date_time' 
    	  AND `t`.`trip_type` = '$triptype' AND `t`.`approved` = '1' AND `t`.`no_of_person` >= 1 ORDER BY (total+admin_markup) ASC";


		$sql_assigned_seller = "select slr.`name` as customer_name, slr.`mobile` as customer_mobile, slr.user_id as customer_code, br.id, br.ticket_id, br.`status`, br.qty as ordered_qty, br.rate as supplier_rate, br.total, br.pnr, br.booking_id, 
		bk.qty as no_of_persons, bk.rate as billed_rate, bk.total as billed_total, tk.class, tk.aircode, srcc.city as source_city, dstc.city destination_city,
		tk.departure_date_time, tk.arrival_date_time, air.airline, br.`date` as ordered_date
		from refrence_booking_tbl br inner join booking_tbl bk on br.booking_id=bk.id and bk.qty>0 and br.qty>0
		inner join user_tbl slr on slr.id=bk.seller_id and slr.active=1
		inner join tickets_tbl tk on tk.id=br.ticket_id and tk.approved=1
		inner join city_tbl srcc on srcc.id=tk.`source`
		inner join city_tbl dstc on dstc.id=tk.destination
		inner join airline_tbl air on air.id=tk.airline
		where bk.id=".$_GET["id"];
		//echo $sql_availabletickets;
		//die;
	}
	$pax = 0;
?>
<title>Ticket Details | <?php echo $row_top['site_title']; ?></title>
<style>
	.ui-helper-hidden-accessible {
		width: 100%;
		float: left;
	}
</style>
</head>
<?php
include_once('leftbar.php');
?>
<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header" id="header">
				<!--.page-header -->
				<h1>
					<?php echo $row["source"]; ?> TO <?php echo $row["destination"] . " ( " . $trip_type . " )"; ?><br>
					<?php
					if ($row["pnr"] == "") {
							echo "PENDING";
						} else {
							echo "PNR : #" . $row["pnr"] . "</b>";
						} ?>

				</h1>

				<?php if (!empty($msg)) echo $msg; ?>
			</div><!-- /.page-header -->
			<form id="frm_customer" enctype="multipart/form-data" action="" method="POST">
				<input type="hidden" name="booking_id" value="<?php echo $_GET["id"]; ?>">
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<!--Widget col-md-8 start-->
						<div style="">

							<h3 style="color:#2679b5;font-size:18px">PASSENGER DETAILS</h3>
							<hr style="margin:0"/>
							<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
								<thead>
									<tr>
										<th>Si No.</th>
										<th>Passenger Name</th>
										<th>Age</th>
										<th>Mobile No.</th>
										<th>Email</th>
										<th>PNR</th>
										<?php
										if ($row["status"] == "CONFIRM") {
												?>
											<th>Booking Request No.</th>
											<th>Supplier</th>
										<?php
									}
								?>

										<?php
										if ($row["status"] == "REQUESTED FOR CANCEL" || $row["status"] == "CANCELLED") {
												?>
											<th>Supplier Cancellation Charge</th>

										<?php
									}
								?>
									</tr>
								</thead>
								<tbody id="grid1">
									<?php
									$dateDiff = intval((strtotime($row["arrival_date_time"]) - strtotime($row["departure_date_time"])) / 60);
									$ctr = 1;
									$all_supplier_cancel = 0;
									$sql_check = "SELECT * FROM refrence_booking_tbl WHERE booking_id='" . $_GET["id"] . "'";
									$result_check = mysql_query($sql_check);
									if (mysql_num_rows($result_check) == 0) {
											$sql_details = "SELECT * FROM customer_information_tbl WHERE booking_id='" . $_GET["id"] . "' ORDER BY id ASC";
										} else {
											$sql_details = "SELECT * FROM refrence_booking_tbl WHERE booking_id='" . $_GET["id"] . "' ORDER BY id ASC";
										}
									$result_details = mysql_query($sql_details);
									while ($row_details = mysql_fetch_array($result_details)) {
											?>
										<tr>
											<td><?php echo $ctr; ?></td>
											<td><?php echo $row_details["prefix"] . " " . $row_details["first_name"] . " " . $row_details["last_name"]; ?></td>
											<td><?php echo $row_details["age"]; ?></td>
											<td><?php echo $row_details["mobile_no"]; ?></td>
											<td><?php echo $row_details["email"]; ?></td>
											<td><input type="text" name="pnr[]" class="form-control" required value="<?php echo $row_details["pnr"]; ?>">
												<?php
												if ($row["status"] == "CONFIRM") {
														$sql_get_supplier = "SELECT u.* FROM user_tbl u INNER JOIN  refrence_booking_tbl r ON r.seller_id=u.id WHERE r.id='" . $row_details["id"] . "'";
														$result_get_supplier = mysql_query($sql_get_supplier);
														$row_get_supplier = mysql_fetch_array($result_get_supplier);
														?>
												<td><?php echo $row_details["id"]; ?></td>
												<td><?php echo $row_get_supplier["user_id"]; ?></td>
											<?php
										}
									?>

											<?php
											if ($row["status"] == "REQUESTED FOR CANCEL" || $row["status"] == "CANCELLED") {
													if ($row_details["supplier_cancel_request"] == 1) {
															?>
													<td>
														<input type="text" name="supplier_cancel_charge[]" value="<?php echo $row_details["supplier_cancel_charge"]; ?>">
														<input type="hidden" name="supplier_status[]" value="1">
														<input type="hidden" name="refrence_booking_id[]" value="<?php echo $row_details["id"]; ?>">
													</td>
													<?php
													$all_supplier_cancel = 1;
												} else {

													?>
													<td>Supplier Not Approved
														<input type="hidden" name="supplier_status[]" value="0">
													</td>
													<?php
													$all_supplier_cancel = 0;
												}
										}
									?>

											<input type="hidden" name="customer_info_id[]" class="form-control" value="<?php echo $row_details["id"]; ?>"></td>
										</tr>
										<?php
										$ctr++;
									}
								?>
								</tbody>
								<?php
								if ($row["status"] == "REQUESTED FOR CANCEL" || $row["status"] == "CANCELLED") {
										?>
									<tr>
										<th colspan="7">Admin Cancellation Charge</th>
										<th><input type="text" name="admin_cancel_charge" class="form-control" value="<?php echo $row["admin_cancel_charge"]; ?>"></th>
									</tr>
								<?php
							}
						?>


							</table>
							<h3 style="color:#2679b5;font-size:18px">GOING FLIGHT DETAILS</h3>
							<hr style="margin:0"/>
							<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
								<thead>
									<tr>
										<th>Dep</th>
										<th>From</th>
										<th>To</th>
										<th>Duration</th>
										<th>Airline</th>
										<th>Class</th>
										<th>Flight No.</th>
										<th>Dep Term</th>
										<th>Arr Term</th>
									</tr>
								</thead>
								<tbody id="grid1">
									<tr>
										<td><?php echo date("jS M y", strtotime($row["departure_date_time"])) . " ( " . date("h:i a", strtotime($row["departure_date_time"])) . " )"; ?></td>
										<td><?php echo $row["source"]; ?></td>
										<td><?php echo $row["destination"]; ?></td>
										<td><?php echo intval($dateDiff / 60) . " Hours " . ($dateDiff % 60) . " Minutes"; ?></th>
										<td><?php echo $row["airline"]; ?></td>
										<td><?php echo $row["class"]; ?></td>
										<td><?php echo $row["flight_no"]; ?></td>
										<td><?php echo $row["terminal"]; ?></td>
										<td><?php echo $row["terminal1"]; ?></td>
									</tr>
								</tbody>
							</table>
							<?php if ($row["trip_type"] == "ROUND") {

									$dateDiff1 = intval((strtotime($row["arrival_date_time1"]) - strtotime($row["departure_date_time1"])) / 60);
									?>

								<h3 style="color:#2679b5;font-size:18px">RETURNING FLIGHT DETAILS</h3>
								<hr style="margin:0"/>
								<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
									<thead>
										<tr>
											<th>Dep</th>
											<th>From</th>
											<th>To</th>
											<th>Duration</th>
											<th>Airline</th>
											<th>Class</th>
											<th>Flight No.</th>
											<th>Dep Term</th>
											<th>Arr Term</th>
										</tr>
									</thead>
									<tbody id="grid1">
										<tr>
											<td><?php echo date("jS M y", strtotime($row["departure_date_time1"])) . " ( " . date("h:i a", strtotime($row["departure_date_time1"])) . " )"; ?></td>
											<td><?php echo $row["source1"]; ?></td>
											<td><?php echo $row["destination1"]; ?></td>
											<td><?php echo intval($dateDiff1 / 60) . " Hours " . ($dateDiff1 % 60) . " Minutes"; ?></th>
											<td><?php echo $row["airline1"]; ?></td>
											<td><?php echo $row["class1"]; ?></td>
											<td><?php echo $row["flight_no1"]; ?></td>
											<td><?php echo $row["terminal2"]; ?></td>
											<td><?php echo $row["terminal3"]; ?></td>
										</tr>
									</tbody>
								</table>
							<?php } ?>
							<h3 style="color:#2679b5;font-size:18px">BOOKING DETAILS</h3>
							<hr style="margin:0"/>
							<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
								<thead>
									<tr>
										<th>Booking Request No.</th>
										<th>Booking Date</th>
										<th>Price Summary</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="grid1">
									<tr style="background:#fff">
										<td><?php echo $_GET["id"]; ?></td>
										<td><?php echo date("jS M y", strtotime($row["date"])) . " ( " . date("h:i a", strtotime($row["date"])) . " )"; ?></td>
										<td>

											<b>Ticket Rate : </b><?php echo number_format(($row["rate"]), 2, ".", ","); ?><br />
											<!--<b>Supplier Markup : </b><?php echo number_format($row["markup"], 2, ".", ","); ?><br/>
												<b>Admin Markup: </b><?php echo number_format($row["admin_markup"], 2, ".", ","); ?><br/>-->
											<b>Total Ticket Fare (each) : </b><?php echo number_format($row["rate"], 2, ".", ","); ?><br />
											<b>Qty : </b><?php echo $row["qty"]; $pax = $row["qty"];?><br />
											<b>Sub Total : </b><?php echo number_format($row["amount"], 2, ".", ","); ?><br />
											<b>Service Charge : </b><?php echo number_format($row["service_charge"], 2, ".", ","); ?><br />
											<b>GST : </b><?php echo number_format(($row["igst"] + $row["sgst"] + $row["cgst"]), 2, ".", ","); ?><br />
											<b>Grand Total : </b><?php echo number_format($row["total"], 2, ".", ","); ?>
										</td>
										<td>
											<?php
											echo  $row["status"] . "<br>";
											if ($row["status"] == "PROCESSING FOR CANCEL") {

													echo "<b>Request Date : </b>" . date("jS M y h:i a", strtotime($row["cancel_request_date"])) . "<br>";
												}
											if ($row["status"] == "CANCELLED") {
													//echo "<b>Supplier Cancellation Charge : </b>".number_format( $row["supplier_cancel_charge"],2,".",",")."<br>";
													echo "<b>Request Date : </b>" . date("jS M y h:i a", strtotime($row["cancel_request_date"])) . "<br>";
													echo "<b>Admin Cancellation Charge : </b>" . number_format($row["admin_cancel_charge"], 2, ".", ",") . "<br>";
													echo "<b>Cancelled Date : </b>" . date("jS M y h:i a", strtotime($row["cancel_date"])) . "<br>";
													echo "<b>Refunded : </b>" . number_format($row["refund_amount"], 2, ".", ",") . "<br>";
												}
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!--Widget col-md-8 end-->



					<!--<div class="col-xs-12 col-sm-12">
							    <div class="widget-box" style="float:left;width:100%">
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Update Status</small>
												</h4>

									</div>

									<div class="widget-body">																											                                            											
                                            <div class="form-group has-info col-xs-12 col-sm-12">
													
													
													
													<div class="col-xs-3">
													    <label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">Status</label>	
														<select id="approved"  name="approved" class="col-xs-12 col-sm-12" required>
														   <?php if ($row["status"] == "CONFIRM") { ?>                                                          
                                                            	<option value="CONFIRM" >CONFIRM</option>   
                                                           <?php }
																													if ($row["status"] == "PENDING") { ?>
                                                            	<option value="PENDING" <?php if ($row["status"] == "PENDING") echo "selected"; ?>>PENDING</option>
                                                            	<option value="CONFIRM" <?php if ($row["status"] == "CONFIRM") echo "selected"; ?>>CONFIRM</option>   
															
														   <?php }
															if ($row["status"] == "REQUESTED FOR CANCEL") { ?>
														   
														    	<option value="REQUESTED FOR CANCEL" <?php if ($row["status"] == "REQUESTED FOR CANCEL") echo "selected"; ?>>REQUESTED FOR CANCEL</option>
                                                            	<?php if ($all_supplier_cancel == 1) { ?>
																		<option value="CANCELLED" <?php if ($row["status"] == "CANCELLED") echo "selected"; ?>>CANCEL</option>  
																<?php } ?>															
															
														   <?php }
															if ($row["status"] == "CANCELLED") { ?>														    
                                                            	<option value="CANCELLED" <?php if ($row["status"] == "CANCELLED") echo "selected"; ?>>CANCELLED</option>   
                                                           <?php } ?>
														</select>
													</div>
													
													
													
															
															
											</div>	
													
													
													
									</div>
                                                                                       																						                                                                                             																																							
                                    </div>							
                            </div>-->
				</div>
				<!--<div class="row">
								<div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
									<button type="submit" class="pull-left btn btn-sm btn-primary" name="update">															
										<span class="bigger-110">Update Status</span>
									</button>																		
								</div>
								</div> -->
				<?php
				$assigned_seller_result = mysql_query($sql_assigned_seller); ?>
								
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<!--Widget col-md-8 start-->
						<h3 style="color:#2679b5;font-size:18px">ASSIGNED SELLERS</h3>
						<hr style="margin:0" />
						<input type="hidden" name="booking_request_id" value="<?php echo $_GET["id"]; ?>">
						<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
							<thead>
								<tr>
									<th>T.No</th>
									<th>Journey</th>
									<th>Status</th>
									<th>Travel Date</th>
									<th>Airline</th>
									<th>Class</th>
									<th>Rate</th>
									<th>Ord.Seats</th>
									<th>Agent</th>
									<th>Ord.Date</th>
								</tr>
							</thead>
							<?php 
								$order_qty = 0;
								if (mysql_num_rows($assigned_seller_result)>0) { ?>
								<?php while ($sellerrecords = mysql_fetch_array($assigned_seller_result, MYSQL_ASSOC)) { ?>
									<tbody id="grid1">
										<tr style="background:#fff">
											<td><?php echo $sellerrecords["ticket_id"]; ?></td>
											<td>
												<div><?php echo $sellerrecords["source_city"]; ?></div>
												<div><?php echo $sellerrecords["destination_city"]; ?></div>
											</td>
											<td><?php echo $sellerrecords["status"];?></td>
											<td>
												<i class="fa fa-plane" style="font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y", strtotime($sellerrecords["departure_date_time"])); ?> </span>(<?php echo date("h:i a", strtotime($sellerrecords["departure_date_time"])); ?>)<br />
												<i class="fa fa-plane" style="transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y", strtotime($sellerrecords["arrival_date_time"])); ?> </span>(<?php echo date("h:i a", strtotime($sellerrecords["arrival_date_time"])); ?>)
											</td>
											<td><?php echo $sellerrecords["airline"]; ?></td>
											<td><?php echo $sellerrecords["class"]; ?></td>
											<td><?php echo "<b>Supplier Rate : </b>" . $sellerrecords["billed_rate"] . "<br> <b>Portal Rate : </b>" . ($sellerrecords["supplier_rate"]); ?></td>
											<td><?php echo $sellerrecords["ordered_qty"]; ?></td>
											<td><?php echo $sellerrecords["customer_name"]; ?><br><?php echo $sellerrecords["customer_mobile"]; ?>(<?php echo $sellerrecords["customer_code"];?>)</td>
											<?php
											if ($sellerrecords["ordered_qty"]>0) {
												$order_qty += $sellerrecords["ordered_qty"];
											} 
											?>
											<td><span class="date"><?php echo date("jS M y", strtotime($sellerrecords["departure_date_time"])); ?> </span><br/>(<?php echo date("h:i a", strtotime($sellerrecords["departure_date_time"])); ?>)</td>
										</tr>
									</tbody>
								<?php } 
								}
								else {?>
								<tbody id="grid1">
									<tr style="background:#fff">
										<td colspan="10"><span>No sellers has been assigned yet</span></td>
									</tr>
								</tbody>
								<?php }?>
						</table>
					</div>
				</div>
				
				<?php
				//die;
				//echo $sql_availabletickets;
				$seller_result = mysql_query($sql_availabletickets); ?>
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<!--Widget col-md-8 start-->
						<h3 style="color:#2679b5;font-size:18px">ASSIGN BOOKING TO SELLERS</h3>
						<hr style="margin:0" />
						<input type="hidden" name="booking_request_id" value="<?php echo $_GET["id"]; ?>">
						<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
							<thead>
								<tr>
									<th>T.No</th>
									<th>Journey</th>
									<th>Type</th>
									<th>Travel Date</th>
									<th>Airline</th>
									<th>Class</th>
									<th>Rate</th>
									<th>Avl.Seats</th>
									<th>Agent</th>
									<th>Book.Seats</th>
								</tr>
							</thead>
							<?php 
							$qty = ($pax-$order_qty);
							if ($seller_result && $qty>0) { 
								?>
								<?php while ($sellerrows = mysql_fetch_array($seller_result, MYSQL_ASSOC)) { ?>
									<tbody id="grid1">
										<tr style="background:#fff">
											<td><?php echo $sellerrows["ticket_no"]; ?></td>
											<td>
												<div><?php echo $sellerrows["source_city"]; ?></div>
												<div><?php echo $sellerrows["destination_city"]; ?></div>
											</td>
											<td><?php if ($sellerrows["trip_type"] == "ONE") echo "ONE WAY";
													else echo "RETURN TRIP"; ?></td>
											<td>
												<i class="fa fa-plane" style="font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y", strtotime($sellerrows["departure_date_time"])); ?> </span>(<?php echo date("h:i a", strtotime($sellerrows["departure_date_time"])); ?>)<br />
												<i class="fa fa-plane" style="transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y", strtotime($sellerrows["arrival_date_time"])); ?> </span>(<?php echo date("h:i a", strtotime($sellerrows["arrival_date_time"])); ?>)
											</td>
											<td><?php echo $sellerrows["airline"]; ?></td>
											<td><?php echo $sellerrows["class"]; ?></td>
											<td><?php echo "<b>Supplier Rate : </b>" . $sellerrows["total"] . "<br> <b>Portal Rate : </b>" . ($sellerrows["total"] + $sellerrows["admin_markup"]); ?></td>
											<td><?php echo $sellerrows["no_of_person"]; ?></td>
											<td><?php echo $sellerrows["name"]; ?><br><?php echo $sellerrows["uid"]; ?></td>
											<?php
											
											$orderqty = 0;
											//if ($sellerrows["no_of_person"] > $qty) {
											if ($sellerrows["no_of_person"] > $qty) {
												$orderqty = $qty;
											} else {
												$orderqty = $sellerrows["no_of_person"];
											}

											$qty = $qty - $orderqty;
											?>
											<td>
												<input type="hidden" name="seller_id" value="<?php echo $sellerrows["user_id"]; ?>">
												<input type="hidden" name="ticket_no" value="<?php echo $sellerrows["ticket_no"]; ?>">
												<input type="number" name="no_of_person" class="col-xs-12 col-sm-12" value="<?php echo $orderqty ?>" style="width:50px">
											</td>
										</tr>
									</tbody>
								<?php } ?>
									<tbody id="grid1">
											<tr style="background:#fff">
												<td colspan="5"><button type="button" id="btnReject" name="btnReject" class="btn btn-primary" style="display:none">Reject</button></td>
												<td colspan="5"><button type="button" id="btnBook" name="btnBook" class="btn btn-primary" style="float:right">Send to seller</button></td>
											</tr>
									</tbody>
							<?php } else { ?>
								<tbody id="grid1">
									<tr style="background:#fff">
										<td colspan="10"><span>No sellers found or already ordered to sellers</span></td>
									</tr>
								</tbody>
							<?php } ?>
						</table>
					</div>
					<!--sellers grid1-->
				</div> <!-- end of list of sellers row -->
		</div>
		<!--row-->

		<input type="hidden" name="existing_seller_id" id="existing_seller_id" value="<?php echo $row["seller_id"]; ?>">
		</form>



	</div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->

<?php include_once('footer.php'); ?>
<script src="<?php echo $baseurl; ?>/adminarea/script/booking.js"></script>
<script>
	$(document).ready(function() {
		$("#menu_cbookings").addClass("active");
		$("#user_id").change(function() {
			if ($("#user_id").val() == $("#existing_seller_id").val()) {
				$("#div_ticket_no").hide();
			} else {
				$("#div_ticket_no").show();
			}
		});

		let leftoutQty = parseInt('<?php echo ($pax-$order_qty);?>');
		let assignedQty = 0;
		//alert(leftoutQty);
		//Booking button click process
		$('#btnBook').click(function(e) {
			let cart2seller = [];
			let booking_id = parseInt($('input[name="booking_request_id"]').val());
			let cart = {};
			//let no_of_persons = $('input[name="no_of_person[]"]');
			$('input[name="seller_id"]').each((idx, elm) => {
				let seller_id = parseInt(($(elm).val()));
				cart = {'booking_id': booking_id};
				cart.sellerid = seller_id;
				cart2seller.push(cart);
			});

			$('input[name="no_of_person"]').each((idx, elm) => {
				let noOfPerson = parseInt('0'+$(elm).val());
				//alert(`${idx} - ${noOfPerson}`);
				assignedQty += noOfPerson;
				cart = cart2seller[idx];
				cart.no_of_person = noOfPerson;
			});

			$('input[name="ticket_no"]').each((idx, elm) => {
				//alert($(elm).val());
				let ticket_no = parseInt($(elm).val());
				cart = cart2seller[idx];
				cart.ticketno = ticket_no;
			});
			
			//alert(JSON.stringify(cart2seller));
			//alert(assignedQty);
			if(leftoutQty>=assignedQty && assignedQty>0) {
				bookTickets(cart2seller, function(data) {
					//alert(JSON.stringify(data));
					window.location.reload(true);
				});
			}
			else {
				alert('Sorry can`t order more than the customer requested quantity');
			}
		});
	});
</script>