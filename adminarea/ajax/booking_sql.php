<?php
use Dompdf\Exception;

include_once('../../config.php');
$dt = new DateTime();
if (isset($_SERVER["HTTP_FUNCTION"])) {
	$tag = strtolower($_SERVER["HTTP_FUNCTION"]);
}
if (isset($_POST["tag"])) {
	$tag = $_POST["tag"];
}

if (!empty($tag)) {
	$response = array();
	switch ($tag) {
		case "search":
			$arr = array();
			$start = $_POST['start'];
			$dt_from = $_POST['dt_from'];
			$dt_to = $_POST['dt_to'];
			$field = trim($_POST['field']);
			$value = trim($_POST['value']);
			$start = $start * 100;
			$limit = 100;
			$total = 0;
			if (empty($dt_from) && empty($dt_to)) {
				if (!empty($value)) {
					$sql = "SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
									FROM booking_tbl b
									INNER JOIN tickets_tbl t ON b.ticket_id = t.id
									INNER JOIN user_tbl u ON b.customer_id = u.id
									INNER JOIN user_tbl us ON b.seller_id = us.id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination							
								WHERE  $field='$value' AND (b.status='PENDING') AND (t.sale_type!='live') ORDER BY b.id DESC";
				} else {
					$sql = "SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
									FROM booking_tbl b
									INNER JOIN tickets_tbl t ON b.ticket_id = t.id
									INNER JOIN user_tbl u ON b.customer_id = u.id
									INNER JOIN user_tbl us ON b.seller_id = us.id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination	WHERE 1		 AND (b.status='PENDING')  AND (t.sale_type!='live')				
								ORDER BY b.id DESC";
				}
			} else {

				$dt_from = date("Y-m-d", strtotime($_POST['dt_from']));
				$dt_to = date("Y-m-d", strtotime($_POST['dt_to']));
				if (!empty($value)) {
					$sql = "SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
									FROM booking_tbl b
									INNER JOIN tickets_tbl t ON b.ticket_id = t.id
									INNER JOIN user_tbl u ON b.customer_id = u.id
									INNER JOIN user_tbl us ON b.seller_id = us.id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination							
								WHERE  $field='$value'  AND (b.status='PENDING')  AND (t.sale_type!='live') AND DATE_FORMAT(b.date,'%Y-%m-%d')>='$dt_from' AND DATE_FORMAT(b.date,'%Y-%m-%d')<='$dt_to' ORDER BY b.id DESC";
				} else {
					$sql = "SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
									FROM booking_tbl b
									INNER JOIN tickets_tbl t ON b.ticket_id = t.id
									INNER JOIN user_tbl u ON b.customer_id = u.id
									INNER JOIN user_tbl us ON b.seller_id = us.id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination								
							        WHERE 1  AND (b.status='PENDING')  AND (t.sale_type!='live') AND ( DATE_FORMAT(b.date,'%Y-%m-%d')>='$dt_from' AND DATE_FORMAT(b.date,'%Y-%m-%d')<='$dt_to' )
									ORDER BY b.id DESC";
				}
			}
			$result = mysql_query($sql);
			if (mysql_num_rows($result) > 0) {
				$ctr = ceil(mysql_num_rows($result) / $limit);
				$total = mysql_num_rows($result);
				$sql .= " LIMIT $start,$limit";
				$result = mysql_query($sql);
				$i = 0;
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					foreach ($row as $k => $v) {
						if ($k == 'date') {
							$dt = new DateTime($v);
							$dt = $dt->format('jS M Y h:i a');
							$response[$i][$k] = $dt;
						} else if ($k == 'departure_date_time') {
							$dt = new DateTime($v);
							$dt = $dt->format('jS M Y h:i a');
							$response[$i][$k] = $dt;
						} else if ($k == 'trip_type') {
							if ($v == "ONE")
								$response[$i][$k] = "ONE WAY";
							else
								$response[$i][$k] = "RETURN TRIP";
						} else
							$response[$i][$k] = $v;
					}
					$i++;
				}
				$response['records'] = $ctr;
				$response['total'] = $total;
			} else {
				$response['no_records'] = TRUE;
			}

			break;
		case "bookticket":
			//php://input is to read raw request buffer
			$postedBody = file_get_contents("php://input");
			$posteddata = json_decode($postedBody);
			$tickets = NULL;

			if ($posteddata != null && sizeof($posteddata) > 0) {
				$tickets = array_values(array_filter($posteddata, function ($ticket) {
					if (isset($ticket->no_of_person)) {
						return $ticket->no_of_person > 0;
					} else {
						return false;
					}
				}));
			}

			for ($i = 0; $i < sizeof($tickets); $i++) {
				$response[$i] = bookTicket($tickets[$i]->booking_id, $tickets[$i]->sellerid, $tickets[$i]->no_of_person, $tickets[$i]->ticketno);
			}

			//log_message('info', stripslashes($posteddata));
			break;
	}
}
echo json_encode($response);
die();
?>

<!-- Helper methods -->
<?php
function bookTicket($bookingId, $sellerId, $noOfPerson, $ticketNo)
{
	$tickets_left = $noOfPerson;
	$returnValue = true;
	$refrence_id = 0;
	$errorMsg = "Booking request $bookingId for $noOfPerson person(s) processed successfully";

	try {
		$sql_booking_details = "select usr.email, cstmr.email as supplier_email, cstmr.id as supplier_user_id, cstmr.`name` as supplier_name, 
		tkt.pnr, tkt.price, tkt.no_of_person, tkt.user_id, bk.*		
		from booking_tbl bk inner join tickets_tbl tkt on bk.ticket_id=tkt.id and tkt.no_of_person>=$noOfPerson and bk.available_qty>=$noOfPerson and tkt.approved=1
		inner join user_tbl usr on usr.id=bk.customer_id and usr.active=1
		inner join user_tbl cstmr on cstmr.id=tkt.user_id and cstmr.active=1
		where bk.id=$bookingId and bk.qty>=$noOfPerson";

		$result_booking_details = mysql_query($sql_booking_details);
		if (mysql_num_rows($result_booking_details) > 0) {
			$booking_details = mysql_fetch_array($result_booking_details);
			$supplier_email = $booking_details["supplier_email"];
			$supplier_name = $booking_details["supplier_name"];
			$supplier_user_id = $booking_details["supplier_user_id"];

			$customer_email = $booking_details["email"];
			$customer_id = $booking_details["customer_id"];
			$ticket_id = $ticketNo; //$booking_details["id"];
			$pnr = $booking_details["pnr"];

			$service_charge = $booking_details["service_charge"];
			$sgst = $booking_details["sgst"];
			$cgst = $booking_details["cgst"];
			$igst = $booking_details["igst"];
			$gst = $sgst + $cgst + $igst;

			$type = $booking_details["type"];
			$rate = $booking_details["price"];
			$amount = $noOfPerson * $rate;
			$ticket_fare = $noOfPerson * $rate;
			$total = $amount + $service_charge + $gst;
			$current_no_of_person = $booking_details["no_of_person"] - $noOfPerson;

			//update booking table and set all status as PENDING and also assign seller_id to the booking.
			//ERROR: can't assign part seller to a single booking
			$sql_update_booking = "update booking_tbl set status='PENDING',seller_status='PENDING',seller_id=$supplier_user_id, available_qty=(available_qty-$noOfPerson) where id=$bookingId";
			$updateResult = mysql_query($sql_update_booking);

			//Insert data into reference_booking_tbl where mutiple request can be placed to multiple sellers
			//This table can be used to solve previous issue
			$currentDate = date("Y-m-d h:i:s");
			$sql_new_booking = "insert into refrence_booking_tbl (date,ticket_id,pnr,ticket_fare,seller_id,customer_id,`status`,qty,rate,amount,service_charge,igst,cgst,sgst,total,type,booking_id) 
			values ('$currentDate', '$ticket_id', '$pnr', $ticket_fare, $supplier_user_id, $customer_id, 'PENDING', $noOfPerson, $rate, $amount, 0, 0, 0, 0, $total, '$type', $bookingId)";

			mysql_query($sql_new_booking) or die($sql_new_booking);
			$refrence_id = mysql_insert_id();

			//update listed customer's information with the booking ref.no
			if ($refrence_id > 0 && $noOfPerson > 0) {
				$sql_cust_update = "update customer_information_tbl set refrence_id='$refrence_id' where booking_id=$bookingId and refrence_id=0 limit $noOfPerson";
				mysql_query($sql_cust_update) or die(mysql_error());;

				//update remaining number of persons after current booking. So that remaining pax can be booked to other sellers
				$sql_update_ticket = "UPDATE tickets_tbl SET no_of_person=$current_no_of_person WHERE id=$ticketNo";
				mysql_query($sql_update_ticket) or die(mysql_error());

				sendCustomerEmail($customer_email, "Your booking approved!", getCustomerMessageBody($refrence_id), getHeaders("OXYTRA <noreply@oxytra.com>"));
			}
		}
		else {
			$sql_check_ticket_qty="SELECT * FROM tickets_tbl WHERE id=$ticketNo";
			$result_check_ticket_qty=mysql_query($sql_check_ticket_qty);
			$row_result_check_ticket_qty=mysql_fetch_array($result_check_ticket_qty);
			$returnValue = $row_result_check_ticket_qty["no_of_person"];
			$returnValue = false;
			//$msg="<div class='alert alert-danger'>This ticket have only ".$row_result_check_ticket_qty["no_of_person"]." tickets.</div>";
		}
	} catch (Exception $ex) {
		$errorMsg = $ex->getMessage();
		log_message('error', $ex->getMessage());
	}

	return array('bookingRefNo'=>$refrence_id, 'result'=>$returnValue, 'message'=> $errorMsg); // "{bookingRefNo: $refrence_id, result: $returnValue}, message: $errorMsg");
}

function sendCustomerEmail($customerEmail, $subject, $message, $headers)
{ 
	mail($customerEmail, $subject, $message, $headers);
}

function getHeaders($fromEmail)
{
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Create email headers
	$headers .= 'From: ' . $fromEmail . "\r\n" .
		'Reply-To: ' . $fromEmail . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	return $headers;
}

function getCustomerMessageBody($refrenceId)
{
	$link = "";
	$msg0 = "You have a new booking Booking No. $refrenceId";
	$msg1 = "";
	$msg2 = "";

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
																																												
	<tr> 
		<td> 
			<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
				<tbody>	
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
										<td height="25"></td>
									</tr>
									<tr> 
										<td>' . $msg1 . '</td>
									</tr>

									<tr> 
										<td height="15"></td>
									</tr>
									<tr> 
										<td><strong style="color:#333333">' . $msg2 . '</strong></td>
									</tr>

									<tr> 
										<td height="25"></td>
									</tr>
									<tr> 
										<td>Best Regards, <br><strong style="color:#333333">The <span class="il">OXYTRA</span> Team</strong></td>
									</tr>
								</tbody> 
							</table> 
						</td>
						<td width="25">
								<img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.dazzlr.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd">
						</td>			
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
	</table></body></html>';

	return $message;
}
?>
<!-- End of Helper methods -->