<?php 
include_once('header.php'); 
if(!in_array(7,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
}
if(!isset($_GET["id"]))
{
?>
<script>window.location.href="tickets.php";</script>
<?php
}
else
{
	
	$sql="SELECT `u`.`id` as `user_id`, `t`.`id`, `t`.`source`, `t`.`destination`, `t`.`source1`, `t`.`destination1`, `t`.`ticket_no`, `t`.`pnr`, `t`.`departure_date_time`, `t`.`departure_date_time1`, `t`.`arrival_date_time`, `t`.`arrival_date_time1`, `t`.`flight_no`, `t`.`flight_no1`, `t`.`terminal`, `t`.`terminal1`, `t`.`terminal2`, `t`.`terminal3`, `t`.`departure_date_time1`, `t`.`arrival_date_time1`, `t`.`flight_no1`, `t`.`total`, `t`.`sale_type`, `t`.`refundable`, `c`.`city` as `source_city`, `ct`.`city` as `destination_city`, `c1`.`city` as `source_city1`, `ct1`.`city` as `destination_city1`, `t`.`class`, `t`.`class1`, `t`.`no_of_person`, `a`.`image`, `t`.`airline`, `t`.`airline1`, `t`.`trip_type`, `t`.`price`, `t`.`baggage`, `t`.`meal`, `t`.`markup`,t.discount,t.admin_markup, `t`.`availibility`, `t`.`aircode`, `t`.`aircode1`, `t`.`no_of_stops`, `t`.`no_of_stops1`, `t`.`remarks`,t.approved FROM `tickets_tbl` `t` JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` JOIN `user_tbl` `u` ON `t`.`user_id` = `u`.`id` LEFT JOIN `city_tbl` `c1` ON `c1`.`id` = `t`.`source1` LEFT JOIN `city_tbl` `ct1` ON `ct1`.`id` = `t`.`destination1` WHERE `t`.`id` = '".$_GET["id"]."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);	
	
	if($row["trip_type"]=="ONE")
		$trip_type="ONE WAY";
	else
		$trip_type="ROUND TRIP";
	
	$sql_airline="SELECT * FROM airline_tbl";
	$result_airline=mysql_query($sql_airline);
	while($row_airline=mysql_fetch_array($result_airline))
	{
		$arr1[$row_airline['id']]=$row_airline['airline'];
	}

}
$msg="";
if(isset($_POST["submit"]))
{  
        $no_of_person=$_POST["no_of_person"];
		$booking_request_id=$_POST["booking_request_id"];
		
		
		$sql_check_ticket_qty="SELECT * FROM tickets_tbl WHERE id=".$_GET["id"]." AND no_of_person>=$no_of_person";
		$result_check_ticket_qty=mysql_query($sql_check_ticket_qty);
		if(mysql_num_rows($result_check_ticket_qty)>0)
		{
			$sql_check="SELECT b.*,u.email FROM booking_tbl b INNER JOIN user_tbl u ON u.id=b.customer_id WHERE b.id='".$booking_request_id."' AND b.available_qty>=$no_of_person";
			$result_check=mysql_query($sql_check);
			if(mysql_num_rows($result_check)>0)
			{	
				$row_check=mysql_fetch_array($result_check);	
				$sql_ticket="SELECT t.*,u.email,u.user_id as supplier_user_id,u.name FROM tickets_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.id='".$_GET["id"]."'";
				$result_ticket=mysql_query($sql_ticket);
				$row_ticket=mysql_fetch_array($result_ticket);				
				$supplier_email=$row_ticket["email"];	
				$supplier_name=$row_ticket["name"];	
				$supplier_user_id=$row_ticket["supplier_user_id"];	
				
				$customer_email=$row_check["email"];
				$ticket_id=$row_ticket["id"];
				$pnr=$row_ticket["pnr"];
				
				
				$service_charge=$row_check["service_charge"];
				$sgst=$row_check["sgst"];
				$cgst=$row_check["cgst"];
				$igst=$row_check["igst"];
				$gst=$sgst+$cgst+$igst;
				
				$rate=$row_ticket["total"];
				$amount=$no_of_person*$rate;
				$ticket_fare=$no_of_person*$rate;
				$total=$amount+$service_charge+$gst;
				$current_no_of_person=$row_ticket["no_of_person"]-$no_of_person;
				
				$sql_update_booking="UPDATE booking_tbl SET status='PENDING',seller_status='PENDING',seller_id='".$row_ticket["user_id"]."' 
				WHERE id='".$booking_request_id."'";
				
				mysql_query($sql_update_booking);			
				$sql_new_booking="INSERT INTO refrence_booking_tbl 
					(date,
					
					ticket_id,					
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
					(
					'".date("Y-m-d h:i:s")."',
					'".$ticket_id."',							
					'$pnr',
					'".$ticket_fare."',	
					'".	$row_ticket["user_id"]."',
					'".$row_check["customer_id"]."',
					'PENDING',
					'$no_of_person',
					'".$rate."',
					'".$amount."',
					'0',
					'0',
					'0',
					'0','
					$total',
					'".$row_check["type"]."',
					'".$booking_request_id."'				
					)";							
					 mysql_query($sql_new_booking) or die($sql_new_booking);
					$refrence_id=mysql_insert_id();
					
					$sql_customer_information="SELECT * FROM  customer_information_tbl WHERE booking_id='".$booking_request_id."' AND refrence_id=0 ORDER BY id ASC";
					$result_customer_information=mysql_query($sql_customer_information);
					$ctr=1;
					while($row_customer_information=mysql_fetch_array($result_customer_information))
					{
						if($ctr<=$no_of_person)
						{
							 $sql_update="UPDATE customer_information_tbl SET refrence_id='$refrence_id' WHERE id='".$row_customer_information["id"]."'";
							 mysql_query($sql_update) or die(mysql_error());;
						}
						$ctr++;
					}
					
					$sql_update_ticket="UPDATE tickets_tbl SET no_of_person='$current_no_of_person' WHERE id='".$booking_request_id."'";
					mysql_query($sql_update_ticket) or die(mysql_error());
					
					$sql_booking_ticket="UPDATE booking_tbl SET available_qty=(available_qty-$no_of_person) WHERE id='".$booking_request_id."'";
					mysql_query($sql_booking_ticket) or die(mysql_error());
					
					 $link="";
					 $msg0="Your Booking Booking No. ".$booking_request_id." is  Approved ";
					 $msg1="";
					 $msg2="";
					 $subject="Booking Approve";
					 $emailaddress="OXYTRA <noreply@oxytra.com>";
					 $headers  = 'MIME-Version: 1.0' . "\r\n";
					 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					 // Create email headers
					 $headers .= 'From: '.$emailaddress."\r\n".
					 'Reply-To: '.$emailaddress."\r\n" .
					 'X-Mailer: PHP/' . phpversion();
						
						
					 $message='<html><body><table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;max-width:500px;color:#444444;text-align:left;background:#ffffff;border:1px solid #efefef"> 
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
							<td valign="middle" style="font-size:18px;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;text-align:left"> '.$msg0.' </td>
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
					<td>'.$msg1.'</td></tr>

					<tr> <td height="15"></td></tr><tr> <td><strong style="color:#333333">'.$msg2.'</strong></td></tr>

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
					 mail($customer_email,$subject,$message,$headers);
					
					
					
					 $link="";
					 $msg0="You have a new booking Booking No. $refrence_id ";
					 $msg1="";
					 $msg2="";
					 $subject="Booking Approve";
					 $emailaddress="OXYTRA <noreply@oxytra.com>";
					 $headers  = 'MIME-Version: 1.0' . "\r\n";
					 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					 // Create email headers
					 $headers .= 'From: '.$emailaddress."\r\n".
					 'Reply-To: '.$emailaddress."\r\n" .
					 'X-Mailer: PHP/' . phpversion();
						
						
					 $message='<html><body><table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;max-width:500px;color:#444444;text-align:left;background:#ffffff;border:1px solid #efefef"> 
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
							<td valign="middle" style="font-size:18px;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;text-align:left"> '.$msg0.' </td>
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
					<td>'.$msg1.'</td></tr>

					<tr> <td height="15"></td></tr><tr> <td><strong style="color:#333333">'.$msg2.'</strong></td></tr>

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
					mail($supplier_email,$subject,$message,$headers);
					$msg="<div class='alert alert-success'>Booking Send to  ".$supplier_name." ( ".$supplier_user_id." ) Successfully</div>";
			}
			else
			{
				$sql_check="SELECT * FROM booking_tbl WHERE id='".$booking_request_id."'";
				$result_check=mysql_query($sql_check);
				$row_check=mysql_fetch_array($result_check);
				$msg="<div class='alert alert-danger'>This Booking Request have only ".$row_check["available_qty"]." tickets to book.</div>";
			}
		}
		else
		{
			$sql_check_ticket_qty="SELECT * FROM tickets_tbl WHERE id='".$_GET["id"]."'";
			$result_check_ticket_qty=mysql_query($sql_check_ticket_qty);
			$row_result_check_ticket_qty=mysql_fetch_array($result_check_ticket_qty);
			$msg="<div class='alert alert-danger'>This ticket have only ".$row_result_check_ticket_qty["no_of_person"]." tickets.</div>";
		}
}	

?>
<title>Book Ticket | <?php echo $row_top['site_title']; ?></title>
<style>
.ui-helper-hidden-accessible
{
	width:100%;
	float:left;
}
</style>
</head>
            <?php 
			include_once('leftbar.php');			
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header"><!--.page-header -->
							<h1>
									<?php echo $row["source_city"];?> TO <?php echo $row["destination_city"]." ( ".$trip_type." )";?><br>
                                								
							</h1>
							<?php if(!empty($msg))echo $msg; ?>	
                            						
						</div><!-- /.page-header -->
						<form  id="frm_customer" enctype="multipart/form-data" action="" method="POST">	
						
						
						<div class="row">
							<div class="col-xs-12 col-sm-12"><!--Widget col-md-8 start-->
							    <div style="" >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
										<thead>
											<tr>                                                
												<th colspan="3">GOING DETAILS	| <?php echo $arr1[$row["airline"]]." - ".$row["flight_no"]." - ".$row["class"]." CLASS";?></th>																																																									
											</tr>
										</thead>
										<tbody id="grid">											                                            
                                              <tr style="background:#EFF3F8">
												<td colspan="3">Departure Details</td>																																														
											  </tr>	
											  
                                              <tr style="background:#fff">
												<td><b>Airport : </b><?php echo $row["source_city"];?></td> 
												<td><b>Terminal : </b><?php echo $row["terminal"];?></td>
                                                <td><b>Departure Time : </b><?php echo date("jS M Y h:i a",strtotime($row["departure_date_time"])); ?></td>                                                 												
											  </tr>                                             
											  
											  <tr style="background:#EFF3F8">
												<td colspan="3">Arrival Details</td>																																														
											  </tr>	
											  
                                              <tr style="background:#fff">
												<td><b>Airport : </b><?php echo $row["destination_city"];?></td> 
												<td><b>Terminal : </b><?php echo $row["terminal1"];?></td>
                                                <td><b>Arrival Time : </b><?php echo date("jS M Y h:i a",strtotime($row["arrival_date_time"])); ?></td>                                                 												
											  </tr>                                                                                         
										</tbody>
									</table>
									<?php if($row["trip_type"]=="ROUND"){?>
									<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
										<thead>
											<tr>                                                
												<th colspan="3">RETRUNING DETAILS | <?php echo $arr1[$row["airline1"]]." - ".$row["flight_no1"]." - ".$row["class1"]." CLASS";?></th>																																																									
											</tr>
										</thead>
										<tbody id="grid">											                                            
                                              <tr style="background:#EFF3F8">
												<td colspan="3">Departure Details</td>																																														
											  </tr>	
											  
                                              <tr style="background:#fff">
												<td><b>Airport : </b><?php echo $row["destination_city"];?></td> 
												<td><b>Terminal : </b><?php echo $row["terminal2"];?></td>
                                                <td><b>Departure Time : </b><?php echo date("jS M Y h:i a",strtotime($row["departure_date_time1"])); ?></td>                                                 												
											  </tr>                                             
											  
											  <tr style="background:#EFF3F8">
												<td colspan="3">Arrival Details</td>																																														
											  </tr>	
											  
                                              <tr style="background:#fff">
												<td><b>Airport : </b><?php echo $row["source_city"];?></td> 
												<td><b>Terminal : </b><?php echo $row["terminal3"];?></td>
                                                <td><b>Arrival Time : </b><?php echo date("jS M Y h:i a",strtotime($row["arrival_date_time1"])); ?></td>                                                 												
											  </tr>                                                                                         
										</tbody>
									</table>
									<?php } ?>
									
									<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
										<thead>
											<tr>                                                
												<th colspan="6"><b>TICKET FARE AND OTHER DETAILS</b></th>																																																									
											</tr>
										</thead>
										<tbody id="grid">											                                            
                                              
											  
                                              <tr style="background:#fff">
											    <td><b>No. of Availability : </b><?php echo $row["availibility"];?></td>
												<td><b>Seats : </b><?php echo $row["no_of_person"];?></td> 
												<td><b>Price : </b><?php echo $row["price"];?></td>
												<td><b>Discount : </b><?php echo $row["discount"];?></td> 
                                                <td><b>Supplier Markup : </b><?php echo $row["markup"];?></td>                                                 												
												<td>
												<input type="hidden" name="hid_price" id="hid_price" value="<?php echo $row["price"];?>">
												<input type="hidden" name="hid_discount" id="hid_discount" value="<?php echo $row["discount"];?>">
												<input type="hidden" name="hid_markup" id="hid_markup" value="<?php echo $row["markup"];?>">
												<input type="hidden" name="total" id="total" value="<?php echo floatval($row["total"]+$row["admin_markup"]);?>">
												<b>Admin Markup : </b><?php echo $row["admin_markup"];?>
												</td>
											  </tr>                                             
											  
											  <tr style="background:#fff">
												<td colspan="6"><b>Total : </b><span id="spn_total"><?php echo floatval($row["total"]+$row["admin_markup"]);?></span></td>																																														
											  </tr>	
											  
                                                                                                                                       
										</tbody>
									</table>
                               </div>     								
							</div><!--Widget col-md-8 end-->

                           

                            <div class="col-xs-12 col-sm-8"><!--Widget col-md-6 start-->
							    <div class="widget-box" style="float:left;width:100%"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Book</small>
												</h4>

									</div>

									<div class="widget-body"><!--Widget Body start-->
									
											<div class="form-group has-info col-xs-6 col-sm-6">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Booking Request No.</label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" name="booking_request_id" class="col-xs-12 col-sm-12"   required>
													</div>
											</div>																                                            
											<div class="form-group has-info col-xs-6 col-sm-6">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> No. of Person</label>
													<div class="col-xs-12 col-sm-12">
														<input type="number" name="no_of_person" class="col-xs-12 col-sm-12"  required>
													</div>
											</div>
                                            <!--<div class="form-group has-info col-xs-6 col-sm-6">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Select Customer</label>
													<div class="col-xs-12 col-sm-12">
														<select id="user_id"  name="user_id" class="col-xs-12 col-sm-12" required>														    
                                                           <option value="">Select Customer</option>
														   <?php
														   $sql="SELECT * FROM user_tbl WHERE active=1 ORDER BY name ASC";														   
														   $result=mysql_query($sql);
														   while($row=mysql_fetch_array($result))
														   {
														   ?>
														     <option value="<?php echo $row["id"];?>"><?php echo $row["name"];?> ( <?php echo $row["user_id"];?> )</option>
														   <?php
														   }
														   ?>
                                                        </select>
													</div>
											</div>-->
                                            
                                           																						                                                                                             																																								
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-6 end-->	
                                                                                                                                                                                 																																								
                        </div>	<!--row-->	
					    <div class="row">
						   <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
							<button type="submit" class="pull-left btn btn-sm btn-primary" name="submit">															
								<span class="bigger-110">Book</span>
							</button>																		
							 </div>
						</div> 
					    </form>
                               
                       
                             
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

 <?php include_once('footer.php');?>
 <script>
    $(document).ready(function ()
	{   
		$("#menu_book_tickets").addClass("active");			
		var price="";
		var total="";
		var markup="";
		var admin_markup="";
		$("#admin_markup").keyup(function()
		{
			if($("#admin_markup").val()=="")
				admin_markup=0;
			else
			admin_markup=$("#admin_markup").val();
			
			total=parseFloat($("#hid_price").val())-parseFloat($("#hid_discount").val())+parseFloat(admin_markup)+parseFloat($("#hid_markup").val());
			$("#spn_total").html(total);
			("#total").html(total);
		});
	});	
 </script>