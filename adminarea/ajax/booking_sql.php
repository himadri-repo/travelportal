<?php
include_once('../../config.php');
$dt=new DateTime();
if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
        case "search":		           					
					$arr=array();
					$start=$_POST['start'];
					$dt_from=$_POST['dt_from'];
					$dt_to=$_POST['dt_to'];
					$field=trim($_POST['field']);
					$value=trim($_POST['value']);
					$start=$start*100;
					$limit=100;
					$total=0;
					if(empty($dt_from) && empty($dt_to))
					{
						if(!empty($value))
						{
							$sql="SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
									FROM booking_tbl b
									INNER JOIN tickets_tbl t ON b.ticket_id = t.id
									INNER JOIN user_tbl u ON b.customer_id = u.id
									INNER JOIN user_tbl us ON b.seller_id = us.id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination							
								WHERE  $field='$value' AND (b.status='PENDING') AND (t.sale_type!='live') ORDER BY b.id DESC";
						}
						else
						{   $sql="SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
									FROM booking_tbl b
									INNER JOIN tickets_tbl t ON b.ticket_id = t.id
									INNER JOIN user_tbl u ON b.customer_id = u.id
									INNER JOIN user_tbl us ON b.seller_id = us.id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination	WHERE 1		 AND (b.status='PENDING')  AND (t.sale_type!='live')				
								ORDER BY b.id DESC";
						}
					}
                    else
					{
						
						$dt_from=date("Y-m-d",strtotime($_POST['dt_from']));
						$dt_to=date("Y-m-d",strtotime($_POST['dt_to']));
						if(!empty($value))
						{
						    $sql="SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
									FROM booking_tbl b
									INNER JOIN tickets_tbl t ON b.ticket_id = t.id
									INNER JOIN user_tbl u ON b.customer_id = u.id
									INNER JOIN user_tbl us ON b.seller_id = us.id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination							
								WHERE  $field='$value'  AND (b.status='PENDING')  AND (t.sale_type!='live') AND DATE_FORMAT(b.date,'%Y-%m-%d')>='$dt_from' AND DATE_FORMAT(b.date,'%Y-%m-%d')<='$dt_to' ORDER BY b.id DESC";
					    }
						else
						{
							$sql="SELECT t.departure_date_time,b.id,b.date,b.pnr,b.status,b.rate,b.qty,b.amount,b.igst,b.service_charge,b.total,t.trip_type,u.user_id,u.name,us.name as seller,us.user_id as seller_id,source.city as source_city,destination.city as destination_city
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
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);						
						$sql.=" LIMIT $start,$limit";												
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						    foreach($row as $k=>$v)
						    {						   
								if($k=='date')
								{
								  $dt=new DateTime($v);
								  $dt=$dt->format('jS M Y h:i a');
								  $response[$i][$k]=$dt; 
								}
								else if($k=='departure_date_time')
								{
								  $dt=new DateTime($v);
								  $dt=$dt->format('jS M Y h:i a');
								  $response[$i][$k]=$dt; 
								}
								else if($k=='trip_type')
								{
								  if($v=="ONE")
									  $response[$i][$k]="ONE WAY";
								  else
									 $response[$i][$k]="RETURN TRIP"; 
								}
								
								else
								$response[$i][$k]=$v;                             							
						    } 
							$i++;				
						}
						  $response['records']=$ctr;
						  $response['total']=$total;						  

					}
					else
					{
					  $response['no_records']=TRUE;
					}

		break;       
        
		
	}
}
echo json_encode($response);
?>