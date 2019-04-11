<?php 
include_once('header.php'); 

$msg="";
if(isset($_POST["update"]))
{ 
    $now=date("Y-m-d H:i:s"); 
	$departure_date=$_POST["departure_date"];
	$diff=strtotime($now)-strtotime($departure_date);
	$diff=intval($dateDiff/60);
	
	$pnr=$_POST["pnr"];
	$qty=$_POST["qty"];
		
    if($diff>4)
	{
		$msg="<div class='alert alert-danger'>Booking Or Cancellation is only Possible before 4 or more than 4 hours of Departure Date</div>";
	}
	
    else
	{		
	    if($_POST["approved"]=="CONFIRM")
		{
			$sql_check="SELECT * FROM refrence_booking_tbl WHERE  id='".$_POST["booking_id"]."' AND status='CONFIRM'";
			$result_check=mysql_query($sql_check);
			if(mysql_num_rows($result_check)==0)
			{						
				$service_charge=$row_top["service_charge"];
				$sgst=$row_top["sgst"];
				$cgst=$row_top["cgst"];
				$igst=$row_top["igst"];
				$gst=$sgst+$cgst+$igst;
				$row_check=mysql_fetch_array($result_check);
				
				/*foreach($_POST["confirm_pnr"] as $key=>$value)
				{
					$sql_update="UPDATE customer_information_tbl  SET pnr='$value' WHERE id='".$_POST["confirm_pnr"][$key]."'";
					mysql_query($sql_update);
				}*/
				
									
				  
					
					
					
					$sql_get="SELECT r.*,t.total as ticket_total_for_seller,t.sale_type FROM refrence_booking_tbl r INNER JOIN tickets_tbl t ON r.ticket_id=t.id WHERE  r.id='".$_POST["booking_id"]."'";
					$result_get=mysql_query($sql_get);
					
					$row_get=mysql_fetch_array($result_get);
					$ticket_id=$row_get["ticket_id"];
					$no_of_person=$row_get["qty"];
					$amount=$row_get["ticket_total_for_seller"]*$no_of_person;
					$seller_id=$row_get["seller_id"];
					$booking_id=$row_get["booking_id"];
					$sale_type=$row_get["sale_type"];
					
					$sql_ticket="SELECT t.*,u.email FROM tickets_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.id='".$ticket_id."'";
					$result_ticket=mysql_query($sql_ticket);
					$row_ticket=mysql_fetch_array($result_ticket);				
					$email=$row_ticket["email"];	
					
					
                    if($sale_type!="live")
					{						
						$sql_update_ticket="UPDATE tickets_tbl SET no_of_person=(no_of_person-$no_of_person) WHERE id='".$ticket_id."'";
						mysql_query($sql_update_ticket);
					}	
					
					$sql_current_ticket="SELECT * FROM tickets_tbl WHERE id='".$row_pnr["id"]."'";
					$result_current_ticket=mysql_query($sql_current_ticket);										
					$row_current_ticket=mysql_fetch_array($result_current_ticket);
														
					
					$sql_update_booking="UPDATE refrence_booking_tbl SET status='CONFIRM',booking_confirm_date='".date("Y-m-d h:i:s")."' WHERE id='".$_POST["booking_id"]."'";
					mysql_query($sql_update_booking) or die($sql_update_booking);
					
					$sql_update_booking="UPDATE booking_tbl SET status='CONFIRM' WHERE id='".$booking_id."'";
					mysql_query($sql_update_booking);
																				
					$sql_credit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$seller_id' ,'$amount','','".$_POST["booking_id"]."' ,'CR')";
					mysql_query($sql_credit); 
					
				   
					
					$msg="<div class='alert alert-success'>Approved Successfully</div>";																																			 								
				
				
				   
					 $link="";
					 $msg0="Your Booking is  Approved ";
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
					mail($email,$subject,$message,$headers);
			}
			else
			{
				$msg="<div class='alert alert-danger'>This Booking already Confirmed</div>";
			}
		}
		
		
		if($_POST["approved"]=="CANCELLED")
		{
			$admin_cancel_charge=isset($_POST["admin_cancel_charge"])?$_POST["admin_cancel_charge"]:"0";
			$supplier_cancel_charge=isset($_POST["supplier_cancel_charge"])?$_POST["supplier_cancel_charge"]:"0";
			$cancel_charge=$admin_cancel_charge+$supplier_cancel_charge;
			
			$sql_check="SELECT * FROM refrence_booking_tbl WHERE  id='".$_POST["booking_id"]."' AND status='CANCELLED'";
			$result_check=mysql_query($sql_check);
			if(mysql_num_rows($result_check)==0)
			{
				        $sql_check="SELECT * FROM refrence_booking_tbl WHERE  id='".$_POST["booking_id"]."'";
			            $result_check=mysql_query($sql_check);
		                $row_check=mysql_fetch_array($result_check);	
						$original_booking_id=$row_check["booking_id"];
					    
						$sql_get="SELECT b.* FROM booking_tbl  b INNER JOIN refrence_booking_tbl r ON  b.id=r.booking_id WHERE r.id='".$_POST["booking_id"]."'";
						$result_get=mysql_query($sql_get);
						$row_get=mysql_fetch_array($result_get);					
						
						$seller_id=$row_get["seller_id"];
						$end_customer_id=$row_get["customer_id"];					
						$booking_id=$row_get["id"];
						$total=$row_get["total"];
						$refund=$total-$cancel_charge;
						
						
						
						$sql_customer_credit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$end_customer_id','".$refund."','REFUND','".$booking_id."','CR')";
						mysql_query($sql_customer_credit);
						
						/*$sql_customer_debit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$end_customer_id','".(0-$admin_cancel_charge)."','ADMIN CANCELLATION CHARGE','".$booking_id."','DR')";
						mysql_query($sql_customer_debit);*/
									
						$sql_update="UPDATE booking_tbl SET status='".$_POST["approved"]."',admin_cancel_charge='".$_POST["admin_cancel_charge"]."',supplier_cancel_charge='".$_POST["supplier_cancel_charge"]."',refund_amount='".$refund."',cancel_date='".date("Y-m-d h:i:s")."' WHERE id='".$original_booking_id."'";
						if(mysql_query($sql_update))
						{
							$sql_get="SELECT r.seller_id,r.ticket_id,r.ticket_fare,r.qty,t.price,t.markup 
							FROM refrence_booking_tbl r 
							INNER JOIN tickets_tbl t ON r.ticket_id=t.id
							WHERE r.id='".$_POST["booking_id"]."'";
							$result_get=mysql_query($sql_get);
							$row_get=mysql_fetch_array($result_get);					
							
							$seller_id=$row_get["seller_id"];
							$ticket_id=$row_get["ticket_id"];
							
							$supplier_rate=$row_get["price"]+$row_get["markup"];
							$cancelled_amount=$supplier_rate-$supplier_cancel_charge;
							$qty=$row_get["qty"];
						
							$sql_update_refrence="UPDATE refrence_booking_tbl set status='CANCELLED' WHERE id='".$_POST["booking_id"]."'";
							mysql_query($sql_update_refrence);
															
							$sql_update="UPDATE tickets_tbl SET  no_of_person=(no_of_person+$qty) WHERE id='".$ticket_id."'";
							if(mysql_query($sql_update))
							{                       				
								$sql_debit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$seller_id','".(0-$cancelled_amount)."','BOOKING CANCEL','".$_POST["booking_id"]."','DR')";
								if(mysql_query($sql_debit))
								{	                           									
									/*$sql_credit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$seller_id','".$supplier_cancel_charge."','SUPPPLIER CANCELLATION CHARGE','".$_POST["booking_id"]."','CR')";
									if(mysql_query($sql_credit))
										$msg="<div class='alert alert-success'>Cancelled Successfully</div>";
									else
										echo " SQL CREDIT ERROR : ".mysql_error();*/
									
									$msg="<div class='alert alert-success'>Cancelled Successfully</div>";
								}
								else
								{
									echo " SQL DEBIT ERROR : ".mysql_error();
								}										
							}
							else
							{
								echo " UPDATE TICKET QUERY ERROR : ".$sql_update;
							}
						}
			}		
			else
			{
				$msg="<div class='alert alert-danger'>This Booking already Cancelled</div>";
			}
			
			
		}
		
		if($_POST["approved"]=="REJECTED")
		{
			
			
			$sql_check="SELECT * FROM refrence_booking_tbl WHERE  id='".$_POST["booking_id"]."' AND status='REJECTED'";
			$result_check=mysql_query($sql_check);
			if(mysql_num_rows($result_check)==0)
			{
				        $sql_check="SELECT * FROM refrence_booking_tbl WHERE  id='".$_POST["booking_id"]."'";
			            $result_check=mysql_query($sql_check);
		                $row_check=mysql_fetch_array($result_check);	
						$original_booking_id=$row_check["booking_id"];
					    
						$sql_get="SELECT b.* FROM booking_tbl  b INNER JOIN refrence_booking_tbl r ON  b.id=r.booking_id WHERE r.id='".$_POST["booking_id"]."'";
						$result_get=mysql_query($sql_get);
						$row_get=mysql_fetch_array($result_get);					
						
						$seller_id=$row_get["seller_id"];
						$end_customer_id=$row_get["customer_id"];					
						$booking_id=$row_get["id"];
						$total=$row_get["total"];
						$refund=$total;
						
						
						
						
						
						$sql_update="UPDATE booking_tbl SET status='".$_POST["approved"]."' ,cancel_date='".date("Y-m-d h:i:s")."' WHERE id='".$original_booking_id."'";
						if(mysql_query($sql_update))
						{
							$sql_get="SELECT r.seller_id,r.ticket_id,r.ticket_fare,r.qty,t.price,t.markup,t.sale_type 
							FROM refrence_booking_tbl r 
							INNER JOIN tickets_tbl t ON r.ticket_id=t.id
							WHERE r.id='".$_POST["booking_id"]."'";
							$result_get=mysql_query($sql_get);
							$row_get=mysql_fetch_array($result_get);					
							
							$seller_id=$row_get["seller_id"];
							$ticket_id=$row_get["ticket_id"];
							$sale_type=$row_get["sale_type"];
							
							$supplier_rate=$row_get["price"]+$row_get["markup"];
							$cancelled_amount=$supplier_rate;
							$qty=$row_get["qty"];
						
							$sql_update_refrence="UPDATE refrence_booking_tbl set status='REJECTED' WHERE id='".$_POST["booking_id"]."'";
							mysql_query($sql_update_refrence);
															
							$sql_update="UPDATE tickets_tbl SET  no_of_person=(no_of_person+$qty) WHERE id='".$ticket_id."'";
							if(mysql_query($sql_update))
							{ 
                                if($sale_type=="live")
								{									
									$sql_customer_credit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$end_customer_id','".$refund."','REJECTED','".$booking_id."','CR')";						        
									if(mysql_query($sql_customer_credit))
									{	                           									
										
										
									}
									else
									{
										echo " SQL DEBIT ERROR : ".mysql_error();
									}	
													
									$sql_debit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$seller_id','".(0-$cancelled_amount)."','REJECTED','".$_POST["booking_id"]."','DR')";
									if(mysql_query($sql_debit))
									{	                           									
										
										$msg="<div class='alert alert-success'>Rejected Successfully</div>";
										
										
									}
									else
									{
										echo " SQL DEBIT ERROR : ".mysql_error();
									}
								}								
							}
							else
							{
								echo " UPDATE TICKET QUERY ERROR : ".$sql_update;
							}
						}
					$sql_get="SELECT r.*,t.total as ticket_total_for_seller,t.sale_type FROM refrence_booking_tbl r INNER JOIN tickets_tbl t ON r.ticket_id=t.id WHERE  r.id='".$_POST["booking_id"]."'";
					$result_get=mysql_query($sql_get);
					
					$row_get=mysql_fetch_array($result_get);
					$ticket_id=$row_get["ticket_id"];
					
					$sql_ticket="SELECT t.*,u.email FROM tickets_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.id='".$ticket_id."'";
					$result_ticket=mysql_query($sql_ticket);
					$row_ticket=mysql_fetch_array($result_ticket);				
					$email=$row_ticket["email"];	
					 $link="";
					 $msg0="Your Booking is  Rejected ";
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
					mail($email,$subject,$message,$headers);
						
			}		
			else
			{
				$msg="<div class='alert alert-danger'>This Booking already Rejected</div>";
			}
			
			
		}
	}
	
}
if(!isset($_GET["id"]))
{
?>
<script>window.location.href="bookings.php";</script>
<?php
}
else
{
	
	 /*$sql="SELECT `cus`.`prefix`, `cus`.`age`, `cus`.`first_name`, `cus`.`last_name`, `cus`.`email`, `cus`.`mobile_no`, 
	`u`.`name`, `u`.`email`, `u`.`mobile`, `b`.`id`,b.booking_id, `t`.`ticket_no`,t.price,t.discount,t.markup,t.admin_markup, `b`.`pnr`, 
	`b`.`date`, `c`.`city` as `source`, `c1`.`city` as `source1`, `ct`.`city` as `destination`, `ct1`.`city` as `destination1`, 
	`a`.`airline`, `a1`.`airline` as `airline1`, `t`.`class`, `t`.`class1`, `t`.`departure_date_time`, `t`.`departure_date_time1`, 
	`t`.`arrival_date_time`, `t`.`arrival_date_time1`, `t`.`trip_type`, `t`.`terminal`, `t`.`terminal1`, `t`.`terminal2`, `t`.`terminal3`, 
	`t`.`flight_no`, `t`.`flight_no1`, `b`.`service_charge`, `b`.`sgst`, `b`.`cgst`, `b`.`igst`, `b`.`rate`, `b`.`qty`, `b`.`amount`, `b`.`total`, 
	`b`.`type`, `b`.`status`,b.supplier_cancel_charge,b.cancel_request_date,b.admin_cancel_charge,b.cancel_date,b.refund_amount,b.customer_id,b.seller_id,
	 b.admin_cancel_charge 
	 
	 FROM `tickets_tbl` as `t` JOIN `refrence_booking_tbl` `b` ON `b`.`ticket_id` = `t`.`id` 
	 
	 JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` LEFT JOIN `airline_tbl` `a1` ON `a1`.`id` = `t`.`airline1` 
	 JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
	 LEFT JOIN `city_tbl` `c1` ON `c1`.`id` = `t`.`source1` LEFT JOIN `city_tbl` `ct1` ON `ct1`.`id` = `t`.`destination1` 
	 JOIN `user_tbl` `u` ON `b`.`customer_id` =`u`.`id` LEFT JOIN `customer_information_tbl` `cus` ON `b`.`id` =`cus`.`booking_id` 
	 WHERE `b`.`id` ='".$_GET["id"]."'";*/
	 
	 $sql="SELECT `cus`.`prefix`, `cus`.`age`, `cus`.`first_name`, `cus`.`last_name`, `cus`.`email`, `cus`.`mobile_no`, 
	`u`.`name`, `u`.`email`, `u`.`mobile`, `b`.`id`,b.booking_id, `t`.`ticket_no`,t.price,t.discount,t.markup,t.admin_markup, `b`.`pnr`, 
	`b`.`date`, `c`.`city` as `source`, `c1`.`city` as `source1`, `ct`.`city` as `destination`, `ct1`.`city` as `destination1`, 
	`a`.`airline`, `a1`.`airline` as `airline1`, `t`.`class`, `t`.`class1`, `t`.`departure_date_time`, `t`.`departure_date_time1`, 
	`t`.`arrival_date_time`, `t`.`arrival_date_time1`, `t`.`trip_type`, `t`.`terminal`, `t`.`terminal1`, `t`.`terminal2`, `t`.`terminal3`, 
	`t`.`flight_no`, `t`.`flight_no1`, `b`.`service_charge`, `b`.`sgst`, `b`.`cgst`, `b`.`igst`, `b`.`rate`, `b`.`qty`, `b`.`amount`, `b`.`total`, 
	`b`.`type`, `b`.`status`,bt.supplier_cancel_charge,b.cancel_request_date,bt.admin_cancel_charge,bt.cancel_date,bt.refund_amount,b.customer_id,b.seller_id
	
	 
	 FROM `tickets_tbl` as `t` JOIN `refrence_booking_tbl` `b` ON `b`.`ticket_id` = `t`.`id` 
	 
	 JOIN `booking_tbl` `bt` ON `bt`.`id` = `b`.`booking_id` 
	 JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` 
	 LEFT JOIN `airline_tbl` `a1` ON `a1`.`id` = `t`.`airline1` 
	 JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` 
	 JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
	 LEFT JOIN `city_tbl` `c1` ON `c1`.`id` = `t`.`source1` 
	 LEFT JOIN `city_tbl` `ct1` ON `ct1`.`id` = `t`.`destination1` 
	 JOIN `user_tbl` `u` ON `b`.`customer_id` =`u`.`id` 
	 LEFT JOIN `customer_information_tbl` `cus` ON `b`.`id` =`cus`.`booking_id` 
	 WHERE `b`.`id` ='".$_GET["id"]."'";
	
	
    $result=mysql_query($sql);
	if($result)
	{
		$row=mysql_fetch_array($result);	
		if($row["trip_type"]=="ONE")
			$trip_type="ONE WAY";
		else
			$trip_type="ROUND TRIP";
	}
	else
	{
		echo "".mysql_error();
	}
	
	
}
?>
<title>Ticket Details | <?php echo $row_top['site_title']; ?></title>
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
									<?php echo $row["source"];?> TO <?php echo $row["destination"]." ( ".$trip_type." )";?><br>
									<?php 
									if($row["pnr"]=="")
									{
										echo "PENDING";
									}
									else
									{
										echo "PNR : #".$row["pnr"]."</b>";
									}?>
									
									
                                				
							</h1>
                            
                         <?php if(!empty($msg))echo $msg; ?>							
						</div><!-- /.page-header -->
						<form  id="frm_customer" enctype="multipart/form-data" action="" method="POST">	
						<input type="hidden" name="booking_id" value="<?php echo $_GET["id"];?>">
						
						<div class="row">
							<div class="col-xs-12 col-sm-12"><!--Widget col-md-8 start-->
							    <div style="" >
								   
								   <h3 style="color:#2679b5;font-size:18px">PASSENGER DETAILS</h3>								   
								   <hr style="margin:0"/ >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
										<thead>
											<tr>                                                
												<th>Si No.</th>	
												<th>Passenger Name</th>
												<th>Age</th>												
												<th>Mobile No.</th>
												
												<th>Email</th>
												<th>PNR</th>
												
												
												
											</tr>
										</thead>
										<tbody id="grid">											                                            
                                              <?php
											      $dateDiff = intval((strtotime($row["arrival_date_time"])-strtotime($row["departure_date_time"]))/60);
											      $ctr=1;
												  $all_supplier_cancel=0;												 
												  $sql_details="SELECT * FROM customer_information_tbl  WHERE refrence_id='".$_GET["id"]."' ORDER BY id ASC";												 
												  $result_details=mysql_query($sql_details);
												  while($row_details=mysql_fetch_array($result_details))
												  {
                                               ?>	
													<tr>                                                
														<td><?php echo $ctr; ?></td>	
														<td><?php echo $row_details["prefix"]." ".$row_details["first_name"]." ".$row_details["last_name"];?></td>
														<td><?php echo $row_details["age"];?></td>													
														<td><?php echo $row_details["mobile_no"];?></td>
														<td><?php echo $row_details["email"];?></td>																																											
														<td>
														<input type="hidden" name="customer_information_id[]" value="<?php echo $row_details["id"];?>">
														<input type="text" name="confirm_pnr[]" value="<?php echo $row_details["pnr"];?>">
														</td>
													</tr>
											   <?php
											       $ctr++;
												  
												  }
												  $ctr--;
												  echo "<input type='hidden' value=".$ctr." name='qty'>";
											   ?>
											   
										
										<?php 
											if($row["status"]=="PROCESSING FOR CANCEL" || $row["status"]=="CANCELLED")
											{
											?>
												<tr>
												   <th colspan="5">Admin Cancellation Charge</th>
												   <th><input type="text" name="admin_cancel_charge" class="form-control" value="<?php echo $row["admin_cancel_charge"]; ?>"></th>
												</tr>
												<tr>
												   <th colspan="5">Supplier Cancellation Charge</th>
												   <th><input type="text" name="supplier_cancel_charge" class="form-control" value="<?php echo $row["supplier_cancel_charge"]; ?>"></th>
												</tr>
										  <?php
											}
										?>
										</tbody>
										
									</table>
									<h3 style="color:#2679b5;font-size:18px">GOING FLIGHT DETAILS</h3>								   
								    <hr style="margin:0"/ >
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
										<tbody id="grid">	
										         <input type="hidden" name="departure_date" value="<?php echo $row["departure_date_time"]; ?>">
                                                 <tr>										
                                                 <td><?php echo date("jS M y",strtotime($row["departure_date_time"]))." ( ".date("h:i a",strtotime($row["departure_date_time"]))." )";?></td>	                                                                  
                                                 <td><?php echo $row["source"];?></td>	                                                                  
                                                 <td><?php echo $row["destination"];?></td>	 
												 <td><?php echo intval($dateDiff/60)." Hours ".($dateDiff%60)." Minutes"; ?></th>
                                                 <td><?php echo $row["airline"];?></td>	                                                                  
                                                 <td><?php echo $row["class"];?></td>	                                                                  
                                                 <td><?php echo $row["flight_no"];?></td>	                                                                  
                                                 <td><?php echo $row["terminal"];?></td>	                                                                  
												 <td><?php echo $row["terminal1"];?></td>
												 </tr>
										</tbody>
									</table>
									<?php if($row["trip_type"]=="ROUND")
									{
										
										$dateDiff1 = intval((strtotime($row["arrival_date_time1"])-strtotime($row["departure_date_time1"]))/60);
										?>
									
									<h3 style="color:#2679b5;font-size:18px">RETURNING FLIGHT DETAILS</h3>								   
								    <hr style="margin:0"/ >
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
										<tbody id="grid">	
                                                 <tr>										
                                                 <td><?php echo date("jS M y",strtotime($row["departure_date_time1"]))." ( ".date("h:i a",strtotime($row["departure_date_time1"]))." )";?></td>	                                                                  
                                                 <td><?php echo $row["source1"];?></td>	                                                                  
                                                 <td><?php echo $row["destination1"];?></td>	
												 <td><?php echo intval($dateDiff1/60)." Hours ".($dateDiff1%60)." Minutes"; ?></th>
                                                 <td><?php echo $row["airline1"];?></td>	                                                                  
                                                 <td><?php echo $row["class1"];?></td>	                                                                  
                                                 <td><?php echo $row["flight_no1"];?></td>	                                                                  
                                                 <td><?php echo $row["terminal2"];?></td>	                                                                  
												 <td><?php echo $row["terminal3"];?></td>
												 </tr>
										</tbody>
									</table>
									<?php } ?>
									<h3 style="color:#2679b5;font-size:18px">BOOKING DETAILS</h3>								   
								    <hr style="margin:0"/ >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
										<thead>
											<tr> 
											    <th>Booking No.</th>	
												<th>Request No.</th>																																																									
												<th>Booking Date</th>
												<th>Price Summary</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody id="grid">											                                                                                          											  
                                              <tr style="background:#fff">
												<td><?php echo $_GET["id"];?></td> 
												<td><?php echo $row["booking_id"];?></td> 
												<td><?php echo date("jS M y",strtotime($row["date"]))." ( ".date("h:i a",strtotime($row["date"]))." )";?></td>
                                                <td>
												
												<b>Ticket Rate : </b><?php echo number_format(($row["rate"]),2,".",","); ?><br/>
												<!--<b>Supplier Markup : </b><?php echo number_format($row["markup"],2,".",","); ?><br/>
												<b>Admin Markup: </b><?php echo number_format($row["admin_markup"],2,".",","); ?><br/>-->
												<b>Total Ticket Fare (each) : </b><?php echo number_format($row["rate"],2,".",","); ?><br/>
												<b>Qty : </b><?php echo $row["qty"]; ?><br/>		
												<b>Sub Total : </b><?php echo number_format( $row["amount"],2,".",","); ?><br/>
												<b>Service Charge : </b><?php echo number_format( $row["service_charge"],2,".",","); ?><br/>												
												<b>GST : </b><?php echo number_format( ($row["igst"]+$row["sgst"]+$row["cgst"]),2,".",","); ?><br/>
												<b>Grand Total : </b><?php echo number_format( $row["total"],2,".",","); ?>
												</td> 
												<td>
												<?php 
													echo  $row["status"]."<br>";
													if($row["status"]=="PROCESSING FOR CANCEL")
													{
														
														echo "<b>Request Date : </b>".date("jS M y h:i a",strtotime($row["cancel_request_date"]))."<br>";
													}
													if($row["status"]=="CANCELLED")
													{
														//echo "<b>Supplier Cancellation Charge : </b>".number_format( $row["supplier_cancel_charge"],2,".",",")."<br>";
														echo "<b>Request Date : </b>".date("jS M y h:i a",strtotime($row["cancel_request_date"]))."<br>";
														echo "<b>Admin Cancellation Charge : </b>".number_format( $row["admin_cancel_charge"],2,".",",")."<br>";
														echo "<b>Cancelled Date : </b>".date("jS M y h:i a",strtotime($row["cancel_date"]))."<br>";
														echo "<b>Refunded : </b>".number_format( $row["refund_amount"],2,".",",")."<br>";
													}
												?>
												</td>
											  </tr>                                             											  											                                                                                  
										</tbody>
									</table>
                               </div>     								
							</div><!--Widget col-md-8 end-->

                           

                             <div class="col-xs-12 col-sm-12">
							    <div class="widget-box" style="float:left;width:100%">
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Update Status</small>
												</h4>

									</div>

									<div class="widget-body">																											                                            											
                                            <div class="form-group has-info col-xs-12 col-sm-12">
													
													<div class="col-xs-3">
													    <label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">PNR</label>	
													     <input type="text" class="col-xs-12 col-sm-12" name="pnr" value="<?php echo $row["pnr"];?>" required>
													</div>	 
													<div class="col-xs-3">
													    <label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">Status</label>	
														<select id="approved"  name="approved" class="col-xs-12 col-sm-12" required>
														   <?php if($row["status"]=="CONFIRM"){ ?>                                                          
                                                            <option value="CONFIRM" >CONFIRM</option>   
                                                           <?php } 
														     if($row["status"]=="PENDING") {?>
                                                            <option value="PENDING" <?php if($row["status"]=="PENDING") echo "selected";?>>PENDING</option>
                                                            <option value="CONFIRM" <?php if($row["status"]=="CONFIRM") echo "selected";?>>CONFIRM</option> 
															<option value="REJECTED" <?php if($row["status"]=="REJECTED") echo "selected";?>>REJECT</option>  
															
														   <?php }  if($row["status"]=="PROCESSING FOR CANCEL") {?>
														   
														    <option value="REQUESTED FOR CANCEL" <?php if($row["status"]=="REQUESTED FOR CANCEL") echo "selected";?>>REQUESTED FOR CANCEL</option>
                                                            <?php //if($all_supplier_cancel==1) {?>
																<option value="CANCELLED" <?php if($row["status"]=="CANCELLED") echo "selected";?>>CANCEL</option>  
															<?php //} ?>															
															
														   <?php }  if($row["status"]=="CANCELLED") {?>														    
                                                            <option value="CANCELLED" <?php if($row["status"]=="CANCELLED") echo "selected";?>>CANCELLED</option>   
                                                           <?php } ?>
														   
														    <?php if($row["status"]=="REJECTED") {?>														    
                                                            <option value="REJECTED" <?php if($row["status"]=="CANCELLED") echo "selected";?>>REJECTED</option>   
                                                           <?php } ?>
														</select>
													</div>
													
													<div class="col-xs-3">
													    <label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">&nbsp;</label>	
													     <a style="width:100%" target="_blank" class="pull-left btn btn-sm btn-success" href="<?php echo $baseurl; ?>/search/thankyou/<?php echo $row["booking_id"];;?>">Customer Ticket</a>
													</div>
													
													<div class="col-xs-3">
													    <label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">&nbsp;</label>	
													     <a style="width:100%" target="_blank" class="pull-left btn btn-sm btn-danger" href="<?php echo $baseurl; ?>/search/thankyou1/<?php echo $_GET["id"];?>">Supplier Ticket</a>
													</div>
													
													
													
															
															
											</div>	
													
													
													
									</div>
                                                                                       																						                                                                                             																																							
                                    </div>							
                            </div>						
							</div>	
							<div class="row">
								<div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
									<button type="submit" class="pull-left btn btn-sm btn-primary" name="update">															
										<span class="bigger-110">Update Status</span>
									</button>																		
								</div>
						    </div>
                                                                                                                                                                                 																																								
                    </div>	<!--row-->	
					    
						<input type="hidden" name="existing_seller_id" id="existing_seller_id" value="<?php echo $row["seller_id"]; ?>">
					    </form>
                               
                       
                             
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

 <?php include_once('footer.php');?>
 <script>
    $(document).ready(function ()
	{   
		$("#menu_booking").addClass("active");
		$("#user_id").change(function()
		{
			if($("#user_id").val()==$("#existing_seller_id").val())
			{
				$("#div_ticket_no").hide();
			}
			else
			{
				$("#div_ticket_no").show();
			}
		});
		
	});	
 </script>