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
					$s=trim($_POST['s']);
					$start=$start*100;
					$limit=100;
					$total=0;
					
					$sql="SELECT u . name,u.id as user_id,u.user_id as uid,p.*
							FROM user_tbl u
							INNER JOIN  payment_request_tbl p ON u.id = p.user_id
							
						WHERE  u.name LIKE '%$s%' ORDER BY p.id DESC";
									
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
												  						
					    $sql="SELECT u . name ,u.id as user_id,u.user_id as uid,p.*
							FROM user_tbl u
							INNER JOIN  payment_request_tbl p ON u.id = p.user_id
							
						WHERE  u.name LIKE '%$s%' ORDER BY p.id DESC LIMIT $start,$limit";
						
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {						   
								if($k=='request_date')
								{
								  $dt=new DateTime($v);
								  $dt=$dt->format('d-m-Y');
								  $response[$i][$k]=$dt; 
								}
                                else if($k=='status')
								{
								   if($v==0)
								     $response[$i][$k]="Pending"; 
								   else
									   $response[$i][$k]="Paid"; 
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