<?php 
include_once('header.php'); 
$msg="";
$ctr=1;
$string="";
if(isset($_POST["chk"]))
{

	foreach($_POST["chk"] as $value)
	{
		if($ctr==1)
			$string=$value;
	    else
			$string.=",".$value;
		$ctr++;
	}
	
	if(empty($string))
	{
		?>
		<script>window.location.href="tickets.php";</script>
		<?php
	}
	
}

if(isset($_POST["update"]))
{
	$ticket_ids=$_POST["ticket_ids"];
	
		if(empty($_POST["admin_markup"]))
			$sql_update="UPDATE tickets_tbl SET approved='".$_POST["approved"]."' WHERE id in ($ticket_ids)  ";
		else
			$sql_update="UPDATE tickets_tbl SET approved='".$_POST["approved"]."',admin_markup='".$_POST["admin_markup"]."' WHERE id in ($ticket_ids)  ";				
	
	
	foreach($_POST["chk"] as $value)
	{
        $sql="SELECT t.no_of_person,u.email FROM tickets_tbl INNER JOIN user_tbl u ON t.user_id=u.id WHERE t.id='".$value."'";
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		$email=$row["email"];
		$link="";
				 $msg="Your Ticket Approved ";
				 $msg1="";
				 $msg2="";
			     $subject="Ticket Approve";
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
	}
	if(mysql_query($sql_update))
	{
		$msg="<div class='alert alert-success'>Ticket Approved Successfully</div>";
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
									UPDATE TICKET						
							</h1>
                            
                         <?php if(!empty($msg))echo $msg; ?>							
						</div><!-- /.page-header -->
						<form  id="frm_customer" enctype="multipart/form-data" action="" method="POST">							
						<input type="hidden" name="ticket_ids" value="<?php echo $string;?>">
						<div class="row">							                         
                            <div class="col-xs-12 col-sm-6"><!--Widget col-md-6 start-->
							    <div class="widget-box" style="float:left;width:100%"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Update Status</small>
												</h4>

									</div>

									<div class="widget-body"><!--Widget Body start-->
																											                                            
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Admin Markup</label>
													<div class="col-xs-12 col-sm-12">
														<input type="number" class="form-control" name="admin_markup">
													</div>
											</div>
											
                                            <div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Status</label>
													<div class="col-xs-12 col-sm-12">
														<select id="approved"  name="approved" class="col-xs-12 col-sm-12" >														                                                    
                                                             <option value="0" <?php if($row["approved"]==0) echo "selected";?>>Pending</option>
                                                             <option value="1" <?php if($row["approved"]==1) echo "selected";?>>Approve</option>   
                                                             <option value="2" <?php if($row["approved"]==2) echo "selected";?>>Reject</option>  
															 <option value="3" <?php if($row["approved"]==3) echo "selected";?>>Freeze</option> 														   
                                                        </select>
													</div>
											</div>
                                            
                                           																						                                                                                             																																								
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-6 end-->	
                                                                                                                                                                                 																																								
                        </div>	<!--row-->	
					    <div class="row">
						   <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
							<button type="submit" class="pull-left btn btn-sm btn-primary" name="update">															
								<span class="bigger-110">Update Status</span>
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
		$("#menu_tickets").addClass("active");		
		
	});	
 </script>