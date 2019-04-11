<?php 
include_once('header.php'); 
if(!in_array(7,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
$msg="";
if(isset($_POST["continue"]))
{  
    $sql="SELECT  
	`u`.`name`, `u`.`email`, `u`.`mobile`,u.type,`t`.`ticket_no`,t.price,t.discount,t.markup,t.admin_markup, 
	 `c`.`city` as `source`, `c1`.`city` as `source1`, `ct`.`city` as `destination`, `ct1`.`city` as `destination1`, 
	`a`.`airline`, `a1`.`airline` as `airline1`, `t`.`class`, `t`.`class1`, `t`.`departure_date_time`, `t`.`departure_date_time1`,t.id, 
	`t`.`arrival_date_time`, `t`.`arrival_date_time1`, `t`.`trip_type`, `t`.`terminal`, `t`.`terminal1`, `t`.`terminal2`, `t`.`terminal3`, 
	`t`.`flight_no`, `t`.`flight_no1`,t.user_id
	 
	 FROM `tickets_tbl` as `t` 
	 
	 JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` LEFT JOIN `airline_tbl` `a1` ON `a1`.`id` = `t`.`airline1` 
	 JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
	 LEFT JOIN `city_tbl` `c1` ON `c1`.`id` = `t`.`source1` LEFT JOIN `city_tbl` `ct1` ON `ct1`.`id` = `t`.`destination1` 
	 JOIN `user_tbl` `u` ON `t`.`user_id` =`u`.`id` 
	 WHERE `t`.`id` ='".$_POST["ticket_id"]."'";

	
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
	
	$sql_details="SELECT * FROM user_tbl WHERE id='".$_POST["user_id"]."'";
	$result_details=mysql_query($sql_details);
	$row_details=mysql_fetch_array($result_details);
}
else
{
	?>
	<script>window.location.href="book_tickets.php";</script>
	<?php
}

if(isset($_POST["confirm"]))
{
	$sql_booking="INSERT INTO  booking_tbl(date,ticket_id,seller_id,customer_id,status,qty,rate,amount,sgst,cgst,igst,total,type)
	VALUES('".$_POST["date"]."','".$_POST["ticket_id"]."','".$_POST["seller_id"]."','".$_POST["customer_id"]."','".$_POST["status"]."','".$_POST["qty"]."','".$_POST["rate"]."','".$_POST["amount"]."','".$_POST["sgst"]."','".$_POST["igst"]."','".$_POST["cgst"]."','".$_POST["total"]."','".$_POST["type"]."')";
    if(mysql_query($sql_booking))
	{
		$booking_id=mysql_insert_id();
		foreach($_POST["prefix"] as $key=>$value)
		{
			$sql_customer_info="INSERT INTO customer_information_tbl (prefix,first_name,last_name,mobile_no,age,email,booking_id)
			VALUES('".$_POST["prefix"][$key]."','".$_POST["first_name"][$key]."','".$_POST["last_name"][$key]."','".$_POST["mobile_no"][$key]."','".$_POST["age"][$key]."','".$_POST["email"][$key]."','$booking_id')";
		    mysql_query($sql_customer_info);
		  
		}
		$sql_debit="INSERT INTO wallet_tbl(date,user_id,amount,narration,booking_id,type) VALUES('".$_POST["date"]."','".$_POST["customer_id"]."','".(0-$_POST["amount"])."','TICKET BOOKING','$booking_id','DR')";
		mysql_query($sql_debit);
		
		$qty=$_POST["qty"];
		$sql_ticket_update="UPDATE tickets_tbl SET no_of_person=(no_of_person-$qty) WHERE id='".$_POST["ticket_id"]."'";
		mysql_query($sql_ticket_update);
		$_SESSION["booking_msg"]="Booking Completed Successfully";
	}
}
?>
<title>Confirm Booking | <?php echo $row_top['site_title']; ?></title>
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
									CONFIRM BOOKING		
							</h1>
                            
                         <?php if(!empty($msg))echo $msg; ?>							
						</div><!-- /.page-header -->
						<form  id="frm_customer" enctype="multipart/form-data" action="" method="POST">	
						
						
						<div class="row">
							<div class="col-xs-12 col-sm-12"><!--Widget col-md-8 start-->
							    <div style="" >
								   
								   <h3 style="color:#2679b5;font-size:18px">PASSENGER DETAILS</h3>								   
								   <hr style="margin:0"/ >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover" style="margin-bottom:20px">
										<thead>
											<tr>                                                
												<th>Si No.</th>	
												<th>Prefix</th>
												<th>First Name</th>
												<th>Last Name</th>
												<th>Age</th>												
												<th>Mobile No.</th>
												<th>Email</th>
												
												
											</tr>
										</thead>
										<tbody id="grid">											                                            
                                              <?php
											      $ctr=1;
												  while($ctr<=$_POST["no_of_person"])
												  {
                                               ?>	
													<tr>                                                
													<td><?php echo $ctr; ?></td>
													<td>
													   <select name="prefix[]" class="form-control" required>
													     <option value="Mr.">Mr.</option>
														 <option value="Miss.">Miss.</option>
														 <option value="Mrs.">Mrs.</option>
														 <option value="Master">Master</option>
													   </select>
													</td>
													<td><input type="text" name="first_name[]" class="form-control" required value="<?php echo $row_details["name"];?>">
													<td><input type="text" name="last_name[]" class="form-control" value="">
													<td><input type="text" name="age[]" class="form-control" required value="19">																																							
													<td><input type="text" name="mobile_no[]" class="form-control" required value="<?php echo $row_details["mobile"];?>">
													<td><input type="text" name="email[]" class="form-control" required value="<?php echo $row_details["email"];?>">
													</tr>
											   <?php
											       $ctr++;
												  }
											   ?>
										</tbody>
										
									</table>
									<?php $dateDiff = intval((strtotime($row["arrival_date_time"])-strtotime($row["departure_date_time"]))/60);?>
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
												<th>Ticket No.</th>																																																									
												<th>Booking Date</th>
												<th>Price Summary</th>
												
											</tr>
										</thead>
										<tbody id="grid">											                                                                                          											  
                                              <tr style="background:#fff">
												<td><?php echo $row["id"];?></td> 
												<td><?php echo date("jS M y");?></td>
                                                <td>
												<?php 
												if($_POST["user_id"]==$row["user_id"])
												{
													$rate=($row["price"]-$row["discount"]);
												}
												else
												{
													$rate=($row["price"]+$row["markup"]-$row["discount"]+$row["admin_markup"]);
												}
												$grand_total=$_POST["no_of_person"]*$rate+$row_top["igst"]+$row_top["sgst"]+$row_top["cgst"]+$row_top["service_charge"];
												?>
												<b>Ticket Fare : </b><?php echo number_format($rate,2,".",","); ?><br/>	
												<b>No. of Tickets : </b><?php echo $_POST["no_of_person"]; ?><br/>	
												<b>Sub Total : </b><?php echo number_format($_POST["no_of_person"]*$rate,2,".",","); ?><br/>
												<b>Service Charge : </b><?php echo number_format( $row_top["service_charge"],2,".",","); ?><br/>												
												<b>GST : </b><?php echo number_format( ($row_top["igst"]+$row_top["sgst"]+$row_top["cgst"]),2,".",","); ?><br/>
												<b>Grand Total : </b><?php echo number_format($grand_total,2,".",","); ?>
												<input type="hidden" name="date" value="<?php echo date("Y-m-d h:i:s");?>">
												<input type="hidden" name="ticket_id" value="<?php echo $_POST["ticket_id"];?>">						                       
												<input type="hidden" name="seller_id" value="<?php echo $row["user_id"];?>">
												<input type="hidden" name="customer_id" value="<?php echo $_POST["user_id"];?>">
												<input type="hidden" name="status" value="PENDING">
												<input type="hidden" name="rate" value="<?php echo  $rate;?>">
												<input type="hidden" name="qty" value="<?php echo  $_POST["no_of_person"];?>">
												<input type="hidden" name="amount" value="<?php echo  $rate*$_POST["no_of_person"];?>">
												<input type="hidden" name="sgst" value="<?php echo  $row_top["sgst"];?>">
												<input type="hidden" name="cgst" value="<?php echo  $row_top["cgst"];?>">
												<input type="hidden" name="igst" value="<?php echo  $row_top["igst"];?>">
												<input type="hidden" name="service_charge" value="<?php echo  $row_top["service_charge"];?>">
												<input type="hidden" name="total" value="<?php echo  $grand_total;?>">
												<input type="hidden" name="type" value="<?php echo  $row_details["type"];?>">
												</td> 
												
											  </tr>                                             											  											                                                                                  
										</tbody>
									</table>
                               </div>     								
							</div><!--Widget col-md-8 end-->

                           

                             				
					    </div>	
						<div class="row">
							<div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
								<button type="submit" class="pull-left btn btn-sm btn-primary" name="confirm">															
									<span class="bigger-110">Confirm</span>
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
		$("#menu_book_tickets").addClass("active");				
	});	
 </script>