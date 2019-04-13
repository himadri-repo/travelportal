<?php
include_once('../../config.php');
$dt=new DateTime();
if(isset($_REQUEST["tag"]))
{
	$tag=$_REQUEST["tag"];
	$response=array();
	switch($tag)
	{
        case "search":		           					
					$arr=array();
					$start=$_REQUEST['start'];
					$dt_from=isset($_REQUEST['dt_from'])?$_REQUEST['dt_from']:"";
					$dt_to=isset($_REQUEST['$dt_to'])?$_REQUEST['$dt_to']:"";
					$field=trim($_REQUEST['field']);
					$value=trim($_REQUEST['value']);
					$allowEmptyStock=($_REQUEST['allowEmptyStock']==='true'?0:1);
					$start=$start*100;
					$limit=100;
					$total=0;
					if(empty($dt_from) && empty($dt_to))
					{
						if($value!="")
						{
							$sql="SELECT u.name,u.user_id as uid,t.*,source.city as source_city,destination.city as destination_city
									FROM tickets_tbl t
									INNER JOIN user_tbl u ON u.id = t.user_id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination							
								WHERE  $field='$value' and t.no_of_person>=$allowEmptyStock ORDER BY t.id DESC";
						}
						else
						{
							$sql="SELECT u.name,u.user_id as uid,t.*,source.city as source_city,destination.city as destination_city
									FROM tickets_tbl t
									INNER JOIN user_tbl u ON u.id = t.user_id
									INNER JOIN city_tbl source ON source.id = t.source
									INNER JOIN city_tbl destination ON destination.id = t.destination
								WHERE t.no_of_person>=$allowEmptyStock 
								ORDER BY t.id DESC";
						}
					}
          else
					{
						
						$dt_from=date("Y-m-d",strtotime($_REQUEST['dt_from']));
						$dt_to=date("Y-m-d",strtotime($_REQUEST['dt_to']));
						if($value!="")
						{
						    $sql="SELECT u.name,u.user_id as uid,t.*,source.city as source_city,destination.city as destination_city
								FROM tickets_tbl t
								INNER JOIN user_tbl u ON u.id = t.user_id
								INNER JOIN city_tbl source ON source.id = t.source
								INNER JOIN city_tbl destination ON destination.id = t.destination							
								WHERE t.no_of_person>=$allowEmptyStock and $field='$value' AND DATE_FORMAT(t.departure_date_time,'%Y-%m-%d')>='$dt_from' AND DATE_FORMAT(t.departure_date_time,'%Y-%m-%d')<='$dt_to' ORDER BY t.id DESC";
					    }
						else
						{
							$sql="SELECT u.name,u.user_id as uid,t.*,source.city as source_city,destination.city as destination_city
								FROM tickets_tbl t
								INNER JOIN user_tbl u ON u.id = t.user_id
								INNER JOIN city_tbl source ON source.id = t.source
								INNER JOIN city_tbl destination ON destination.id = t.destination							
								WHERE t.no_of_person>=$allowEmptyStock and DATE_FORMAT(t.departure_date_time,'%Y-%m-%d')>='$dt_from' AND DATE_FORMAT(t.departure_date_time,'%Y-%m-%d')<='$dt_to' ORDER BY t.id DESC";
						}
					}
					//echo $sql;
					//die;
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);	
						if(empty($value) && empty($dt_from) && empty($dt_to))
						     $sql.=" LIMIT $start,$limit";	
						 else
						     $sql.="";
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						    foreach($row as $k=>$v)
						    {						   
								if($k=='departure_date_time')
								{
								  $dt=new DateTime($v);
								  $dt=$dt->format('jS M Y h:i a');
								  $response[$i][$k]=$dt; 
								}
								else if($k=='arrival_date_time')
								{
								  if($v!="0000-00-00 00:00:00") 
								  {
    								  $dt=new DateTime($v);
    								  $dt=$dt->format('jS M Y h:i a');
    								  $response[$i][$k]=$dt; 
								  }
								  else
								  {
								      $response[$i][$k]="";
								  }
								}
								else if($k=='departure_date_time1')
								{
								  if($v!="0000-00-00 00:00:00") 
								  {    
    								  $dt=new DateTime($v);
    								  $dt=$dt->format('jS M Y h:i a');
    								  $response[$i][$k]=$dt; 
								  }
								  else
								  {
								      $response[$i][$k]="";
								  }
								}
								else if($k=='arrival_date_time1')
								{
								  if($v!="0000-00-00 00:00:00") 
								  {    
    								  $dt=new DateTime($v);
    								  $dt=$dt->format('jS M Y h:i a');
    								  $response[$i][$k]=$dt; 
								  }
								  else
								  {
								      $response[$i][$k]="";
								  }
								}
								else if($k=='trip_type')
								{
								  if($v=="ONE")
									  $response[$i][$k]="ONE WAY";
								  else
									 $response[$i][$k]="RETURN TRIP"; 
								}
								else if($k=='approved')
								{
								  if($v=="0")
										$response[$i][$k]="PENDING";
									if($v=="1")
										$response[$i][$k]="APPROVED"; 
									if($v=="2")
										$response[$i][$k]="REJECTED"; 
									if($v=="3")
										$response[$i][$k]="FREEZED"; 
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