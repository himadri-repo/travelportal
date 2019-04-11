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
									<?php echo $row["source_city"];?> TO <?php echo $row["destination_city"]." ( ".$trip_type." )";?><br>
                                	PNR	: <?php  echo "<b>".$row["pnr"]."</b>";?>							
							</h1>
                            <h4></h4>
                         <?php if(!empty($msg))echo $msg; ?>							
						</div><!-- /.page-header -->
						<form  id="frm_customer" enctype="multipart/form-data" action="confirm_booking.php" method="POST">	
						<input type="hidden" name="ticket_id" value="<?php echo $row["id"];?>">
						
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

                           

                            <div class="col-xs-12 col-sm-6"><!--Widget col-md-6 start-->
							    <div class="widget-box" style="float:left;width:100%"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Update Status</small>
												</h4>

									</div>

									<div class="widget-body"><!--Widget Body start-->
																											                                            
											<div class="form-group has-info col-xs-6 col-sm-6">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> No. of Person</label>
													<div class="col-xs-12 col-sm-12">
														<input type="number" name="no_of_person" class="col-xs-12 col-sm-12"  min="1" max="<?php echo $row["no_of_person"]; ?>" required>
													</div>
											</div>
                                            <div class="form-group has-info col-xs-6 col-sm-6">
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
											</div>
                                            
                                           																						                                                                                             																																								
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-6 end-->	
                                                                                                                                                                                 																																								
                        </div>	<!--row-->	
					    <div class="row">
						   <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
							<button type="submit" class="pull-left btn btn-sm btn-primary" name="continue">															
								<span class="bigger-110">Continue</span>
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