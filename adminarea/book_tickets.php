<?php 
include_once('header.php');
if(!in_array(7,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
$trip_type=isset($_POST["trip_type"])?$_POST["trip_type"]:"";
$source=isset($_POST["source"])?$_POST["source"]:"";
$destination=isset($_POST["destination"])?$_POST["destination"]:"";
$departure_date_time=isset($_POST["departure_date_time"])?$_POST["departure_date_time"]:date("d-m-Y");
$no_of_person=isset($_POST["no_of_person"])?$_POST["no_of_person"]:"";

if(isset($_POST["submit"]))
{
	  $diff = intval((strtotime($departure_date_time)-strtotime(date("d-m-Y")))/60);
	  $diff=intval($diff/60);
	  $days=$diff/24;
      if($departure_date_time==date("d-m-Y")) 
	  {
		  $departure_date_time=date("Y-m-d H:i:s");
		 /* $sql_query="SELECT `t`.`id` as ticket_no, `t`.`source`, `t`.`destination`, `t`.`pnr`, `t`.`departure_date_time`, `t`.`arrival_date_time`, `t`.`total`, `t`.`admin_markup`, `t`.`markup`, `t`.`sale_type`, `t`.`refundable`, `c`.`city` as `source_city`, `ct`.`city` as `destination_city`, `t`.`no_of_person`, `t`.`class`, `a`.`image`,a.airline, `t`.`user_id`, `t`.`no_of_stops`,t.trip_type,u.name,u.user_id as uid
    	  FROM `tickets_tbl` `t` JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` 
    	  JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` 
    	  JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
    	  JOIN `user_tbl` `u` ON `u`.`id` = `t`.`user_id` 
    	  WHERE `t`.`source` = '$source' AND `t`.`destination` = '$destination' AND t.departure_date_time >'$departure_date_time' 
    	  AND `t`.`trip_type` = '$trip_type' AND `t`.`approved` = '1' AND `t`.`no_of_person` >= '$no_of_person' AND `t`.`availibility` >= $days ORDER BY (total+admin_markup) ASC";	 */ 
    	  
    	  $sql_query="SELECT `t`.`id` as ticket_no, `t`.`source`, `t`.`destination`, `t`.`pnr`, `t`.`departure_date_time`, `t`.`arrival_date_time`, `t`.`total`, `t`.`admin_markup`, `t`.`markup`, `t`.`sale_type`, `t`.`refundable`, `c`.`city` as `source_city`, `ct`.`city` as `destination_city`, `t`.`no_of_person`, `t`.`class`, `a`.`image`,a.airline, `t`.`user_id`, `t`.`no_of_stops`,t.trip_type,u.name,u.user_id as uid
    	  FROM `tickets_tbl` `t` JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` 
    	  JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` 
    	  JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
    	  JOIN `user_tbl` `u` ON `u`.`id` = `t`.`user_id` 
    	  WHERE `t`.`source` = '$source' AND `t`.`destination` = '$destination' AND t.departure_date_time >'$departure_date_time' 
    	  AND `t`.`trip_type` = '$trip_type' AND `t`.`approved` = '1' AND `t`.`no_of_person` >= '$no_of_person'ORDER BY (total+admin_markup) ASC";	 
	  }
	  else		
	  {		
          $departure_date_time=date("Y-m-d",strtotime($departure_date_time));
          /*$sql_query="SELECT `t`.`id` as ticket_no, `t`.`source`, `t`.`destination`, `t`.`pnr`, `t`.`departure_date_time`, `t`.`arrival_date_time`, `t`.`total`, `t`.`admin_markup`, `t`.`markup`, `t`.`sale_type`, `t`.`refundable`, `c`.`city` as `source_city`, `ct`.`city` as `destination_city`, `t`.`no_of_person`, `t`.`class`, `a`.`image`,a.airline, `t`.`user_id`, `t`.`no_of_stops`,t.trip_type,u.name,u.user_id as uid
    	  FROM `tickets_tbl` `t` JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` 
    	  JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` 
    	  JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
    	  JOIN `user_tbl` `u` ON `u`.`id` = `t`.`user_id` 
    	  WHERE `t`.`source` = '$source' AND `t`.`destination` = '$destination' AND DATE_FORMAT(t.departure_date_time, '%Y-%m-%d') = '$departure_date_time' 
    	  AND `t`.`trip_type` = '$trip_type' AND `t`.`approved` = '1' AND `t`.`no_of_person` >= '$no_of_person' AND `t`.`availibility` >= $days ORDER BY (total+admin_markup) ASC";	 */ 
    	  
    	  $sql_query="SELECT `t`.`id` as ticket_no, `t`.`source`, `t`.`destination`, `t`.`pnr`, `t`.`departure_date_time`, `t`.`arrival_date_time`, `t`.`total`, `t`.`admin_markup`, `t`.`markup`, `t`.`sale_type`, `t`.`refundable`, `c`.`city` as `source_city`, `ct`.`city` as `destination_city`, `t`.`no_of_person`, `t`.`class`, `a`.`image`,a.airline, `t`.`user_id`, `t`.`no_of_stops`,t.trip_type,u.name,u.user_id as uid
    	  FROM `tickets_tbl` `t` JOIN `airline_tbl` `a` ON `a`.`id` = `t`.`airline` 
    	  JOIN `city_tbl` `c` ON `c`.`id` = `t`.`source` 
    	  JOIN `city_tbl` `ct` ON `ct`.`id` = `t`.`destination` 
    	  JOIN `user_tbl` `u` ON `u`.`id` = `t`.`user_id` 
    	  WHERE `t`.`source` = '$source' AND `t`.`destination` = '$destination' AND DATE_FORMAT(t.departure_date_time, '%Y-%m-%d') = '$departure_date_time' 
    	  AND `t`.`trip_type` = '$trip_type' AND `t`.`approved` = '1' AND `t`.`no_of_person` >= '$no_of_person' ORDER BY (total+admin_markup) ASC";
	  }	  
	  //echo $sql_query;die();
	 
	  
	  
}
else
{
	$sql_query="";
}
?>
<style>
#dynamic-table td
{
 font-size:11px;   
}
</style>
<title>Tickets | <?php echo $row_top['site_title']; ?></title>
<script>
var date='<?php echo date("d-m-Y",strtotime($departure_date_time)); ?>';
</script>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>				
								Book Ticket						
							</h1>
                          	<?php 
							if(!empty($_SESSION["booking_msg"]))
							{
								echo "<div class='alert alert-success'>".$_SESSION["booking_msg"]."</div>";
								unset($_SESSION["booking_msg"]);
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
								<form action="" method="post" autocomplete="off">
                                    <div class="form-group has-info col-sm-12" style="float:left;margin-top:15px">	
                                    <div class="col-sm-2">
									    <select  class="col-xs-12 col-sm-12" name="trip_type" id="trip_type" autocomplete="off" required>
										<option value="" selected>Trip Type</option>
									  	<option value="ONE" <?php if($trip_type=="ONE") echo "selected";?>>ONE</option>			
										<option value="ROUND" <?php if($trip_type=="ROUND") echo "selected";?>>ROUND</option>
										</select>
									</div>								
									<div class="col-sm-2" id="div_txt_name">									  
                                      <select  class="col-xs-12 col-sm-12" name="source" id="source" autocomplete="off" required>										   
										<option value="">Source</option>
										<?php 
										$sql="SELECT * FROM city_tbl ORDER BY city ASc";
										$result=mysql_query($sql);
										while($row=mysql_fetch_array($result))
										{										
									   ?>
										<option value="<?php echo $row["id"];?>" <?php if($source==$row["id"]) echo "selected";?>><?php echo $row["city"]; ?></option>
										<?php
										}
										?>
									  </select>
									</div>
									
									<div class="col-sm-2" id="div_status">
									  <select  class="col-xs-12 col-sm-12" name="destination" id="destination" autocomplete="off" required>	
									  
										<?php 
										 if(isset($_POST["submit"]))
										 {
											$sql="SELECT * FROM city_tbl WHERE id='".$destination."'";
											$result=mysql_query($sql);
											while($row=mysql_fetch_array($result))
											{										
											?>
											<option value="<?php echo $row["id"];?>" <?php if($destination==$row["id"]) echo "selected";?>><?php echo $row["city"]; ?></option>
											<?php
											}									
										}
										 else
										 {
											 ?>
											   <option value="">Destination</option>
											 <?php
										 }
										?>
									  </select>
									</div> 
									
									
									
									<div class="col-sm-2" id="div_date_from">
									  <input type="text"  id="departure_date_time" name="departure_date_time" placeholder="Departing Date" class="col-xs-12 col-sm-12 dpd3"  autocomplete="off" required>									
									</div>
							
									<div class="col-sm-2" id="div_date_to">
									  <select  class="col-xs-12 col-sm-12" name="no_of_person" id="no_of_person" autocomplete="off" required>	
									    <option value="">No of Passengers</option>
										<option value="1" <?php if($no_of_person=="1") echo "selected";?>>1</option>
									    <option value="2" <?php if($no_of_person=="2") echo "selected";?>>2</option>
									    <option value="3" <?php if($no_of_person=="3") echo "selected";?>>3</option>
									    <option value="4" <?php if($no_of_person=="4") echo "selected";?>>4</option>
									    <option value="5" <?php if($no_of_person=="5") echo "selected";?>>5</option>
									    <option value="6" <?php if($no_of_person=="6") echo "selected";?>>6</option>
									    <option value="7" <?php if($no_of_person=="7") echo "selected";?>>7</option>
									    <option value="8" <?php if($no_of_person=="8") echo "selected";?>>8</option>
									    <option value="9" <?php if($no_of_person=="9") echo "selected";?>>9</option>														  
									  </select>														
									</div>
									
									
									
									<div class="col-sm-2" >
									  <button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" name="submit">															
											<span class="bigger-110">Search</span>
								     </button>
									</div>
								</form>
									     
									
									
									
								</div>
					
								<div style="background:#EFF3F8;border:1px solid #ccc" >
								  
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>												
												<th>T.No.</th>
												<th>Journey</th>
												<th>PNR</th>
                                                <th>Type</th>
												<th>Going Date</th>
												<th>Returning Date</th>	
												<th>Airline</th>
												<th>Class</th>
												<th>Rate</th> 												
												<th>Seats</th>
												<th>Agent</th>																																		
												<th>Book</th>
											
											</tr>
										</thead>
										<tbody id="grid">
											<?php
											if($sql_query!=null && $sql_query!="") {
												$result_query=mysql_query($sql_query);
											}
                      if($sql_query!=null && $sql_query!="" && mysql_num_rows($result_query)>0)
										 	{											 
											 while($row_query=mysql_fetch_array($result_query,MYSQL_ASSOC))
											 {
											?>
											 <tr>
												<td><?php echo $row_query["ticket_no"];?></td>
												<td><?php echo $row_query["source_city"]." <br>".$row_query["destination_city"];?></td>
												<td><?php echo $row_query["pnr"];?></td>
												<td><?php if($row_query["trip_type"]=="ONE") echo "ONE WAY"; else echo "RETURN TRIP";?></td>												
												<td>
												<i class="fa fa-plane" style="font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y",strtotime($row_query["departure_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($row_query["departure_date_time"])); ?>)<br/>
												<i class="fa fa-plane" style="transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y",strtotime($row_query["arrival_date_time"])); ?> </span>(<?php echo date("h:i a",strtotime($row_query["arrival_date_time"])); ?>)
												</td>
												<td>
												<?php if($row_query["trip_type"]=="ROUND") {?>
												<i class="fa fa-plane" style="font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y",strtotime($row_query["departure_date_time1"])); ?> </span>(<?php echo date("h:i a",strtotime($row_query["departure_date_time1"])); ?>)<br/>
												<i class="fa fa-plane" style="transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px"></i><span class="date"><?php echo date("jS M y",strtotime($row_query["arrival_date_time1"])); ?> </span>(<?php echo date("h:i a",strtotime($row_query["arrival_date_time1"])); ?>)
												<?php } ?>
											 
												</td>
												<td><?php echo $row_query["airline"];?></td>
												<td><?php echo $row_query["class"];?></td>
												<td><?php echo "<b>Supplier Rate : </b>".$row_query["total"]."<br> <b>Portal Rate : </b>".($row_query["total"]+$row_query["admin_markup"]);?></td>
												<td><?php echo $row_query["no_of_person"];?></td>
												<td><?php echo $row_query["name"];?><br><?php echo $row_query["uid"];?></td>
												<td>
												    <a class="red" href="book.php?id=<?php echo $row_query["ticket_no"];?>">NEW BOOKING</a><br/>
												    <a class="red" href="booking_against_request.php?id=<?php echo $row_query["ticket_no"];?>">BOOKING<br/> AGAINST REQUEST</a>
												    </td>
										
											 </tr>
											<?php
											 }
										 }
										 else
										 {
                                        ?>
										  <tr>
										   <td colspan="12" align="center" style="color:red">No Record to Display</td>
										  </tr>
                                        <?php
										 }
                                        ?> 										
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

 
 
 <script src="https://yourwebsite.co.in/oxytra/js/jquery.min.js"></script>
 <script>
	$(document).ready(function() 
	{	
        $("#menu_book_tickets").addClass("active");	
		$("#source").change(function()
		{
				
			 $.ajax
			 ({
				type: "POST",
				url: ""+baseurl+"/adminarea/ajax/filter_city.php", 
				data: {source:$("#source").val(),trip_type:$("#trip_type").val()},
				dataType: "json",  
				success: function(data)
				{	
				   	
                    $("#destination").html("");				
						$("#destination").append("<option value=''>Destination</option>");				
					$.each(data, function(key, value) 
					{  
										
							
															  
							  $("#destination").append("<option value='"+data[key]["id"]+"'>"+data[key]["city"]+"</option>");
						
						
					});
				}
			});
						
		});
		
		$("#trip_type").change(function()
		{
			$("#destination").html("");	
			$("#destination").append("<option value=''>Destination</option>");	
			 $.ajax
			 ({
				type: "POST",
				url: ""+baseurl+"/adminarea/ajax/filter_city1.php", 
				data: {trip_type:$("#trip_type").val()},
				dataType: "json",  
				success: function(data)
				{	
				   	
                    $("#source").html("");				
					$("#source").append("<option value=''>Source</option>");				
					$.each(data, function(key, value) 
					{  
										
							
															  
							  $("#source").append("<option value='"+data[key]["id"]+"'>"+data[key]["city"]+"</option>");
						
						
					});
				}
			});
						
		});
	});
</script>

<link rel="stylesheet" href="../calendar/base/jquery.ui.all.css">
<script src="../calendar/jquery-1.5.1.js"></script>
 <script src="../calendar/jquery.ui.core.js"></script>
 <script src="../calendar/jquery.ui.widget.js"></script>
 <script src="../calendar/jquery.ui.datepicker.js"></script> 
 <script>
	$(function() 
	{	
        
        
		$("#departure_date_time" ).datepicker();
		$("#departure_date_time" ).datepicker("option", "dateFormat","dd-mm-yy");	
        $("#departure_date_time" ).datepicker('setDate',date);		
	});
</script>