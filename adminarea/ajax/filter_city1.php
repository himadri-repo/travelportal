<?php
include_once('../../config.php');

$today=date("Y-m-d");
$sql="SELECT DISTINCT(c.city),c.id FROM tickets_tbl t 
INNER JOIN city_tbl c ON t.source=c.id 
WHERE t.trip_type='".$_REQUEST["trip_type"]."' 
AND DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')>=$today AND t.approved=1";


$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result,MYSQL_ASSOC))
{
   foreach($row as $k=>$v)
  {						     							 
		$response[$i][$k]=$v;                              								
  } 
	$i++;
 
}

echo json_encode($response);

?>