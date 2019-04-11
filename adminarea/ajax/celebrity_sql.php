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
					
					$sql="SELECT u . * , SUM( w.amount ) AS wallet
							FROM user_tbl u
							LEFT JOIN wallet_tbl w ON u.id = w.user_id
							
						WHERE  u.name LIKE '%$s%' GROUP BY u.id";
									
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
												  						
					    $sql="SELECT u . * , SUM( w.amount ) AS wallet
							FROM user_tbl u
							LEFT JOIN wallet_tbl w ON u.id = w.user_id
							
						WHERE  u.name LIKE '%$s%' GROUP BY u.id order by u.id desc LIMIT $start,$limit";
						
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {						   
								if($k=='doj')
								{
								  $dt=new DateTime($v);
								  $dt=$dt->format('d-m-Y');
								  $response[$i][$k]=$dt; 
								}
								else if($k=='is_supplier')
								{
								  if($v==1)
								      $response[$i][$k]="Yes"; 
								  else
									   $response[$i][$k]="No"; 
								}
								else if($k=='is_customer')
								{
								  if($v==1)
								      $response[$i][$k]="Yes"; 
								  else
									   $response[$i][$k]="No"; 
								}
								else if($k=='active')
								{
								  if($v==1)
								      $response[$i][$k]="Yes"; 
								  else
									   $response[$i][$k]="No"; 
								}
							
								else if($k=='wallet')
								{
								  if($v=="")
								      $response[$i][$k]="0"; 
								  else
									   $response[$i][$k]=$v; 
								}
								else if($k=='credit_ac')
								{
								  if($v=="0")
								      $response[$i][$k]="No"; 
								  else
									   $response[$i][$k]="Yes"; 
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
        
							     
        case "delete": 
					$celeb_id=$_POST['celeb_id'];
					
					$sql="DELETE FROM  celebrity_tbl  WHERE id='$celeb_id'";
					$result=mysql_query($sql);
					if($result)
					{
					   
							$response['success']="Record Deleted  !!!";
											
					}
					else
					{ 
					  $response['no_records']=mysql_error();
					}
					
		break;
		
        	
	}
}
echo json_encode($response);
?>