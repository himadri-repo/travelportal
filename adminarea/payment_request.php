<?php 
include_once('header.php');
if(!in_array(4,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
$msg="";
if(isset($_GET["id"]) && isset($_GET["user_id"]))
{
	$sql_check="SELECT p.*,u.email FROM 
	payment_request_tbl p INNER JOIN user_tbl u ON p.user_id=u.id WHERE p.status=0 AND p.id='".$_GET["id"]."'";
	$result_check=mysql_query($sql_check);
	if(mysql_num_rows($result_check)>0)
	{
		$row_check=mysql_fetch_array($result_check);
		$refrence=$row_check["id"];
		$email=$row_check["email"];
		$sql_update="update payment_request_tbl set status=1 where id='".$_GET["id"]."'";
		
		if(mysql_query($sql_update))
		{
			$date=date("Y-m-d h:i:s");
			$sql_insert="INSERT INTO wallet_tbl(date,amount,narration,type,user_id,refrence)values('$date','$_GET[amount]','','CR','$_GET[user_id]','$refrence')";
			if(mysql_query($sql_insert))
			{
				 $link="";
				 $msg="Your Payment Approved For ".$row_check["amount"]."";
				 $msg1="";
				 $msg2="";
			     $subject="Payment Approve";
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
						<td valign="middle" style="font-size:18px;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;text-align:left"> '.$msg.' </td>
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
			   $msg="Money Sent Successfully";
			}
			else
				$msg="";
		} 
	}
}
?>
<title>Payment Request | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>				
								Payment Request							
							</h1>
                           <?php
							  if(!empty($msg))
							  {
                            ?>	
							  <div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 "><?php echo $msg; ?></div>
                            <?php
							   $msg="";
							}
                            ?>							
						</div><!-- /.page-header -->                      
						
						
                        <!--<div class="row">
							   <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
								<a href="celebrity_form.php"  class="pull-left btn btn-sm btn-primary" >															
								<span class="bigger-110">Add New</span>
								</a>																		
							  </div>
						</div>-->
						<div class="row">
							<div class="col-xs-12">																				
								<div class="table-header" id="result">								
								</div>
                                <div class="form-group has-info col-sm-12" style="float:left;margin-top:15px">									
									<label class="col-sm-3 control-label no-padding-right" for="form-field-username">Search With Name </label>									
									<div class="col-sm-6" id="div_txt_name">
									  <span class="block input-icon input-icon-left">
									  <input class="col-xs-12 col-sm-12" type="text" name="txt_search" id="txt_search" placeholder="Search..." value="" >														 
									</div>
								</div>
					
								<div style="background:#EFF3F8;border:1px solid #ccc" >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>SI No.</th>	
												<th>Agent ID</th>
                                                <th>Name</th>
												<th>Request Date</th>
												<th>Payment Type</th>
												<th>Payment Details</th>
												<th>Amount</th>												
												<th>Status</th>	
                                                <th>Pay</th>												
											</tr>
										</thead>
										<tbody id="grid">											                                                                                         									
										</tbody>
									</table>

									

									<div class="row">
										<div class="col-xs-6">
											<div class="dataTables_info" id="info" role="status" aria-live="polite" style="padding-left:20px">									
											</div>
										</div>
										<div class="col-xs-6">
											<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
												<ul class="pagination" id="div_pagination">

													
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
<?php include_once('footer.php');?>

 <script src="<?php echo $baseurl;?>/adminarea/script/payment_request.js"></script>  			
 <link rel="stylesheet" href="../calendar/base/jquery.ui.all.css">
 <script src="../calendar/jquery-1.5.1.js"></script>
 <script src="../calendar/jquery.ui.core.js"></script>
 <script src="../calendar/jquery.ui.widget.js"></script>
 <script src="../calendar/jquery.ui.datepicker.js"></script> 
 <script>
	$(function() 
	{
		$("#menu_payment_request").addClass("active");
		$( "#dt_date_from" ).datepicker();
		$( "#dt_date_to" ).datepicker();
		$( "#dt_date_from" ).datepicker("option", "dateFormat","dd-mm-yy");
		$( "#dt_date_to" ).datepicker("option", "dateFormat","dd-mm-yy");
	});
</script> 