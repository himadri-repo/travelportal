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
						if(!empty($field) && !empty($value))
						{
							$sql="SELECT q.id,q.status,q.request_date,q.departure_date_time,q.departure_date_time1,q.trip_type,q.no_of_person,q.source_city,q.destination_city,
					                u.user_id,u.name,us.name as seller,us.user_id as seller_id,u.mobile as customer_mobile,us.mobile as supplier_mobile
									FROM quotation_tbl q
									
									INNER JOIN user_tbl u ON q.customer_id = u.id
									INNER JOIN user_tbl us ON q.supplier_id = us.id							
								WHERE  $field='$value'  ORDER BY q.id DESC";
						}
						else
						{   $sql="SELECT q.id,q.status,q.request_date,q.departure_date_time,q.departure_date_time1,q.trip_type,q.no_of_person,q.source_city,q.destination_city,
					                u.user_id,u.name,us.name as seller,us.user_id as seller_id,u.mobile as customer_mobile,us.mobile as supplier_mobile
									FROM quotation_tbl q
									
									INNER JOIN user_tbl u ON q.customer_id = u.id
									INNER JOIN user_tbl us ON q.supplier_id = us.id
									WHERE 1				
								ORDER BY q.id DESC";
						}
					}
                    else
					{
						
						$dt_from=date("Y-m-d",strtotime($_POST['dt_from']));
						$dt_to=date("Y-m-d",strtotime($_POST['dt_to']));
						if(!empty($field) && !empty($value))
						{
						    $sql="SELECT q.id,q.status,q.request_date,q.departure_date_time,q.departure_date_time1,q.trip_type,q.no_of_person,q.source_city,q.destination_city,
					                u.user_id,u.name,us.name as seller,us.user_id as seller_id,u.mobile as customer_mobile,us.mobile as supplier_mobile
									FROM quotation_tbl q
									
									INNER JOIN user_tbl u ON q.customer_id = u.id
									INNER JOIN user_tbl us ON q.supplier_id = us.id							
								WHERE  $field='$value'   AND DATE_FORMAT(q.request_date,'%Y-%m-%d')>='$dt_from' AND DATE_FORMAT(q.request_date,'%Y-%m-%d')<='$dt_to' ORDER BY q.id DESC";
					    }
						else
						{
							$sql="SELECT q.id,q.status,q.request_date,q.departure_date_time,q.departure_date_time1,q.trip_type,q.no_of_person,q.source_city,q.destination_city,
					                u.user_id,u.name,us.name as seller,us.user_id as seller_id,u.mobile as customer_mobile,us.mobile as supplier_mobile
									FROM quotation_tbl q
									
									INNER JOIN user_tbl u ON q.customer_id = u.id
									INNER JOIN user_tbl us ON q.supplier_id = us.id								
							        WHERE 1  AND DATE_FORMAT(q.request_date,'%Y-%m-%d')>='$dt_from' AND DATE_FORMAT(q.request_date,'%Y-%m-%d')<='$dt_to' ORDER BY q.id DESC";
									
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
								if($k=='request_date')
								{
								  $dt=new DateTime($v);
								  $dt=$dt->format('jS M Y');
								  $response[$i][$k]=$dt; 
								}
								else if($k=='departure_date_time')
								{
								  $dt=new DateTime($v);
								  $dt=$dt->format('jS M Y');
								  $response[$i][$k]=$dt; 
								}
								else if($k=='departure_date_time1')
								{
								 if($v=="0000-00-00")
								 {
								 $response[$i][$k]="";
								 }
								 else
								 {
								  $dt=new DateTime($v);
								  $dt=$dt->format('jS M Y');
								  $response[$i][$k]=$dt; 
								 }
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
 
        case "update":	
		     $id=$_POST['id'];
		     $status=$_POST['status'];
			 $sql="UPDATE quotation_tbl SET status='$status' WHERE id='$id'";
			 $result=mysql_query($sql);	
				if($result)
				{
					$response["success"]="Record Updated Successfully";	
					
				}	
				else
					$response["error"]=mysql_error();
        break;		
        
		
	}
}
echo json_encode($response);
?>