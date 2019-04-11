<?php 
include_once('header.php'); 
$msg="";
if(isset($_POST["update"]))
{  
    
	if($_POST["approved"]=="CONFIRM")
	{
		$sql_check="SELECT * FROM booking_tbl WHERE  id='".$_POST["booking_id"]."' AND status='CONFIRM'";
		$result_check=mysql_query($sql_check);
		if(mysql_num_rows($result_check)==0)
		{
			
			
			$service_charge=$row_check["service_charge"];
			$sgst=$row_check["sgst"];
			$cgst=$row_check["cgst"];
			$igst=$row_check["igst"];
			$gst=$sgst+$cgst+$igst;
			
			if($_POST["existing_seller_id"]==$_POST["user_id"])
			{
				$sql_update="UPDATE booking_tbl SET status='".$_POST["approved"]."',pnr='".$_POST["pnr"]."',booking_confirm_date='".date("Y-m-d h:i:s")."' WHERE id='".$_POST["booking_id"]."'";
				if(mysql_query($sql_update))
				{
					$sql_get="SELECT b.id,t.total,t.user_id,b.qty,u.email FROM tickets_tbl t 
					INNER JOIN booking_tbl b ON t.id=b.ticket_id 
					INNER JOIN user_tbl u ON t.user_id=u.id 
					WHERE b.id='".$_POST["booking_id"]."'";
					$result_get=mysql_query($sql_get);
					$row_get=mysql_fetch_array($result_get);
					$user_id=$row_get["user_id"];
					$amount=$row_get["total"]*$row_get["qty"];
					$booking_id=$row_get["id"];
					$email=$row_get["email"];
					$sql_credit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$user_id','$amount','','$booking_id','CR')";
					mysql_query($sql_credit);
					$msg="<div class='alert alert-success'>Approved Successfully</div>";
				}
				else
				{					
					echo mysql_error();die();
				}
			}
			else
			{
				    $sql_get="SELECT * FROM booking_tbl WHERE  id='".$_POST["booking_id"]."'";
					$result_get=mysql_query($sql_get);
					
					$row_get=mysql_fetch_array($result_get);
					$ticket_id=$row_get["ticket_id"];
					$qty=$row_get["qty"];
				    
				    $sql_ticket="SELECT t.*,u.email FROM tickets_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.id='".$ticket_id."'";
					$result_ticket=mysql_query($sql_ticket);
					$row_ticket=mysql_fetch_array($result_ticket);
					$no_of_person=$row_ticket["no_of_person"];
					$no_of_person=$no_of_person+$qty;
					$email=$row_ticket["email"];
					
					$sql_update_ticket="UPDATE tickets_tbl SET no_of_person='$no_of_person' WHERE id='".$ticket_id."'";
					mysql_query($sql_update_ticket);
						
					
					$sql_current_ticket="SELECT * FROM tickets_tbl WHERE id='".$_POST["ticket_id"]."'";
					$result_current_ticket=mysql_query($sql_current_ticket);										
					$row_current_ticket=mysql_fetch_array($result_current_ticket);
					
				    $rate=$row_current_ticket["total"];
					$amount=$qty*$rate;
					$total=$amount+$gst+$service_charge+$row_current_ticket["admin_markup"];
					$current_no_of_person=$row_current_ticket["no_of_person"]-$qty;
					
					$sql_update_booking="UPDATE booking_tbl SET ticket_id='".$_POST["ticket_id"]."',status='".$_POST["approved"]."',pnr='".$_POST["pnr"]."',seller_id='".$_POST["user_id"]."',booking_confirm_date='".date("Y-m-d h:i:s")."',rate='$rate',amount='$amount',total='$total' WHERE id='".$_POST["booking_id"]."'";
					mysql_query($sql_update_booking);
						
				    $sql_credit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','".$_POST["user_id"]."' ,'$amount','','".$_POST["booking_id"]."' ,'CR')";
					mysql_query($sql_credit); 
						
					$sql_update_ticket="UPDATE tickets_tbl SET no_of_person='$current_no_of_person' WHERE id='".$_POST["ticket_id"]."'";
					mysql_query($sql_update_ticket);
					
					$msg="<div class='alert alert-success'>Approved Successfully</div>";
				 
			}
			    
		          $link="";
				 $msg0="Your Booking is  Approved ";
				 $msg1="";
				 $msg2="";
			     $subject="Booking Approve";
				 $emailaddress="OXYTRA <noreply@yourwebsite.co.in>";
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
		$admin_cancel_charge=isset($_POST["admin_cancel_charge"])?$_POST["admin_cancel_charge"]:"";
		$sql_check="SELECT * FROM booking_tbl WHERE  id='".$_POST["booking_id"]."' AND status='CANCELLED'";
		$result_check=mysql_query($sql_check);
		if(mysql_num_rows($result_check)==0)
		{
			
			$sql_update="UPDATE booking_tbl SET status='".$_POST["approved"]."',pnr='".$_POST["pnr"]."',admin_cancel_charge='".$_POST["admin_cancel_charge"]."',refund_amount=(total-supplier_cancel_charge-$admin_cancel_charge),cancel_date='".date("Y-m-d h:i:s")."' WHERE id='".$_POST["booking_id"]."'";
			if(mysql_query($sql_update))
			{
				$sql_get="SELECT b.seller_id,b.refund_amount,b.customer_id,b.id,b.ticket_id,t.total,t.user_id,b.qty FROM tickets_tbl t INNER JOIN booking_tbl b ON t.id=b.ticket_id WHERE b.id='".$_POST["booking_id"]."'";
				$result_get=mysql_query($sql_get);
				$row_get=mysql_fetch_array($result_get);
				$qty=$row_get["qty"];
				$customer_id=$row_get["customer_id"];
				$seller_id=$row_get["seller_id"];
				$ticket_id=$row_get["ticket_id"];
				$amount=$row_get["refund_amount"];
				
				$sql_ticket="SELECT * FROM tickets_tbl WHERE id='".$ticket_id."'";
				$result_ticket=mysql_query($sql_ticket);
				$row_ticket=mysql_fetch_array($result_ticket);
				$no_of_person=$row_ticket["no_of_person"];
				$no_of_person=$no_of_person+$qty;
				
				$sql_update="UPDATE tickets_tbl SET  no_of_person='$no_of_person' WHERE id='".$ticket_id."'";
				if(mysql_query($sql_update))
				{
					
					$sql_credit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$customer_id','$amount','REFUND','".$_POST["booking_id"]."','CR')";
					if(mysql_query($sql_credit))
					{
						
						$sql_debit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".date("Y-m-d h:i:s")."','$seller_id','".(0-$amount)."','REFUND','".$_POST["booking_id"]."','DR')";
						if(mysql_query($sql_debit))
						 echo $msg="<div class='alert alert-success'>Cancelled Successfully</div>";
						else
						  echo " SQL DEBIT ERROR : ".mysql_error();die();
					}
					else
					{
						echo " SQL CREDIT ERROR : ".mysql_error();die();
					}
				}
				else
				{
					echo " UPDATE TICKET QUERY ERROR : ".mysql_error();die();
				}
				
			}
			else
			{
				echo " REFUND QUERY ERROR : ".mysql_error();die();
		    }
		}
		else
		{
			$msg="<div class='alert alert-danger'>This Booking already Cancelled</div>";
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
	
	$sql="SELECT `cus`.`prefix`, `cus`.`age`, `cus`.`first_name`, `cus`.`last_name`, `cus`.`email`, `cus`.`mobile_no`, `u`.`name`, `u`.`email`, `u`.`mobile`, `b`.`id`, `t`.`ticket_no`,t.price,t.discount,t.markup,t.admin_markup, `b`.`pnr`, `b`.`date`, `c`.`city` as `source`, `c1`.`city` as `source1`, `ct`.`city` as `destination`, `ct1`.`city` as `destination1`, `a`.`airline`, `a1`.`airline` as `airline1`, `t`.`class`, `t`.`class1`, `t`.`departure_date_time`, `t`.`departure_date_time1`, `t`.`arrival_date_time`, `t`.`arrival_date_time1`, `t`.`trip_type`, `t`.`terminal`, `t`.`terminal1`, `t`.`terminal2`, `t`.`terminal3`, `t`.`flight_no`, `t`.`flight_no1`, `b`.`service_charge`, `b`.`sgst`, `b`.`cgst`, `b`.`igst`, `b`.`rate`, `b`.`qty`, `b`.`amount`, `b`.`total`, `b`.`type`, `b`.`status`,b.supplier_cancel_charge,b.cancel_request_date,b.admin_cancel_charge,b.cancel_date,b.refund_amount,b.customer_id,b.seller_id FROM `tickets_tbl` as `t` JOIN `booking_tbl` `b` ON `b`.`ticket_id` = `t`.`id` JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` LEFT JOIN `airline_tbl` `a1` ON `a1`.`id` = `t`.`airline1` JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` LEFT JOIN `city_tbl` `c1` ON `c1`.`id` = `t`.`source1` LEFT JOIN `city_tbl` `ct1` ON `ct1`.`id` = `t`.`destination1` JOIN `user_tbl` `u` ON `b`.`customer_id` =`u`.`id` LEFT JOIN `customer_information_tbl` `cus` ON `b`.`id` =`cus`.`booking_id` WHERE `b`.`id` ='".$_GET["id"]."'";
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
                                                  $sql_details="SELECT * FROM customer_information_tbl WHERE booking_id='".$_GET["id"]."'";
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
													<td><input type="text" name="pnr" class="form-control"></td>
													</tr>
											   <?php
											       $ctr++;
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
												<th>Booking Date</th>
												<th>Price Summary</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody id="grid">											                                                                                          											  
                                              <tr style="background:#fff">
												<td><?php echo $_GET["id"];?></td> 
												<td><?php echo date("jS M y",strtotime($row["date"]))." ( ".date("h:i a",strtotime($row["date"]))." )";?></td>
                                                <td>
												
												<b>Ticket Rate : </b><?php echo number_format(($row["price"]-$row["discount"]),2,".",","); ?><br/>
												<b>Supplier Markup : </b><?php echo number_format($row["markup"],2,".",","); ?><br/>
												<b>Admin Markup: </b><?php echo number_format($row["admin_markup"],2,".",","); ?><br/>
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
														echo "<b>Supplier Cancellation Charge : </b>".number_format( $row["supplier_cancel_charge"],2,".",",")."<br>";
														echo "<b>Request Date : </b>".date("jS M y h:i a",strtotime($row["cancel_request_date"]))."<br>";
													}
													if($row["status"]=="CANCELLED")
													{
														echo "<b>Supplier Cancellation Charge : </b>".number_format( $row["supplier_cancel_charge"],2,".",",")."<br>";
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
													    <label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> PNR</label>
													    <input type="text" name="pnr" id="pnr" class="col-xs-12 col-sm-12" value="<?php echo $row["pnr"]; ?>" required>
													</div>
													
													<div class="col-xs-3">
													    <label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">Status</label>	
														<select id="approved"  name="approved" class="col-xs-12 col-sm-12" >
														   <?php if($row["status"]=="CONFIRM"){ ?>                                                          
                                                            <option value="CONFIRM" >CONFIRM</option>   
                                                           <?php } 
														     if($row["status"]=="PENDING") {?>
                                                            <option value="PENDING" <?php if($row["status"]=="PENDING") echo "selected";?>>PENDING</option>
                                                            <option value="CONFIRM" <?php if($row["status"]=="CONFIRM") echo "selected";?>>CONFIRM</option>   
															
														   <?php }  if($row["status"]=="PROCESSING FOR CANCEL") {?>
														   
														    <option value="PROCESSING FOR CANCEL" <?php if($row["status"]=="PROCESSING FOR CANCEL") echo "selected";?>>PROCESSING FOR CANCEL</option>
                                                            <option value="CANCELLED" <?php if($row["status"]=="CANCELLED") echo "selected";?>>CANCELLED</option>   
															
														   <?php }  if($row["status"]=="CANCELLED") {?>														    
                                                            <option value="CANCELLED" <?php if($row["status"]=="CANCELLED") echo "selected";?>>CANCELLED</option>   
                                                           <?php } ?>
														</select>
													</div>
													<?php if($row["status"]=="PROCESSING FOR CANCEL") {?>
														<div class="col-xs-3">
															<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">CANCELLATION CHARGE</label>
															<input type="number" name="admin_cancel_charge" id="admin_cancel_charge" class="col-xs-12 col-sm-12" value="" required>
														</div>	
													<?php } ?>
													
													<?php if($row["status"]=="PENDING") {?>
														
															
															<!--<div class="col-sm-3" id="div_user">
															<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">Change Seller</label>
															  <select  class="col-xs-12 col-sm-12" name="user_id" id="user_id">
																
																<?php 
																$sql="SELECT * FROM user_tbl WHERE is_supplier=1";
																$result=mysql_query($sql);
																while($rows=mysql_fetch_array($result))
																{										
															   ?>
																<option value="<?php echo $rows["id"];?>" <?php if($rows["id"]==$row["seller_id"]) echo "selected"; ?>><?php echo $rows["name"]." ( ".$rows["user_id"]." ) "; ?></option>
																<?php
																}
																?>
															  </select>
															</div>
															<?php } ?>
															
															<div class="col-xs-3" style="display:none" id="div_ticket_no">
																<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Ticket No.</label>
																<input type="text" name="ticket_id" id="ticket_id" class="col-xs-12 col-sm-12" value="">
															</div>-->
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